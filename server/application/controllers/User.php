<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/23
 */

use \QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class User extends MY_Controller {

    function __construct()
    {
        //{"openId":"oQRE-5Sl-q-_UJfBlkoeCEbdCBPM","nickName":"\u8def\u5eb7","gender":1,"language":"zh_CN","city":"Chengdu","province":"Sichuan","country":"China","avatarUrl":"https:\/\/wx.qlogo.cn\/mmopen\/vi_32\/hBV3ichhl4jT3WibT2WNmsuZKtU6cUht3P6ACiaOPBK9wXI5myoSVdEJKpAT2iamZ1BfNKiaSxslWibPSYiae3f62ib1iag\/132","watermark":{"timestamp":1531114472,"appid":"wx344d68fb511b1677"}}
        parent::__construct();
        $this->load->model('user_model');
    }

    public function login(){
        $result = LoginService::login();
        if ($result['loginState'] === Constants::S_AUTH) {
            //保存用户数据到ya项目库
            $wx_user_info = $this->_object2array_pre($result['userinfo']);
            log_message('debug', '获得的微信用户信息：'.var_export($wx_user_info, true));
            $wx_user_info = $wx_user_info['userinfo'];
            $open_id = $wx_user_info['openId'];
            if ($this->user_model->count_user_by_open_id($open_id)){
                //更新
                $update_item = array(
                    'nick_name' => $wx_user_info['nickName'],
                    'province' => $wx_user_info['province'],
                    'logo_url' => $wx_user_info['avatarUrl'],
                    'last_visit_time' => date('Y-m-d H:i:s')
                );
                $this->user_model->update($open_id, $update_item);
            }else{
                //插入
                $insert_item = array(
                    'open_id' => $wx_user_info['openId'],
                    'nick_name' => $wx_user_info['nickName'],
                    'province' => $wx_user_info['province'],
                    'logo_url' => $wx_user_info['avatarUrl'],
                    'create_time' => date('Y-m-d H:i:s'),
                    'last_visit_time' => date('Y-m-d H:i:s'),
                );
                $this->user_model->insert_user($insert_item);
            }
            $user_ret = $this->user_model->select_user_by_open_id($open_id, array('user_id'));
            log_message('debug', '查询的user id = '.var_export($user_ret, true));
            if ($user_ret['user_id'] > 0){
                api_return_json(API_RET_SUCCESS, 'ok', array_merge($result['userinfo'], $user_ret));
                return;
            }else{
                api_return_json(API_RET_DB_ERROR, '数据库操作失败');
                return;
            }
        }else{
            api_return_json(API_RET_INVALID_INPUT, '用户未登录');
            return;
        }
    }

    public function getUserInfo(){
        $result = LoginService::check();
        if ($result['loginState'] === Constants::S_AUTH) {
            $wx_user_info = $this->_object2array_pre($result['userinfo']);
            log_message('debug', '获得的微信用户信息：'.var_export($wx_user_info, true));
            $wx_user_info = $wx_user_info['userinfo'];
            $user_ret = $this->user_model->select_user_by_open_id($wx_user_info['openId'], array('user_id'));
            if ($user_ret['user_id'] > 0){
                api_return_json(API_RET_SUCCESS, 'ok', array_merge($result['userinfo'], $user_ret));
                return;
            }else{
                api_return_json(API_RET_DB_ERROR, '数据库操作失败');
                return;
            }
        } else {
            api_return_json(API_RET_INVALID_INPUT, '用户未登录');
            return;
        }
    }

    public function logout(){

    }

    /**************查询用户**********************/
    private function _object2array_pre(&$object) {
        if (is_object($object)) {
            $arr = (array)($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach($arr as $varName => $varValue){
                $arr[$varName] = $this->_object2array($varValue);
            }
        }
        return $arr;
    }
    private function _object2array(&$object) {
        $object =  json_decode( json_encode( $object),true);
        return  $object;
    }
}