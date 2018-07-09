<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('debug')) {
    /**
     * 调试专用
     */
    function debug() {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $messages = array();

        for ($i = 0; $i < $numargs; $i += 1) {
            $message = $arg_list[$i];

            if (is_array($message)) {
                if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    $message = json_encode($message);
                }
            }

            if (is_string($message) || is_numeric($message)) {
                $messages[] = $message;
            }
        }

        log_message('debug', implode(' ', $messages) . "\n");
    }
}

if (!function_exists('request_post')) {
    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL, $postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);

        return $data;
    }
}



