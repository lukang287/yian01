<?php
// 目录入口
define('QCLOUDAPI_ROOT_PATH', dirname(__FILE__));
/**
 * QcloudApi
 * SDK入口文件
 */
class QcloudApi
{
    /*方法常量*/
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * MODULE_ACCOUNT
     * 用户账户
     */
    const MODULE_ACCOUNT   = 'account';

    /**
     * MODULE_ASR
     * 智能语音识别
     */
    const MODULE_ASR   = 'asr';
    
    /**
     * MODULE_CVM
     * 云服务器
     */
    const MODULE_CVM   = 'cvm';

    /**
     * MODULE_CDB
     * CDB数据库
     */
    const MODULE_CDB   = 'cdb';

    /**
     * MODULE_LB
     * 负载均衡
     */
    const MODULE_LB    = 'lb';

    /**
     * MODULE_TRADE
     * 产品售卖
     */
    const MODULE_TRADE = 'trade';
    
    /**
     * MODULE_BILL
     * 账单
     */
    const MODULE_BILL = 'bill';

    /**
     * MODULE_SEC
     * 云安全
     */
    const MODULE_SEC = 'sec';

    /**
     * MODULE_IMAGE
     * 镜像
     */
    const MODULE_IMAGE = 'image';

    /**
     * MODULE_MONITOR
     * 云监控
     */
    const MODULE_MONITOR = 'monitor';

    /**
     * MODULE_CDN
     * CDN
     */
    const MODULE_CDN = 'cdn';

    /**
     * MODULE_VPC
     * VPC
     */
    const MODULE_VPC = 'vpc';

    /**
     * MODULE_VOD
     * VOD
     */
    const MODULE_VOD = 'vod';
    
    /**
     * YUNSOU
     */
    const MODULE_YUNSOU = 'yunsou';
	
	  /**
     * cns
     */
    const MODULE_CNS = 'cns';
	
	  /**
     * wenzhi
     */
    const MODULE_WENZHI = 'wenzhi';
    
    /**
     * MARKET
     */
    const MODULE_MARKET = 'market';
    
    /**
     * MODULE_EIP
     * 弹性公网Ip
     */
    const MODULE_EIP = 'eip';
    
    /**
     * MODULE_LIVE
     * 直播
     */
    const MODULE_LIVE = 'live';

    /**
     * MODULE_SNAPSHOT
     * 快照
     */
    const MODULE_SNAPSHOT = 'snapshot';

    /**
     * MODULE_CBS
     * 云硬盘
     */
    const MODULE_CBS = 'cbs';
    
    /**
     * MODULE_SCALING
     * 弹性伸缩
     */
    const MODULE_SCALING = 'scaling';

    /**
     * load
     * 加载模块文件
     * @param  string $moduleName   模块名称
     * @param  array  $moduleConfig 模块配置
     * @return
     */
    public static function load($moduleName, $moduleConfig = array())
    {
        $moduleName = ucfirst($moduleName);
        $moduleClassFile = QCLOUDAPI_ROOT_PATH . '/Module/' . $moduleName . '.php';

        if (!file_exists($moduleClassFile)) {
            return false;
        }

        require_once $moduleClassFile;
        $moduleClassName = 'QcloudApi_Module_' . $moduleName;
        $moduleInstance = new $moduleClassName();

        if (!empty($moduleConfig)) {
            $moduleInstance->setConfig($moduleConfig);
        }

        return $moduleInstance;
    }
}
