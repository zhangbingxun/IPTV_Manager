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
-- 表的结构luo2888_epg
--

DROP TABLE IF EXISTS `luo2888_epg`;
CREATE TABLE `luo2888_epg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remarks` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 luo2888_epg
--

INSERT INTO `luo2888_epg` VALUES('1','cntv-cctv1','CCTV-1综合,CCTV-1','1','');
INSERT INTO `luo2888_epg` VALUES('2','cntv-cctv2','CCTV-2财经,CCTV-2','1','');
INSERT INTO `luo2888_epg` VALUES('3','cntv-cctv3','CCTV-3综艺,CCTV-3','1','');
INSERT INTO `luo2888_epg` VALUES('4','cntv-cctv4','CCTV-4国际,CCTV-4','1','');
INSERT INTO `luo2888_epg` VALUES('5','cntv-cctv5','CCTV-5体育,CCTV-5','1','');
INSERT INTO `luo2888_epg` VALUES('6','cntv-cctv5plus','CCTV-5Plus','1','');
INSERT INTO `luo2888_epg` VALUES('7','cntv-cctv6','CCTV-6','1','');
INSERT INTO `luo2888_epg` VALUES('8','cntv-cctv7','CCTV-7','1','');
INSERT INTO `luo2888_epg` VALUES('9','cntv-cctv8','CCTV-8','1','');
INSERT INTO `luo2888_epg` VALUES('10','cntv-cctvjilu','CCTV-9','1','');
INSERT INTO `luo2888_epg` VALUES('11','cntv-cctv10','CCTV-10','1','');
INSERT INTO `luo2888_epg` VALUES('12','cntv-cctv11','CCTV-11','1','');
INSERT INTO `luo2888_epg` VALUES('13','cntv-cctv12','CCTV-12','1','');
INSERT INTO `luo2888_epg` VALUES('14','cntv-cctv13','CCTV-13','1','');
INSERT INTO `luo2888_epg` VALUES('15','cntv-cctvchild','CCTV-14','1','');
INSERT INTO `luo2888_epg` VALUES('16','cntv-cctv15','CCTV-15','1','');
INSERT INTO `luo2888_epg` VALUES('17','cntv-cctv17','CCTV-17','1','');
INSERT INTO `luo2888_epg` VALUES('18','cntv-cetv1','CETV-1','1','');
INSERT INTO `luo2888_epg` VALUES('19','cntv-cetv2','CETV-2','1','');
INSERT INTO `luo2888_epg` VALUES('20','cntv-cetv3','CETV-3','1','');
INSERT INTO `luo2888_epg` VALUES('21','cntv-cetv4','CETV-4','1','');
INSERT INTO `luo2888_epg` VALUES('22','cntv-cctv4k','CCTV 4K超高清','1','');
INSERT INTO `luo2888_epg` VALUES('23','tvmao-ZJTV-ZJTV1','浙江卫视','1','');
INSERT INTO `luo2888_epg` VALUES('24','tvmao-JSTV-JSTV1','江苏卫视','1','');
INSERT INTO `luo2888_epg` VALUES('25','tvmao-HUNANTV-HUNANTV1','湖南卫视','1','');
