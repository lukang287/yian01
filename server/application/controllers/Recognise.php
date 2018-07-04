<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/4
 */
require_once '../third_party/QcloudApi/QcloudApi.php';

class Recognise extends CI_Controller{

    function index(){
        $wxconfig = $this->config->item('wx.asr.config');
        $service = QcloudApi::load(QcloudApi::MODULE_ASR);
        //设置SecretId/SecretKey
        $service->setConfig($wxconfig);
        $service->setConfigRequestMethod(QcloudApi::METHOD_POST);
        $service->setRequestPath($wxconfig['path']);

        $yian_track_id = generate_track_id();
        $params = array(
            'ProjectId' => $wxconfig['ProjectId'],
            'SubServiceType' => 2,
            'EngSerViceType' => '16k',
            'SourceType' => 1,
            'VoiceFormat' => 'mp3',
            'UsrAudioKey' => $yian_track_id,
            'Url' => '',
            'Data' => '',       //语音数据，当SourceType 值为1时必须填写，为0可不写。要base64编码。音频数据要小于900k
            'DataLen' => 0      //数据长度，当 SourceType 值为1时必须填写，为0可不写。
        );
        $action = 'SentenceRecognition';
        // 生成请求的URL，不发起请求
        $url = $service->generateUrl($action, $params);

        if ($url === false) {
            // 请求失败，解析错误信息
            $error = $service->getError();
            log_message('error', '请求wx失败:'.'Error code:' . $error->getCode() . ' message:' . $error->getMessage());
            // 对于异步任务接口，可以通过下面的方法获取对应任务执行的信息
            //$detail = $error->getExt();
            api_return_json(API_RET_THIRD_ERROR);
            return;
        } else {
            // 请求成功
            $ret = $service->__call();
            if ('fail' == $this->_wx_result($ret)){
                api_return_json(API_RET_THIRD_ERROR);
                return;
            }
            //成功
            api_return_json(API_RET_SUCCESS, $ret['Result']);
            return;
        }
    }

    private function _wx_result($wx_ret){
        $wx_error_list = array('InternalError','InternalError.ErrorConfigure','InternalError.ErrorCreateLog','InternalError.ErrorDownFile','InternalError.ErrorFailNewprequest','InternalError.ErrorFailWritetodb','InternalError.ErrorFileCannotopen','InternalError.ErrorGetRoute','InternalError.ErrorMakeLogpath','InternalError.ErrorRecognize','InvalidParameter.ErrorContentlength','InvalidParameter.ErrorParamsMissing','InvalidParameter.ErrorParsequest','InvalidParameterValue','InvalidParameterValue.ErrorInvalidAppid','InvalidParameterValue.ErrorInvalidClientip','InvalidParameterValue.ErrorInvalidEngservice','InvalidParameterValue.ErrorInvalidProjectid','InvalidParameterValue.ErrorInvalidRequestid','InvalidParameterValue.ErrorInvalidSourcetype','InvalidParameterValue.ErrorInvalidSubservicetype','InvalidParameterValue.ErrorInvalidUrl','InvalidParameterValue.ErrorInvalidUseraudiokey','InvalidParameterValue.ErrorInvalidVoicedata');

        if(!isset($wx_ret['Result']) || in_array($wx_ret['Result'], $wx_error_list)){
            //报错
            return 'fail';
        }
        return 'sucess';
    }
}





