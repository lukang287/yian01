<?php
/**
 * Created by PhpStorm.
 * User: lukang
 * Date: 2018/7/17
 */

// 微信智能语音配置
$config['wx.asr.config'] = array(
    'host' => 'aai.qcloud.com',
    'path' => '/asr/v1/1257035159',
    'appid'=> '1257035159',
    'ProjectId' => '1115426',//一桉日记
    'SecretId' => 'AKIDMWS9boTUxHPL4bJ2wd7OSJxOdfV88orh',
    'SecretKey' => 'rWyCE3f5II5f2FG4f7NQGsHTSEqERQBj'
);

// 百度智能语音配置
$config['baidu.asr.config'] = array(
    'APP_ID' => '11507359',
    'API_KEY' => '7n5nlpbemdDbGuoZUiYIb5Ge',
    'SECRET_KEY'=> 'kAitLEeep9ukzQKqyzIkqLt4lKYmeHKf'
);

//ftp 配置
$config['ftp.config'] = array(
    'host' => '111.230.248.221',
    'username' => 'ftpuser2',
    'password' => 'lk123456',
);