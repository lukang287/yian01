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
  `user_input_text` varchar(512) DEFAULT NULL COMMENT '用户输入文本',
  `voice_text_tx` varchar(512) DEFAULT NULL COMMENT '腾讯语言识别文本',
  `voice_text_bd` varchar(512) DEFAULT NULL COMMENT '百度语言识别文本',
  `voice_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_time` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`voice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ya_user`;
CREATE TABLE `ya_user` (
  `user_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(100) NOT NULL COMMENT '腾讯返回的open_id',
  `nick_name` varchar(256) DEFAULT NULL COMMENT '昵称',
  `province` varchar(256) DEFAULT NULL COMMENT '省份',
  `logo_url` varchar(512) DEFAULT NULL COMMENT '头像url',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `last_visit_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最近登录时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ya_baby`;
CREATE TABLE `ya_baby` (
  `baby_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(32) NOT NULL COMMENT '所属用户',
  `baby_name` varchar(256) NOT NULL DEFAULT "" COMMENT '宝宝名称',
  `sex` int(2) NOT NULL DEFAULT 1 COMMENT '性别1男，2女',
  `birth_day` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生日',
  `img_url` VARCHAR(256) DEFAULT "" COMMENT '宝宝图片',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`baby_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;