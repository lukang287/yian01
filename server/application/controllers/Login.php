<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class Login extends CI_Controller {
    public function index() {
        //{"openId":"oQRE-5Sl-q-_UJfBlkoeCEbdCBPM","nickName":"\u8def\u5eb7","gender":1,"language":"zh_CN","city":"Chengdu","province":"Sichuan","country":"China","avatarUrl":"https:\/\/wx.qlogo.cn\/mmopen\/vi_32\/hBV3ichhl4jT3WibT2WNmsuZKtU6cUht3P6ACiaOPBK9wXI5myoSVdEJKpAT2iamZ1BfNKiaSxslWibPSYiae3f62ib1iag\/132","watermark":{"timestamp":1531114472,"appid":"wx344d68fb511b1677"}}
        $result = LoginService::login();
        
        if ($result['loginState'] === Constants::S_AUTH) {
            $this->json([
                'code' => 0,
                'data' => $result['userinfo']
            ]);
        } else {
            $this->json([
                'code' => -1,
                'error' => $result['error']
            ]);
        }
    }
}
