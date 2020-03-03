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
-- 表的结构luo2888_appdata
--

DROP TABLE IF EXISTS `luo2888_appdata`;
CREATE TABLE `luo2888_appdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataver` int(11) NOT NULL,
  `appver` varchar(16) NOT NULL,
  `setver` int(11) NOT NULL DEFAULT '0',
  `dataurl` varchar(255) NOT NULL,
  `appurl` varchar(255) NOT NULL,
  `adtext` varchar(1024) NOT NULL,
  `qqinfo` varchar(255) DEFAULT NULL,
  `showtime` int(11) NOT NULL,
  `showinterval` int(11) NOT NULL,
  `needauthor` int(11) NOT NULL DEFAULT '1',
  `splash` varchar(255) NOT NULL,
  `decoder` int(11) NOT NULL DEFAULT '0',
  `buffTimeOut` int(11) NOT NULL DEFAULT '10',
  `tipusernoreg` varchar(100) NOT NULL,
  `tipuserexpired` varchar(100) NOT NULL,
  `tipuserforbidden` varchar(100) NOT NULL,
  `tiploading` varchar(100) NOT NULL,
  `ipcount` int(11) NOT NULL DEFAULT '5',
  `trialdays` int(11) DEFAULT NULL,
  `autoupdate` int(11) DEFAULT '1',
  `randkey` varchar(100) DEFAULT '827ccb0eea8a706c4c34a16891f84e7b',
  `updateinterval` int(11) DEFAULT '15',
  `up_size` varchar(16) NOT NULL,
  `up_sets` int(11) NOT NULL,
  `up_text` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_appdata
--

INSERT INTO `luo2888_appdata` VALUES('1','2','1.0','1','http://127.0.0.1/data.php','https://127.0.0.1/tv.apk','欢迎关注微信公众号@luo2888的工作室。','欢迎关注微信公众号@luo2888的工作室。','120','5','1','http://127.0.0.1/images/tv.png','1','30','未被授权使用，请联系公众号客服，@luo2888的工作室。','账号已到期，请联系公众号客服续费。','账号已禁用，请联系公众号客服。','正在连接，请稍后 ...','2','-999','1','827ccb0eea8a706c4c34a16891f84e7b','10','0.0M','0','1.公告测试');
