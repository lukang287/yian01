<?php
/**
 * 使用腾讯智能语音API
 * User: Administrator
 * Date: 2018/7/7 0007
 */

include_once dirname(__FILE__).'/../third_party/tencentSar.php';

class Recognize extends CI_Controller{

    public function index(){
        // 处理文件上传
        log_message('debug', '接收到的音频数据为： '.var_export($_FILES, true));

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

        //获取配置
        $wxconfig = $this->config->item('wx.asr.config');

        //识别引擎 8k_0 or 16k_0
        $engine_model_type='16k_0';
        //结果返回方式 0：同步返回 or 1：尾包返回
        $res_type=0;
        // 识别结果文本编码方式 0:UTF-8,1:GB2312,2:GBK,3:BIG5
        $result_text_format=0;
        // 语音编码方式 1:wav 4:sp 6:skill
        $voice_format=4;

        $filepath=dirname(__FILE__).'/Rec0001.wav';/*$file['tmp_name'];*/
        // 语音切片长度 cutlength<200000
        $cutlength=35000;
        $ret = sendvoice($wxconfig['SecretKey'],$wxconfig['SecretId'],$wxconfig['appid'],$engine_model_type,$res_type,$result_text_format,$voice_format,$filepath,$cutlength);
        if (!$ret){
            log_message('debug', '语音识别失败');

        }
        api_return_json(API_RET_SUCCESS);
        return;
    }
}