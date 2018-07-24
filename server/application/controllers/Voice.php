<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/23
 */
use FtpClient\FtpClient;

//百度支持的语音模型常量
const BAIDU_CHN_EN = 1536;//普通话(支持简单的英文识别)搜索模型	无标点	支持自定义词库
const BAIDU_CHN = 1537;//普通话(纯中文识别)	输入法模型	有标点	不支持自定义词库
const BAIDU_EN = 1737; //英语		有标点	不支持自定义词库
const BAIDU_YUEYU = 1637;//粤语		有标点	不支持自定义词库
const BAIDU_SICHUAN = 1837;//四川话		有标点	不支持自定义词库
const BAIDU_CHN_YUAN = 1936;//普通话远场	远场模型	有标点	不支持

class Voice extends MY_Controller
{
    private $voice_id = '';
    private $local_mp3_file = '';
    private $local_pcm_file = '';
    private $mp3_len = 0;
    private $remote_ftp_path = '/';

    private $ftp_config = array();
    private $ftp_client = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('voice_model');
        //连接ftp
        $this->ftp_config = $this->config->item('ftp.config');
        $this->ftp_client = new FtpClient();
        $this->ftp_client->connect($this->ftp_config['host']);
        $this->ftp_client->login($this->ftp_config['username'], $this->ftp_config['password']);
    }

    //百度语音识别
    public function recognize_bd($user_id){
        require dirname(__FILE__).'/../third_party/aip-speech-php-sdk-1.6.0/AipSpeech.php';
        $file = $_FILES['file']; // 去除 field 值为 file 的文件
        // 处理文件上传
        log_message('debug', '接收到的音频数据为： '.var_export($_FILES, true));
        $this->voice_id = generate_unique_id();
        $this->local_mp3_file = APPPATH.'cache/voice_'.$this->voice_id.'.mp3';
        //接收文件处理
        if(!$this->_get_recv_mp3($file)){
            api_return_json(API_RET_INVALID_INPUT, '接收MP3文件失败');
            return ;
        }
        //转码到pcm
        if (!$this->_convert_to_pcm()){
            api_return_json(API_RET_SYSTEM_ERROR, 'MP3转码pcm失败');
            return ;
        }
        //发送到百度获取识别文本，更新数据库
        $access_token = $this->_get_baidu_accessToken();
        if (!$access_token){
            api_return_json(API_RET_THIRD_ERROR, '获取百度access token失败');
            return;
        }
        $baiduconfig = $this->config->item('baidu.asr.config');
        $client = new AipSpeech($baiduconfig['APP_ID'], $baiduconfig['API_KEY'], $baiduconfig['SECRET_KEY']);
        // 识别本地文件
        $yian_track_id = generate_unique_id();
        $res = $client->asr(file_get_contents($this->local_pcm_file), 'pcm', 16000, array(
            'dev_pid' => BAIDU_CHN_EN, 'cuid'=> $yian_track_id, 'token' => $access_token
        ));
        //echo 'baidu res - '.var_export($res, true);
        log_message('debug', '百度语言识别返回的结果为:'.var_export($res, true));
        if (isset($res['err_no']) && $res['err_no'] === 0){
            $baidu_msg = $res['result'][0];
            //成功，不带标点的
            //上传文件到ftp，写入到数据库
            if (is_file($this->local_mp3_file)){
                $remote_file = $this->remote_ftp_path.'voice_'.$this->voice_id.'.mp3';
                $ftp_res = $this->ftp_client->put($remote_file, $this->local_mp3_file, $this->ftp_config['ftp_model']);
                if (!$ftp_res){
                    log_message('error', '上报本地语言MP3文件到ftp server失败:'.$this->local_mp3_file);
                    api_return_json(API_RET_SYSTEM_ERROR, '上报本地语言MP3文件到ftp server失败');
                    return;
                }
                //ftp文件存入数据库
                $db_insert = array(
                    'voice_id' => $this->voice_id,
                    'user_id' => $user_id,
                    'voice_ftp_path' => $this->remote_ftp_path,
                    'user_input_text' => '',
                    'voice_text_tx' => '',
                    'voice_text_bd' => $baidu_msg,
                    'voice_status' => 0,
                    'create_time' => date('Y-m-d H:i:s'),
                    'update_time' => '',
                );
                $db_ret = $this->voice_model->insert($db_insert);
                if (!$db_ret){
                    log_message('error', '存入数据库失败:'.var_export($db_insert, true));
                    api_return_json(API_RET_DB_ERROR, '存入数据库失败');
                    return;
                }
            }
            //删除本地文件
            @unlink($this->local_mp3_file);
            @unlink($this->local_pcm_file);
            //返回处理结果
            $ret_msg = array(array('code'=> 0, 'text'=>$baidu_msg, 'message'=>'ok'));
            api_return_json(API_RET_SUCCESS, 'success', $ret_msg);
            return;
        }else{
            //失败
            log_message('error', '百度语言识别失败，返回的结果为:'.var_export($res, true));
            //删除本地文件
            @unlink($this->local_mp3_file);
            @unlink($this->local_pcm_file);
            api_return_json(API_RET_THIRD_ERROR, '语言识别失败', $res);
            return;
        }
    }

    //腾讯语言识别
    public function recognize_tx(){

    }

    //用户手输入语音文本
    public function user_input(){

    }

    //获取用户的语言列表
    public function get_list(){

    }

    //更新语音文本
    public function update($voice_id){
        //获取用户id

    }

    //删除语音
    public function delete($voice_id){
        //获取用户id

    }

    /***********************私有方法***********************************************/
    private function _get_recv_mp3($file){

        ini_set('upload_max_filesize', '1M');
        ini_set('post_max_size', '2M');

        // 限制文件格式，支持图片上传
        if ($file['type'] !== 'audio/mp3' && $file['type'] !== 'audio/mpeg') {
            api_return_json(API_RET_INVALID_INPUT, '不支持的上传语音类型：' . $file['type']);
            return false;
        }

        // 限制文件大小：1M 以内
        if ($file['size'] > 900 * 1024) {
            api_return_json(API_RET_INVALID_INPUT, '文件大小不能超过900K，当前文件大小为：' . $file['size']);
            return false;
        }

        //文件内容
        $rawContent = file_get_contents($file['tmp_name']);
        $this->mp3_len = strlen($rawContent);
        file_put_contents($this->local_mp3_file, $rawContent);
        return true;
    }

    private function _convert_to_pcm(){
        $this->local_pcm_file = APPPATH.'cache/c_voice_'.$this->voice_id.'.pcm';
        //mp3 文件转 16K 16bits 位深的单声道 pcm文件
        $ffmpeg_cmd = sprintf("ffmpeg -y  -i %s -acodec pcm_s16le -f s16le -ac 1 -ar 16000 %s", $this->local_mp3_file, $this->local_pcm_file);
        $convert_res = system($ffmpeg_cmd);
        if ($convert_res === false){
            log_message('error', 'MP3转码pcm失败:'.$this->local_mp3_file.' 转换结果：'.var_export($convert_res, true));
            return false;
        }
        return true;
    }

    private function _get_baidu_accessToken(){
        $baiduconfig = $this->config->item('baidu.asr.config');
        $req = array('grant_type'=>'client_credentials',
            'client_id' => $baiduconfig['API_KEY'],
            'client_secret' => $baiduconfig['SECRET_KEY']);

        include_once dirname(__FILE__).'/../third_party/aip-speech-php-sdk-1.6.0/lib/AipHttpClient.php';
        $client = new AipHttpClient();
        $ret = $client->post('https://openapi.baidu.com/oauth/2.0/token', $req);
        //access Token存入memcache
        if($ret['code'] == '200'){
            $res = json_decode($ret['content'], true);
            return isset($res['access_token'])?$res['access_token']:false;
        }
        return false;
    }
}