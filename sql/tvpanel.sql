-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-03-13 02:05:10
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4


--
-- 数据库： `tvpanel`
--

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_admin`
--

CREATE TABLE `luo2888_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `psw` varchar(32) NOT NULL,
  `showcounts` tinyint(1) NOT NULL DEFAULT '20',
  `author` tinyint(1) NOT NULL DEFAULT '0',
  `useradmin` tinyint(1) NOT NULL DEFAULT '0',
  `ipcheck` tinyint(1) NOT NULL DEFAULT '0',
  `epgadmin` tinyint(1) NOT NULL DEFAULT '0',
  `mealsadmin` tinyint(1) NOT NULL DEFAULT '0',
  `channeladmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_admin`
--

INSERT INTO `luo2888_admin` (`id`, `name`, `psw`, `showcounts`, `author`, `useradmin`, `ipcheck`, `epgadmin`, `mealsadmin`, `channeladmin`) VALUES
(1, 'admin', '8114c88b2062d554b895f92bd3d7b9b8', 20, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_adminrec`
--

CREATE TABLE `luo2888_adminrec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `loc` varchar(32) DEFAULT NULL,
  `time` varchar(64) NOT NULL,
  `func` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_category`
--

CREATE TABLE `luo2888_category` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `psw` varchar(16) DEFAULT NULL,
  `type` varchar(16) NOT NULL DEFAULT 'default',
  `url` varchar(1024) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_category`
--

INSERT INTO `luo2888_category` (`id`, `name`, `enable`, `psw`, `type`, `url`) VALUES
(1, '试看频道', 1, '', 'default', NULL),
(2, 'HomeNET', 1, '', 'default', 'https://gitee.com/homenet6/list/raw/master/nj.txt'),
(3, 'Sason', 1, '', 'default', 'https://raw.githubusercontent.com/sasoncheung/iptv/master/all.txt'),
(50, '重庆', 1, '', 'province', NULL),
(51, '河南', 1, '', 'province', NULL),
(52, '广东', 1, '', 'province', NULL),
(53, '湖北', 1, '', 'province', NULL),
(54, '河北', 1, '', 'province', NULL),
(55, '安徽', 1, '', 'province', NULL),
(56, '江西', 1, '', 'province', NULL),
(57, '黑龙江', 1, '', 'province', NULL),
(58, '天津', 1, '', 'province', NULL),
(59, '上海', 1, '', 'province', NULL),
(60, '山西', 1, '', 'province', NULL),
(61, '吉林', 1, '', 'province', NULL),
(62, '江苏', 1, '', 'province', NULL),
(63, '福建', 1, '', 'province', NULL),
(64, '海南', 1, '', 'province', NULL),
(65, '贵州', 1, '', 'province', NULL),
(66, '云南', 1, '', 'province', NULL),
(67, '陕西', 1, '', 'province', NULL),
(68, '西藏', 1, '', 'province', NULL),
(69, '宁夏', 1, '', 'province', NULL),
(70, '内蒙古', 1, '', 'province', NULL),
(71, '北京', 1, '', 'province', NULL),
(72, '湖南', 1, '', 'province', NULL),
(73, '广西', 1, '', 'province', NULL),
(74, '甘肃', 1, '', 'province', NULL),
(75, '浙江', 1, '', 'province', NULL),
(76, '新疆', 1, '', 'province', NULL),
(77, '山东', 1, '', 'province', NULL),
(78, '四川', 1, '', 'province', NULL),
(250, '隐藏频道', 1, '12345', 'vip', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_channels`
--

CREATE TABLE `luo2888_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `category` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_channels`
--

INSERT INTO `luo2888_channels` (`id`, `name`, `url`, `category`) VALUES
(1, '测试', 'http://127.0.0.1', '试看频道'),
(2, '测试', 'http://127.0.0.1', '隐藏频道');

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_config`
--

CREATE TABLE `luo2888_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_config`
--

INSERT INTO `luo2888_config` (`id`, `name`, `value`) VALUES
(1, 'adinfo', '欢迎关注微信公众号@luo2888的工作室。'),
(2, 'adtext', '欢迎关注微信公众号@luo2888的工作室。'),
(3, 'appurl', 'https://127.0.0.1/tv.apk'),
(4, 'appurl_sdk14', 'https://127.0.0.1/tv_sdk14.apk'),
(5, 'appver', '1.0'),
(6, 'appver_sdk14', '1.0'),
(7, 'app_appname', 'IPTV'),
(8, 'app_packagename', 'cn.tv.player'),
(9, 'app_sign', '12315'),
(10, 'autoupdate', '1'),
(11, 'buffTimeOut', '30'),
(12, 'dataver', '1'),
(13, 'decoder', '1'),
(14, 'epg_api_chk', '0'),
(15, 'ipcount', '2'),
(16, 'ip_chk', '1'),
(17, 'max_sameip_user', '5'),
(18, 'needauthor', '1'),
(19, 'randkey', '6d7caa26b6de5941e3b24fd7c573d0bb'),
(20, 'secret_key', NULL),
(21, 'setver', '3'),
(22, 'showtime', '120'),
(23, 'showinterval', '5'),
(24, 'showwea', '0'),
(25, 'tipepgerror_1000', '1000_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室'),
(26, 'tipepgerror_1001', '1001_EPG接口验证失败系!如有疑问请联系公众号客服：luo2888的工作室'),
(27, 'tipepgerror_1002', '1002_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室'),
(28, 'tipepgerror_1003', '1003_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室'),
(29, 'tipepgerror_1004', '1004_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室'),
(30, 'tipepgerror_1005', '1005_EPG接口验证失败!如有疑问请联系公众号客服：luo2888的工作室'),
(31, 'tiploading', '正在连接，请稍后 ...'),
(32, 'tipuserexpired', '账号已到期，请联系公众号客服续费。'),
(33, 'tipuserforbidden', '账号已禁用，请联系公众号客服。'),
(34, 'tipusernoreg', '未被授权使用，请联系公众号客服，@luo2888的工作室。'),
(35, 'trialdays', '-999'),
(36, 'updateinterval', '10'),
(37, 'up_size', '0.0M'),
(38, 'up_sets', '0'),
(39, 'up_text', '1.公告测试'),
(40, 'weaapi_id', NULL),
(41, 'weaapi_key', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_epg`
--

CREATE TABLE `luo2888_epg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remarks` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_epg`
--

INSERT INTO `luo2888_epg` (`id`, `name`, `content`, `status`, `remarks`) VALUES
(1, 'cntv-cctv1', 'CCTV-1', 1, NULL),
(2, 'cntv-cctv2', 'CCTV-2', 1, NULL),
(3, 'cntv-cctv3', 'CCTV-3', 1, NULL),
(4, 'cntv-cctv4', 'CCTV-4', 1, NULL),
(5, 'cntv-cctv5', 'CCTV-5', 1, NULL),
(6, 'cntv-cctv5plus', 'CCTV-5Plus', 1, NULL),
(7, 'cntv-cctv6', 'CCTV-6', 1, NULL),
(8, 'cntv-cctv7', 'CCTV-7', 1, NULL),
(9, 'cntv-cctv8', 'CCTV-8', 1, NULL),
(10, 'cntv-cctvjilu', 'CCTV-9', 1, NULL),
(11, 'cntv-cctv10', 'CCTV-10', 1, NULL),
(12, 'cntv-cctv11', 'CCTV-11', 1, NULL),
(13, 'cntv-cctv12', 'CCTV-12', 1, NULL),
(14, 'cntv-cctv13', 'CCTV-13', 1, NULL),
(15, 'cntv-cctvchild', 'CCTV-14', 1, NULL),
(16, 'cntv-cctv15', 'CCTV-15', 1, NULL),
(17, 'cntv-cctv17', 'CCTV-17', 1, NULL),
(18, 'cntv-cetv1', 'CETV-1', 1, NULL),
(19, 'cntv-cetv2', 'CETV-2', 1, NULL),
(20, 'cntv-cetv3', 'CETV-3', 1, NULL),
(21, 'cntv-cetv4', 'CETV-4', 1, NULL),
(22, 'cntv-cctv4k', 'CCTV 4K超高清', 1, NULL),
(23, 'tvmao-ZJTV-ZJTV1', '浙江卫视', 1, NULL),
(24, 'tvmao-JSTV-JSTV1', '江苏卫视', 1, NULL),
(25, 'tvmao-HUNANTV-HUNANTV1', '湖南卫视', 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_loginrec`
--

CREATE TABLE `luo2888_loginrec` (
  `userid` bigint(15) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(32) DEFAULT NULL,
  `logintime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_meals`
--

CREATE TABLE `luo2888_meals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `luo2888_meals`
--

INSERT INTO `luo2888_meals` (`id`, `name`, `content`, `status`) VALUES
(1000, '试看套餐', '试看频道', 1),
(1002, '会员套餐', 'HomeNET_Sason_重庆_河南_广东_湖北_河北_安徽_江西_黑龙江_天津_上海_山西_吉林_江苏_福建_海南_贵州_云南_陕西_西藏_宁夏_内蒙古_北京_湖南_广西_甘肃_浙江_新疆_山东_四川_隐藏频道', 1);

-- --------------------------------------------------------

--
-- 表的结构 `luo2888_users`
--

CREATE TABLE `luo2888_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` bigint(20) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `deviceid` varchar(32) NOT NULL,
  `model` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `region` varchar(32) DEFAULT NULL,
  `exp` bigint(20) NOT NULL,
  `vpn` tinyint(5) NOT NULL DEFAULT '0',
  `author` varchar(16) DEFAULT NULL,
  `authortime` bigint(20) NOT NULL DEFAULT '0',
  `status` int(4) NOT NULL DEFAULT '-1',
  `lasttime` bigint(20) NOT NULL,
  `marks` varchar(16) DEFAULT NULL,
  `meal` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac` (`mac`,`deviceid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
