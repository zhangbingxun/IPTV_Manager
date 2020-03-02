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
-- 表的结构luo2888_loginrec
--

DROP TABLE IF EXISTS `luo2888_loginrec`;
CREATE TABLE `luo2888_loginrec` (
  `userid` bigint(15) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(32) NOT NULL,
  `logintime` int(11) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
