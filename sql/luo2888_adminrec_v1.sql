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
-- 表的结构luo2888_adminrec
--

DROP TABLE IF EXISTS `luo2888_adminrec`;
CREATE TABLE `luo2888_adminrec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `loc` varchar(64) NOT NULL,
  `time` varchar(64) NOT NULL,
  `func` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_adminrec
--

INSERT INTO `luo2888_adminrec` VALUES('1','admin','127.0.0.1','保留地址','2020-01-01 00:00:00','用户登入');
INSERT INTO `luo2888_adminrec` VALUES('2','test','127.0.0.1','保留地址','2020-01-01 00:00:00','尝试登陆');
