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
-- 表的结构luo2888_category
--

DROP TABLE IF EXISTS `luo2888_category`;
CREATE TABLE `luo2888_category` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  `psw` varchar(16) DEFAULT '',
  `type` varchar(16) NOT NULL DEFAULT 'default',
  `url` varchar(1024) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_category
--

INSERT INTO `luo2888_category` VALUES('1','HomeNET','1','','default','https://gitee.com/homenet6/list/raw/master/nj.txt');
INSERT INTO `luo2888_category` VALUES('2','默认频道','1','','default','');
INSERT INTO `luo2888_category` VALUES('3','隐藏频道','1','12345','vip','');
