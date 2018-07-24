/**
 * 小程序配置文件
 */

// 此处主机域名修改成腾讯云解决方案分配的域名
var host = 'https://api.pslelec.com';//'https://kck3s5el.qcloud.la';

var config = {

    // 下面的地址配合云端 Demo 工作
    service: {
        host,

        // 登录地址，用于建立会话
        loginUrl: `${host}/user/login`,

        // 测试的请求地址，用于测试会话
        requestUrl: `${host}/user/getUserInfo`,


        //语音识别接口
        voiceUrl: `${host}/voice/recognize_bd`,

        // 获取用户语言列表
        voiceListUrl: `${host}/voice/get_list`,
    }
};

module.exports = config;