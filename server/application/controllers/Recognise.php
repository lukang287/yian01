<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/4
 */
//require_once dirname(__FILE__).'/../third_party/QcloudApi/QcloudApi.php';
require_once dirname(__FILE__).'/../third_party/TCloudAutoLoader.php';
// 导入对应产品模块的client
use TencentCloud\Aai\V20180522\AaiClient;
// 导入要请求接口对应的Request类
use TencentCloud\Aai\V20180522\Models\SentenceRecognitionRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
// 导入可选配置类
//use TencentCloud\Common\Profile\ClientProfile;
//use TencentCloud\Common\Profile\HttpProfile;


class Recognise extends CI_Controller{

    function index(){
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

        //文件内容
        $rawSource = file_get_contents($file['tmp_name']);
        $voiceContent = $rawSource;
        $voiceContentLen = strlen($rawSource);

        //创建Asr服务对象
        $wxconfig = $this->config->item('wx.asr.config');
        try {
            // 实例化一个证书对象，入参需要传入腾讯云账户secretId，secretKey
            /*// 实例化一个http选项，可选的，没有特殊需求可以跳过
            $httpProfile = new HttpProfile();
            $httpProfile->setReqMethod("GET");  // post请求(默认为post请求)
            $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
            // 实例化一个client选项，可选的，没有特殊需求可以跳过
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);*/

            $cred = new Credential($wxconfig['SecretId'], $wxconfig['SecretKey']);

            // # 实例化要请求产品(以cvm为例)的client对象
            $client = new AaiClient($cred, "ap-guangzhou");

            // 实例化一个请求对象
            $req = new SentenceRecognitionRequest();

            // 通过client对象调用想要访问的接口，需要传入请求对象
            $action = 'SentenceRecognition';
            $yian_track_id = generate_track_id();
            $params = array(
                'Action' => $action,
                'Version' => '2018-05-22',
                'ProjectId' => $wxconfig['ProjectId'],
                'SubServiceType' => 2,
                'EngSerViceType' => '16k',
                'SourceType' => 1,
                'VoiceFormat' => 'mp3',
                'UsrAudioKey' => $yian_track_id,
                'Data' => $voiceContent,       //语音数据，当SourceType 值为1时必须填写，为0可不写。要base64编码。音频数据要小于900k
                'DataLen' => $voiceContentLen      //数据长度，当 SourceType 值为1时必须填写，为0可不写。
            );
            //$req->fromJsonString(json_encode($params));
            $resp = $client->__call($action, $params);

            print_r($resp->toJsonString());
        }
        catch(TencentCloudSDKException $e) {
            echo $e;
        }
        return;


        $yian_track_id = generate_track_id();
        $params = array(
            'Action' => $action,
            'Version' => '2018-05-22',
            'ProjectId' => $wxconfig['ProjectId'],
            'SubServiceType' => 2,
            'EngSerViceType' => '16k',
            'SourceType' => 1,
            'VoiceFormat' => 'mp3',
            'UsrAudioKey' => $yian_track_id,
            'Data' => $voiceContent,       //语音数据，当SourceType 值为1时必须填写，为0可不写。要base64编码。音频数据要小于900k
            'DataLen' => $voiceContentLen      //数据长度，当 SourceType 值为1时必须填写，为0可不写。
        );
        // 生成请求的URL，不发起请求
        $url = $service->generateUrl($action, $params);
        log_message('debug', '生成的请求url：'.$url);

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
            $ret = $service->__call($action, $params);
            log_message('debug', '获取微信语音识别结果为： '.var_export($ret, true));
            $error = $service->getError();
            log_message('error', '请求wx失败:'.'Error code:' . $error->getCode() . ' message:' . $error->getMessage());
            if (!$ret){
                api_return_json(API_RET_THIRD_ERROR, 'Error code:' . $error->getCode() . ' message:' . $error->getMessage());
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
            return false;
        }
        return true;
    }
}





