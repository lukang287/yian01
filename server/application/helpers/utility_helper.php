<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/4
 */

function generate_unique_id()
{
    // seed the better random number generator
    mt_srand((double)microtime() * 10000);
    // md5 a random string
    $unique_id = strtoupper(md5(uniqid(rand(), true)));
    return $unique_id;
}

function api_return_json($error_code,$message = '', $data=array()){
    $CI = &get_instance();
    $reason = empty($message)?ApiConstants::getErrorMsg($error_code):$message;
    return $CI->json(array('code'=>$error_code, 'reason'=>$reason, 'data'=>$data));
}
