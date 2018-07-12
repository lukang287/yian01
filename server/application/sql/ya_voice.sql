/*
Navicat MySQL Data Transfer

Source Server         : 腾讯云01
Source Server Version : 50640
Source Host           : 111.230.248.221:3306
Source Database       : yian_front

Target Server Type    : MYSQL
Target Server Version : 50640
File Encoding         : 65001

Date: 2018-07-12 23:41:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ya_voice
-- ----------------------------
DROP TABLE IF EXISTS `ya_voice`;
CREATE TABLE `ya_voice` (
  `voice_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(32) NOT NULL,
  `voice_ftp_path` varchar(255) NOT NULL,
  `user_input_text` varchar(512) DEFAULT '0' COMMENT '0正常，1删除',
  `voice_text_tx` varchar(512) DEFAULT NULL COMMENT '腾讯语言识别文本',
  `voice_text_bd` varchar(512) DEFAULT NULL COMMENT '百度语言识别文本',
  `voice_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1删除',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_time` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`voice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
