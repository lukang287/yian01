<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/4
 */

/*api 接口常量类*/
const API_RET_SUCCESS=0;
const API_RET_INVALID_INPUT=1;
const API_RET_DB_ERROR=2;
const API_RET_THIRD_ERROR=3;
const API_RET_SYSTEM_ERROR = 4;
const API_RET_UNKNOWN_ERROR=99;

class ApiConstants {
    private static $const_arr = array(
        API_RET_SUCCESS => 'success',
        API_RET_INVALID_INPUT => 'invalid input parameter',
        API_RET_DB_ERROR => 'database error',
        API_RET_THIRD_ERROR => 'thrid party return error',
        API_RET_UNKNOWN_ERROR => 'unknown error',
        API_RET_SYSTEM_ERROR => 'system error',
    );

    public static function getErrorMsg($error_code){
        if (!in_array($error_code, array_keys(self::$const_arr))){
            return 'unknown error';
        }
        return self::$const_arr[$error_code];
    }

    //mp3 文件转 16K 16bits 位深的单声道 pcm文件
    private static $ffmpeg_cmd = "ffmpeg -y  -i aidemo.mp3  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 16k.pcm";

}
