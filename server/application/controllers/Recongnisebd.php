<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/8 0008
 * Time: 23:50
 */

require dirname(__FILE__).'/../third_party/aip-speech-php-sdk-1.6.0/AipSpeech.php';

class Recongnisebd extends My_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("voice_model");
    }

    public function index(){

        // 处理文件上传
       /* log_message('debug', '接收到的音频数据为： '.var_export($_FILES, true));

        $file = $_FILES['file']; // 去除 field 值为 file 的文件

        ini_set('upload_max_filesize', '1M');
        ini_set('post_max_size', '2M');

        // 限制文件格式，支持图片上传
        if ($file['type'] !== 'audio/mp3' && $file['type'] !== 'audio/mpeg') {
            api_return_json(API_RET_INVALID_INPUT, '不支持的上传语音类型：' . $file['type']);
            return;
        }

        // 限制文件大小：1M 以内
        if ($file['size'] > 900 * 1024) {
            api_return_json(API_RET_INVALID_INPUT, '文件大小不能超过900K，当前文件大小为：' . $file['size']);
            return;
        }

        //文件内容
        $rawContent = file_get_contents($file['tmp_name']);

        $at = $this->_get_baidu_at();
        return;*/
        $baiduconfig = $this->config->item('baidu.asr.config');
        $access_token = $this->get_baidu_accessToken();
        if (!$access_token){
            api_return_json(API_RET_THIRD_ERROR, '获取百度access token失败');
            return;
        }

        $client = new AipSpeech($baiduconfig['APP_ID'], $baiduconfig['API_KEY'], $baiduconfig['SECRET_KEY']);
        // 识别本地文件
        $yian_track_id = generate_track_id();
        $res = $client->asr(file_get_contents(dirname(__FILE__).'/wx16k.pcm'), 'pcm', 16000, array(
            'dev_pid' => 1536,'cuid'=> $yian_track_id, 'token' => $access_token
        ));
        //echo 'baidu res - '.var_export($res, true);
        if (isset($res['err_no']) && $res['err_no'] === 0){
            //成功，不带标点的
            $ret_msg = array(array('code'=> 0, 'text'=>$res['result'], 'message'=>'ok'));
            api_return_json(API_RET_SUCCESS, 'success', $ret_msg);
        }else{
            //失败
            api_return_json(API_RET_THIRD_ERROR, $res['err_msg'], $res);
        }
        return;
    }

    private function get_baidu_accessToken(){
        $baiduconfig = $this->config->item('baidu.asr.config');
        $req = array('grant_type'=>'client_credentials',
            'client_id' => $baiduconfig['API_KEY'],
            'client_secret' => $baiduconfig['SECRET_KEY']);

        include_once dirname(__FILE__).'/../third_party/aip-speech-php-sdk-1.6.0/lib/AipHttpClient.php';
        $client = new AipHttpClient();
        $ret = $client->post('https://openapi.baidu.com/oauth/2.0/token', $req);
        log_message('debug', '获得百度accesstoken = '.var_export($ret, true));
        //access Token存入memcache
        if($ret['code'] == '200'){
            $res = json_decode($ret['content'], true);
            return isset($res['access_token'])?$res['access_token']:false;
        }
        return false;
    }
}
