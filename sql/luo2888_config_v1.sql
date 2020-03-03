--
-- MySQL database dump
-- Created by DbManage class, Power By yanue. 
-- http://yanue.net 
--
-- 生成日期: 2020 年  03 月 03 日 03:09
-- MySQL版本: 5.5.62-log
-- PHP 版本: 7.2.25

--
-- 数据库: ``
--

-- -------------------------------------------------------

--
-- 表的结构luo2888_config
--

DROP TABLE IF EXISTS `luo2888_config`;
CREATE TABLE `luo2888_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_config
--

INSERT INTO `luo2888_config` VALUES('1','secret_key','');
INSERT INTO `luo2888_config` VALUES('2','epg_api_chk','0');
INSERT INTO `luo2888_config` VALUES('3','ip_chk','1');
INSERT INTO `luo2888_config` VALUES('4','showwea','0');
INSERT INTO `luo2888_config` VALUES('5','weaapi_id','');
INSERT INTO `luo2888_config` VALUES('6','weaapi_key','');
INSERT INTO `luo2888_config` VALUES('7','app_sign','12315');
INSERT INTO `luo2888_config` VALUES('8','app_appname','IPTV');
INSERT INTO `luo2888_config` VALUES('9','app_packagename','cn.tv.player');
INSERT INTO `luo2888_config` VALUES('10','jisuapi_key','');
INSERT INTO `luo2888_config` VALUES('11','max_sameip_user','5');
