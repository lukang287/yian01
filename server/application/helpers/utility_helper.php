<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/4
 */

function generate_track_id()
{
    // seed the better random number generator
    mt_srand((double)microtime() * 10000);
    // md5 a random string
    $char_id = strtoupper(md5(uniqid(rand(), true)));
    // define the hyphen character
    //$hyphen = chr(45);
    // create the guid
    //$guid = substr($char_id, 0, 8) . $hyphen . substr($char_id, 8, 4) . $hyphen . substr($char_id, 12, 4).$hyphen . substr($char_id, 16, 4) . $hyphen . substr($char_id, 20, 12);
    // return the guid here
    return $char_id;
}

function api_return_json($error_code,$message = '', $data=array()){
    $CI = &get_instance();
    $reason = empty($message)?ApiConstants::getErrorMsg($error_code):$message;
    return $CI->json(array('code'=>$error_code, 'reason'=>$reason, 'data'=>$data));
}
