
-- ----------------------------
-- Table structure for ya_voice
-- ----------------------------
DROP TABLE IF EXISTS `ya_voice`;
CREATE TABLE `ya_voice` (
  `voice_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(32) NOT NULL,
  `voice_path` varchar(255) NOT NULL,
  `voice_text_tx` varchar(512) DEFAULT NULL COMMENT '腾讯语言识别文本',
  `voice_text_bd` varchar(512) DEFAULT NULL COMMENT '百度语言识别文本',
  `voice_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1删除',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_time` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`voice_id`,`voice_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
