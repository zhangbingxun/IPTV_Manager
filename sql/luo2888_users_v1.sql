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
-- 表的结构luo2888_users
--

DROP TABLE IF EXISTS `luo2888_users`;
CREATE TABLE `luo2888_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` bigint(15) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `model` varchar(200) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(50) NOT NULL,
  `exp` int(11) NOT NULL,
  `vpn` int(11) NOT NULL DEFAULT '0',
  `author` varchar(16) NOT NULL,
  `authortime` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '-1',
  `lasttime` int(11) NOT NULL,
  `marks` varchar(100) NOT NULL,
  `isvip` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`mac`,`deviceid`)
) ENGINE=InnoDB AUTO_INCREMENT=7897 DEFAULT CHARSET=utf8;
