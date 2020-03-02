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
-- 表的结构luo2888_admin
--

DROP TABLE IF EXISTS `luo2888_admin`;
CREATE TABLE `luo2888_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `psw` varchar(32) NOT NULL,
  `showcounts` tinyint(4) NOT NULL DEFAULT '20',
  `author` tinyint(4) NOT NULL DEFAULT '0',
  `useradmin` tinyint(4) NOT NULL DEFAULT '0',
  `ipcheck` tinyint(4) NOT NULL DEFAULT '0',
  `epgadmin` tinyint(4) NOT NULL DEFAULT '0',
  `channeladmin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_admin
--

INSERT INTO `luo2888_admin` VALUES('1','admin','8114c88b2062d554b895f92bd3d7b9b8','20','1','1','1','1','1');
