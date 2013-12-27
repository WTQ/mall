-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 18 日 10:17
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `mallbuilder`
--

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_activity`
--

CREATE TABLE `mallbuilder_activity` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `ads_code` varchar(100) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `templates` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  `displayorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_activity`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_activity_product_list`
--

CREATE TABLE `mallbuilder_activity_product_list` (
  `id` int(10) NOT NULL auto_increment,
  `activity_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `member_id` int(11) NOT NULL,
  `member_name` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `displayorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_activity_product_list`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin`
--

CREATE TABLE `mallbuilder_admin` (
  `id` int(3) NOT NULL auto_increment,
  `user` char(30) NOT NULL,
  `name` varchar(50) default NULL,
  `password` char(35) NOT NULL,
  `group_id` smallint(5) NOT NULL default '0',
  `desc` text,
  `logonums` int(5) default '0',
  `lastlogotime` int(11) default NULL,
  `logoip` varchar(30) default NULL,
  `province` varchar(60) default NULL,
  `city` varchar(60) default NULL,
  `area` varchar(60) default NULL,
  `type` smallint(1) unsigned default NULL COMMENT '1manager',
  `lang` varchar(10) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- 导出表中的数据 `mallbuilder_admin`
--

INSERT INTO `mallbuilder_admin` (`id`, `user`, `name`, `password`, `group_id`, `desc`, `logonums`, `lastlogotime`, `logoip`, `province`, `city`, `area`, `type`, `lang`) VALUES
(1, 'admin', NULL, '21232f297a57a5a743894a0e4a801fc3', 0, NULL, 1847, 1371548841, '127.0.0.1', NULL, NULL, NULL, 1, 'cn'),
(95, 'fdsg', 'ggd', 'ff9e747b03c22f2be9090b78402130e1', 56, '', 0, NULL, NULL, '北京市', '东城区', '', NULL, 'cn'),
(94, '5', 'fg', 'e5bb23797bfea314a3db43d07dbd6a74', 56, '', 0, NULL, NULL, '河南省', '濮阳市', '濮阳县', NULL, 'cn');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin_group`
--

CREATE TABLE `mallbuilder_admin_group` (
  `group_id` smallint(5) unsigned NOT NULL auto_increment COMMENT '组Id',
  `group_name` varchar(60) NOT NULL COMMENT '组名称',
  `group_perms` text NOT NULL COMMENT '组权限',
  `group_desc` varchar(250) NOT NULL COMMENT '组描述',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户组' AUTO_INCREMENT=57 ;

--
-- 导出表中的数据 `mallbuilder_admin_group`
--

INSERT INTO `mallbuilder_admin_group` (`group_id`, `group_name`, `group_perms`, `group_desc`) VALUES
(56, 'aaa', '51cfabe8a3f1fe94e64a4b82da1c66e5,51cfabe8a3f1fe94e64a4b82da1c66e5,a87ff679a2f3e71d9181a67b7542122c,51cfabe8a3f1fe94e64a4b82da1c66e5,2ed37efec916b0d7a0c6316caa6fad5b,822e21ddc725fd0f531a53267c7f56a3,aa7c5817f29b31daa7ddf0fa0ae5a32b,51cfabe8a3f1fe94e64a4b82da1c66e5', 'aaa');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin_operation_log`
--

CREATE TABLE `mallbuilder_admin_operation_log` (
  `id` int(5) NOT NULL auto_increment,
  `user` varchar(20) default NULL,
  `scriptname` varchar(50) default NULL,
  `url` varchar(200) default NULL,
  `time` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_admin_operation_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_advs`
--

CREATE TABLE `mallbuilder_advs` (
  `ID` int(4) NOT NULL auto_increment,
  `width` varchar(10) default NULL,
  `height` varchar(10) default NULL,
  `ad_type` tinyint(1) NOT NULL default '1',
  `name` varchar(50) NOT NULL default '',
  `onurl` varchar(200) default NULL,
  `group` varchar(50) default NULL,
  `con` mediumtext,
  `date` datetime default NULL,
  `price` decimal(10,2) default NULL,
  `unit` enum('day','week','month') NOT NULL,
  `total` tinyint(4) default '0' COMMENT '数量',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- 导出表中的数据 `mallbuilder_advs`
--

INSERT INTO `mallbuilder_advs` (`ID`, `width`, `height`, `ad_type`, `name`, `onurl`, `group`, `con`, `date`, `price`, `unit`, `total`) VALUES
(1, '100', '500', 1, '所有页面头部', NULL, '首页', '', '2012-08-26 18:57:43', 36.00, 'day', 100),
(8, '520', '324', 2, '首页幻灯片', NULL, '商城', '', '2013-03-15 10:51:42', 0.00, 'day', 0),
(9, '', '', 1, '商城封页类别下', NULL, '商城', '', '2012-04-22 08:18:56', 0.00, 'day', 0),
(10, '', '', 1, '商城产品列表上', NULL, '商城', '', '2012-04-22 08:19:02', 0.00, 'day', 0),
(11, '', '', 1, '商城产品列表右', NULL, '商城', '', '2012-04-22 08:19:07', 0.00, 'day', 0),
(12, '', '', 1, '商城产品详情左', NULL, '商城', '', '2012-04-22 08:19:14', 0.00, 'day', 0),
(18, '', '', 1, '公司封面类别上', NULL, '公司', '', '2012-04-22 08:21:02', 0.00, 'day', 0),
(19, '', '', 1, '公司封面右', NULL, '公司', '', '2012-04-22 08:21:08', 0.00, 'day', 0),
(20, '', '', 1, '公司列表左', NULL, '公司', '', '2012-04-22 08:21:13', 0.00, 'day', 0),
(21, '', '', 1, '公司列表右', NULL, '公司', '', '2012-04-22 08:21:19', 0.00, 'day', 0),
(27, '', '', 1, '新闻封面中', NULL, NULL, '', '2011-09-05 12:25:27', 0.00, 'day', 0),
(28, '', '', 1, '新闻列表左', NULL, NULL, '', '2011-09-05 16:49:18', 0.00, 'day', 0),
(29, '', '', 1, '新闻详情左', NULL, NULL, '', '2011-09-05 17:01:47', 0.00, 'day', 0),
(5, '100', '100', 1, '', '', NULL, NULL, NULL, NULL, 'day', 0),
(37, '100', '100', 1, '', '', NULL, NULL, NULL, NULL, 'day', 0),
(36, '100', '100', 1, '', '', NULL, NULL, NULL, NULL, 'day', 0),
(30, '100', '100', 1, '', '', NULL, NULL, NULL, NULL, 'day', 0),
(31, '100', '100', 1, '', '', NULL, NULL, NULL, NULL, 'day', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_advs_con`
--

CREATE TABLE `mallbuilder_advs_con` (
  `ID` int(4) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `group_id` int(5) default NULL,
  `name` varchar(50) NOT NULL default '',
  `type` varchar(20) default NULL,
  `url` varchar(200) default NULL,
  `con` mediumtext,
  `picName` varchar(50) NOT NULL default '',
  `isopen` int(1) default '0',
  `ctime` int(11) default NULL,
  `province` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `area` varchar(50) default NULL,
  `width` char(4) default NULL,
  `height` char(4) default NULL,
  `catid` int(8) default NULL,
  `unit` enum('day','week','month') default NULL,
  `show_time` tinyint(4) default '0' COMMENT '展出时间',
  `statu` tinyint(1) default '0' COMMENT '0:待支付,1:购买成功,',
  `shownum` int(11) unsigned default '1' COMMENT '展示次数',
  `stime` int(10) unsigned default NULL,
  `etime` int(10) unsigned default NULL,
  `sort_num` tinyint(3) unsigned default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- 导出表中的数据 `mallbuilder_advs_con`
--

INSERT INTO `mallbuilder_advs_con` (`ID`, `userid`, `group_id`, `name`, `type`, `url`, `con`, `picName`, `isopen`, `ctime`, `province`, `city`, `area`, `width`, `height`, `catid`, `unit`, `show_time`, `statu`, `shownum`, `stime`, `etime`, `sort_num`) VALUES
(1, NULL, 1, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "pub-4124985563260650";\r\n/* 所有页面头部 */\r\ngoogle_ad_slot = "4075435918";\r\ngoogle_ad_width = 728;\r\ngoogle_ad_height = 90;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 0, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 11829, 1330099200, 1361721600, 0),
(77, NULL, 9, '', '3', 'http://', '', '1351565497.jpg', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 181, 1351526400, 1354464000, 0),
(5, NULL, 11, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "pub-4124985563260650";\r\n/* 产品列表右 */\r\ngoogle_ad_slot = "4445364967";\r\ngoogle_ad_width = 120;\r\ngoogle_ad_height = 240;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 312, NULL, NULL, 0),
(6, NULL, 10, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "pub-4124985563260650";\r\n/* 产品列表上 */\r\ngoogle_ad_slot = "1404105526";\r\ngoogle_ad_width = 728;\r\ngoogle_ad_height = 90;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 499, NULL, NULL, 0),
(76, NULL, 8, '', '3', 'http://', '', '761370322751.jpg', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 1467, 1350748800, 1385222400, 0),
(8, NULL, 12, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "pub-4124985563260650";\r\n/* 产品详情左 */\r\ngoogle_ad_slot = "1946228338";\r\ngoogle_ad_width = 250;\r\ngoogle_ad_height = 250;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 305, NULL, NULL, 0),
(17, NULL, 21, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "pub-4124985563260650";\r\n/* 公司列表右 */\r\ngoogle_ad_slot = "3563010812";\r\ngoogle_ad_width = 200;\r\ngoogle_ad_height = 200;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 196, NULL, NULL, 0),
(22, NULL, 27, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-4124985563260650";\r\n/* 新闻封面中 */\r\ngoogle_ad_slot = "3335817674";\r\ngoogle_ad_width = 728;\r\ngoogle_ad_height = 90;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 351, NULL, NULL, 0),
(23, NULL, 28, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-4124985563260650";\r\n/* 新闻列表左 */\r\ngoogle_ad_slot = "1497912939";\r\ngoogle_ad_width = 728;\r\ngoogle_ad_height = 90;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 68, NULL, NULL, 0),
(24, NULL, 29, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-4124985563260650";\r\n/* 新闻详情左 */\r\ngoogle_ad_slot = "5031017890";\r\ngoogle_ad_width = 250;\r\ngoogle_ad_height = 250;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 169, NULL, NULL, 0),
(32, NULL, 36, '', '2', '', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-4124985563260650";\r\n/* 图库封面左 */\r\ngoogle_ad_slot = "6443988026";\r\ngoogle_ad_width = 728;\r\ngoogle_ad_height = 90;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', '', 1, NULL, '', '', NULL, '', '', 0, NULL, 0, 0, 124, NULL, NULL, 0),
(72, 48, 1, '所有页面头部', '1', '111', 'qqqqqqqqqqqqqqqqqqqq', '', 1, 1345976616, NULL, NULL, NULL, '', '', 0, 'day', 2, 1, 974, 1345824000, 1346083200, 0),
(74, 48, 1, '所有页面头部', NULL, NULL, NULL, '', 0, 1345979787, NULL, NULL, NULL, NULL, NULL, NULL, 'day', 1, 0, 929, NULL, NULL, 0),
(75, 1, 1, '所有页面头部', NULL, NULL, NULL, '', 0, 1349609590, NULL, NULL, NULL, NULL, NULL, NULL, 'day', 1, 0, 638, NULL, NULL, 0),
(78, NULL, 1, 'ye', '3', 'http://', '', '1367471143.jpg', 1, NULL, '', '', '', '', '', 0, NULL, 0, 0, 9, 1367424000, 1370361600, 0),
(79, NULL, 8, 'd', '3', 'http://', '', '1370322773.jpg', 1, NULL, '', '', '', '', '', 0, NULL, 0, 0, 133, 1370275200, 1373212800, 0),
(80, NULL, 8, 'h', '3', 'http://', '', '1370322792.jpg', 1, NULL, '', '', '', '', '', 0, NULL, 0, 0, 132, 1370275200, 1373212800, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_album`
--

CREATE TABLE `mallbuilder_album` (
  `id` int(8) NOT NULL auto_increment,
  `catid` int(11) unsigned default NULL,
  `userid` int(8) default NULL,
  `zname` varchar(50) default NULL,
  `con` text,
  `fb` char(2) default NULL,
  `user` varchar(30) default NULL,
  `time` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_album`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_announcement`
--

CREATE TABLE `mallbuilder_announcement` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `url` varchar(100) default NULL COMMENT '跳转链接',
  `create_time` int(10) unsigned NOT NULL COMMENT '发布时间',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态 0 为关闭 1为开启',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_announcement`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_auditing`
--

CREATE TABLE `mallbuilder_auditing` (
  `itemid` int(11) NOT NULL auto_increment,
  `itemtype` varchar(10) NOT NULL,
  `argument` varchar(100) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `uptime` int(11) NOT NULL,
  `statu` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_auditing`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_brand`
--

CREATE TABLE `mallbuilder_brand` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  `char_index` char(1) NOT NULL,
  `catid` int(11) NOT NULL,
  `logo` varchar(150) NOT NULL,
  `displayorder` smallint(6) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `create_time` int(10) unsigned NOT NULL,
  `hits` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- 导出表中的数据 `mallbuilder_brand`
--

INSERT INTO `mallbuilder_brand` (`id`, `name`, `char_index`, `catid`, `logo`, `displayorder`, `status`, `create_time`, `hits`) VALUES
(1, 'Nike/耐克', 'N', 55, 'http://localhost/mallbuilder/uploadfile/all/2013/05/31/1369977103.jpg', 255, 1, 1370485748, 0),
(2, 'Adidas/阿迪达斯', 'A', 55, 'http://localhost/mallbuilder/uploadfile/all/2013/05/31/1369976320.jpg', 255, 2, 1369976329, 0),
(3, 'Asics/亚瑟士', 'A', 55, 'http://localhost/mallbuilder/uploadfile/all/2013/05/31/1369976369.jpg', 255, 2, 1369976370, 0),
(4, 'New Balance/新百伦', 'N', 55, 'http://localhost/mallbuilder/uploadfile/all/2013/05/31/1369976394.jpg', 255, 2, 1369976407, 0),
(5, 'NBA', 'N', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197273.jpg', 255, 1, 1371197279, 0),
(6, 'Le coq sportif/公鸡', 'L', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197313.jpg', 255, 1, 1371197315, 0),
(7, 'crocs/卡骆驰', 'c', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197338.jpg', 255, 1, 1371197340, 0),
(8, 'CONVERSE/匡威', 'C', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197423.jpg', 255, 1, 1371197425, 0),
(9, 'Fila/斐乐', 'F', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197450.jpg', 255, 1, 1371197459, 0),
(10, 'ANTA/安踏', 'A', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197484.jpg', 255, 1, 1371197486, 0),
(11, 'THE NORTH FACE/北面', 'T', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197516.jpg', 255, 1, 1371197517, 0),
(12, '乔丹体育', 'q', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197541.jpg', 255, 1, 1371197543, 0),
(13, '361度', '3', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197560.jpg', 255, 1, 1371197562, 0),
(14, 'ARC‘TERYX', 'A', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197664.jpg', 255, 1, 1371197665, 0),
(15, 'New Balance/新百伦', 'N', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197708.jpg', 255, 1, 1371197709, 0),
(16, 'Mizuno/美津浓', 'M', 54, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197725.jpg', 255, 1, 1371197726, 0),
(17, 'K-boxing/劲霸', 'K', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197754.jpg', 255, 1, 1371197755, 0),
(18, '浪莎', 'l', 50, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371197775.jpg', 255, 1, 1371197777, 0),
(19, 'Septwolves/七匹狼', 'S', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198358.jpg', 255, 1, 1371198359, 0),
(20, 'Camel/骆驼', 'C', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198376.jpg', 255, 1, 1371198378, 0),
(21, 'Gulao＆Shayu/古老鲨鱼', 'G', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198396.jpg', 255, 1, 1371198399, 0),
(22, 'Peacebird/太平鸟', 'P', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198424.jpg', 255, 1, 1371198426, 0),
(23, 'Jack Jones/杰克琼斯', 'J', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198443.jpg', 255, 1, 1371198444, 0),
(24, 'Fairwhale/马克华菲', 'F', 51, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198461.jpg', 255, 0, 1371198462, 0),
(25, 'Bolon/暴龙', 'B', 103, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198761.jpg', 255, 1, 1371198769, 0),
(26, 'Rayban/雷朋', 'R', 103, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198843.jpg', 255, 1, 1371198844, 0),
(27, 'Prada/普拉达', 'P', 103, 'http://localhost/mallbuilder/uploadfile/all/2013/06/14/1371198861.jpg', 255, 1, 1371198864, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_brand_cat`
--

CREATE TABLE `mallbuilder_brand_cat` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `displayorder` smallint(6) NOT NULL default '255',
  `catname` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104 ;

--
-- 导出表中的数据 `mallbuilder_brand_cat`
--

INSERT INTO `mallbuilder_brand_cat` (`id`, `parent_id`, `displayorder`, `catname`) VALUES
(39, 0, 255, '鞋、箱包'),
(38, 0, 255, '服饰、内衣、配件'),
(40, 0, 255, '珠宝、手表'),
(41, 0, 255, '化妆品'),
(42, 0, 255, '运动、户外'),
(43, 0, 255, '手机、数码'),
(44, 0, 255, '家用电器'),
(45, 0, 255, '家具、建材'),
(46, 0, 255, '居家生活'),
(47, 0, 255, '食品、医药'),
(48, 0, 255, '母婴用品'),
(49, 0, 255, '汽车、配件 '),
(50, 38, 0, '女装'),
(51, 38, 0, '男装'),
(52, 38, 0, '内衣'),
(53, 38, 0, '服饰配件 '),
(54, 42, 0, '运动服饰配件'),
(55, 42, 0, '运动鞋'),
(56, 42, 0, '运动器械'),
(57, 42, 0, '户外装备 '),
(90, 82, 255, 'gfgf'),
(82, 0, 255, '13'),
(100, 40, 255, '品牌手表/流行手表'),
(101, 40, 255, '铂金'),
(102, 40, 255, '饰品'),
(103, 40, 255, 'ZIPPO/眼镜/军刀');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_comment`
--

CREATE TABLE `mallbuilder_comment` (
  `id` int(11) NOT NULL auto_increment,
  `conid` int(10) default NULL COMMENT '被评论内容的ID',
  `fromuid` int(10) default NULL COMMENT '评论者会员ID',
  `fromname` varchar(50) default NULL,
  `ctype` int(1) default NULL COMMENT '被评论内容的类型',
  `rank` tinyint(1) unsigned NOT NULL default '1',
  `content` text COMMENT '评论的内容',
  `uptime` int(11) default NULL COMMENT '评论时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_contags`
--

CREATE TABLE `mallbuilder_contags` (
  `tagname` char(20) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `type` tinyint(4) default NULL,
  KEY `tid` (`tid`),
  KEY `tagname_2` (`tagname`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_contags`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_cron`
--

CREATE TABLE `mallbuilder_cron` (
  `id` int(6) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `script` varchar(50) default NULL,
  `lasttransact` int(10) default NULL,
  `nexttransact` int(10) default NULL,
  `week` varchar(12) default '-1',
  `day` varchar(2) default '-1',
  `hours` varchar(2) default '00',
  `minutes` varchar(2) default '00',
  `active` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_cron`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_custom_cat`
--

CREATE TABLE `mallbuilder_custom_cat` (
  `id` int(10) NOT NULL auto_increment,
  `pid` int(11) unsigned default '0',
  `sys_cat` int(11) default NULL,
  `userid` int(8) default NULL,
  `type` tinyint(4) default NULL,
  `name` varchar(60) default NULL,
  `des` char(200) default NULL,
  `tj` tinyint(1) default '0',
  `nums` int(11) default '0',
  `pic` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_custom_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_custom_service`
--

CREATE TABLE `mallbuilder_custom_service` (
  `id` int(10) NOT NULL auto_increment COMMENT '客服id',
  `uid` int(10) NOT NULL COMMENT '会员id',
  `name` varchar(20) NOT NULL COMMENT '客服名称',
  `tool` tinyint(1) NOT NULL COMMENT '客服工具',
  `number` varchar(30) NOT NULL COMMENT '客服账号',
  `type` tinyint(1) NOT NULL COMMENT '客服类型 0-售前客服 1-售后客服',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_custom_service`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_delivery_address`
--

CREATE TABLE `mallbuilder_delivery_address` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `provinceid` int(11) NOT NULL,
  `cityid` int(11) NOT NULL,
  `areaid` int(11) NOT NULL,
  `area` varchar(255) NOT NULL,
  `address` varchar(50) NOT NULL,
  `zip` int(10) unsigned NOT NULL,
  `tel` varchar(30) default NULL,
  `mobile` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_delivery_address`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_district`
--

CREATE TABLE `mallbuilder_district` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `sorting` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `upid` (`pid`,`sorting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_district`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_fast_mail`
--

CREATE TABLE `mallbuilder_fast_mail` (
  `id` int(11) NOT NULL auto_increment,
  `company` varchar(30) default NULL,
  `introduction` text,
  `url` varchar(30) default NULL,
  `logo` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_fast_mail`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_feed`
--

CREATE TABLE `mallbuilder_feed` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) default NULL,
  `company` varchar(100) default NULL,
  `contact` varchar(30) default NULL,
  `email` varchar(30) default NULL,
  `mes` text,
  `iflook` char(2) default NULL,
  `province` varchar(30) default NULL,
  `city` varchar(30) default NULL,
  `tell` varchar(30) default NULL,
  `addr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_feed`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_filter_keyword`
--

CREATE TABLE `mallbuilder_filter_keyword` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `keyword` varchar(50) default NULL,
  `replace` varchar(50) default NULL,
  `statu` tinyint(1) default '1',
  `time` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_filter_keyword`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_logistics_temp`
--

CREATE TABLE `mallbuilder_logistics_temp` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `title` varchar(50) default NULL,
  `price_type` varchar(50) default NULL COMMENT '按件数  按重量  按体积 ',
  `country` varchar(80) default NULL,
  `privince` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_logistics_temp`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_logistics_temp_con`
--

CREATE TABLE `mallbuilder_logistics_temp_con` (
  `id` int(11) NOT NULL auto_increment,
  `temp_id` int(11) default NULL,
  `logistics_type` varchar(50) default NULL COMMENT 'EMS,平邮,快递',
  `default_num` smallint(3) default NULL,
  `default_price` float(5,0) default NULL,
  `add_num` smallint(3) default NULL,
  `add_price` float(5,0) default NULL,
  `define_citys` text,
  PRIMARY KEY  (`id`),
  KEY `temp_id` (`temp_id`,`logistics_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_logistics_temp_con`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_mail_mod`
--

CREATE TABLE `mallbuilder_mail_mod` (
  `id` int(8) NOT NULL auto_increment,
  `subject` varchar(100) default NULL,
  `title` varchar(100) default NULL,
  `message` text,
  `flag` varchar(30) default NULL,
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_mail_mod`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_mail_record`
--

CREATE TABLE `mallbuilder_mail_record` (
  `id` int(11) NOT NULL auto_increment,
  `sendmailname` varchar(20) default NULL,
  `sendtime` datetime default NULL,
  `sendmailrecord` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_mail_record`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_member`
--

CREATE TABLE `mallbuilder_member` (
  `userid` int(8) NOT NULL auto_increment,
  `pid` int(11) default NULL,
  `user` char(20) default NULL,
  `password` char(32) default NULL,
  `rand` varchar(5) default NULL,
  `name` varchar(30) default NULL,
  `sex` tinyint(1) default NULL,
  `position` varchar(50) default NULL,
  `qq` varchar(50) default NULL,
  `msn` varchar(50) default NULL,
  `skype` varchar(50) default NULL,
  `tel` varchar(25) default NULL,
  `mobile` varchar(18) default NULL,
  `email` varchar(50) default NULL,
  `email2` varchar(50) default NULL,
  `provinceid` int(11) default NULL,
  `cityid` int(11) default NULL,
  `areaid` int(11) default NULL,
  `area` varchar(255) default NULL,
  `logo` varchar(120) default '0',
  `ip` char(15) NOT NULL,
  `point` int(10) default NULL,
  `statu` tinyint(1) default NULL,
  `regtime` datetime default NULL,
  `lastLoginTime` int(10) default NULL,
  `invite` varchar(50) default NULL,
  `sellerpoints` int(10) NOT NULL default '0',
  `buyerpoints` int(10) NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  KEY `user` (`user`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- 导出表中的数据 `mallbuilder_member`
--

INSERT INTO `mallbuilder_member` (`userid`, `pid`, `user`, `password`, `rand`, `name`, `sex`, `position`, `qq`, `msn`, `skype`, `tel`, `mobile`, `email`, `email2`, `provinceid`, `cityid`, `areaid`, `area`, `logo`, `ip`, `point`, `statu`, `regtime`, `lastLoginTime`, `invite`, `sellerpoints`, `buyerpoints`) VALUES
(23, NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, '', 0, '', NULL, '', '', NULL, '', 'admin@qq.com', NULL, NULL, NULL, NULL, NULL, '0', '127.0.0.1', NULL, 2, '2013-06-13 18:15:55', 1371431358, NULL, 0, 0),
(10, NULL, 'test', '098f6bcd4621d373cade4e832627b4f6', NULL, '百度', 1, '', '123456', '12345', '12345', '', '', '407295198@qq.com', NULL, 1, 37, 568, '北京市 东城区 东四街道', '0', '127.0.0.1', 0, 2, '2013-06-09 09:49:32', 1371539472, NULL, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_message`
--

CREATE TABLE `mallbuilder_message` (
  `id` int(8) NOT NULL auto_increment,
  `touserid` int(8) default NULL,
  `fromuserid` int(8) default NULL,
  `fromInfo` varchar(250) default '0',
  `msgtype` tinyint(1) default '1',
  `sub` varchar(50) default NULL,
  `con` text,
  `iflook` varchar(10) default NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `contype` tinyint(4) default NULL,
  `tid` varchar(50) default NULL,
  `receive_type` varchar(200) default NULL,
  `reply_by` int(11) default NULL,
  `attachments` varchar(50) default NULL,
  `is_save` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_message`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_nav_menu`
--

CREATE TABLE `mallbuilder_nav_menu` (
  `id` int(11) NOT NULL auto_increment,
  `sort` int(2) NOT NULL,
  `menu_name` varchar(20) NOT NULL,
  `link_addr` varchar(100) default NULL,
  `type` int(1) NOT NULL,
  `statu` int(1) NOT NULL,
  `partent_menu_id` int(20) NOT NULL,
  `selected_flag` varchar(20) default '',
  `selected_flay` varchar(20) default NULL,
  `lang` varchar(5) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_nav_menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_news`
--

CREATE TABLE `mallbuilder_news` (
  `nid` int(11) NOT NULL auto_increment,
  `classid` smallint(6) NOT NULL default '1' COMMENT '类别ID',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `ftitle` varchar(120) NOT NULL COMMENT '副标题',
  `keyboard` varchar(100) NOT NULL COMMENT '关键字',
  `titleurl` varchar(200) NOT NULL default '0' COMMENT '外部链接',
  `isrec` tinyint(1) NOT NULL default '0' COMMENT '是否推荐 0否1是',
  `istop` tinyint(1) NOT NULL default '0' COMMENT '是否头条 0否1是',
  `ispass` tinyint(1) NOT NULL default '0' COMMENT '是否审批 0否1是',
  `firsttitle` tinyint(1) NOT NULL default '0' COMMENT '置顶',
  `onclick` int(11) NOT NULL default '0' COMMENT '点击率',
  `titlefont` varchar(100) NOT NULL COMMENT '标题样式',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `uptime` int(12) NOT NULL COMMENT '新闻发布时间',
  `smalltext` varchar(255) NOT NULL COMMENT '内容简介',
  `writer` varchar(50) NOT NULL COMMENT '作者',
  `source` varchar(100) NOT NULL COMMENT '来源',
  `titlepic` varchar(200) NOT NULL COMMENT '标题图片',
  `ispic` tinyint(1) NOT NULL default '0' COMMENT '有无图片 0否1有',
  `isgid` tinyint(1) NOT NULL default '0' COMMENT '阅读权限 0游客',
  `ispl` tinyint(1) NOT NULL COMMENT '是否关闭评论 1关',
  `userfen` smallint(6) NOT NULL COMMENT '查看扣除点数',
  `newstempid` varchar(40) NOT NULL COMMENT '内容模板',
  `pagenum` int(11) NOT NULL default '0' COMMENT '分页',
  `lastedittime` int(12) NOT NULL,
  `vote` char(255) NOT NULL default '0',
  `special` char(255) NOT NULL default '0',
  `imgs_url` text NOT NULL,
  `videos_url` text NOT NULL,
  `admin` varchar(50) default NULL,
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_news`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_newscat`
--

CREATE TABLE `mallbuilder_newscat` (
  `catid` int(6) NOT NULL auto_increment,
  `cat` char(100) default NULL,
  `nums` int(10) default NULL,
  `pid` int(6) default '0',
  `ishome` int(1) default NULL,
  `template` varchar(50) default NULL,
  `pic` varchar(100) default NULL,
  `openpost` tinyint(1) default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_newscat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_news_data`
--

CREATE TABLE `mallbuilder_news_data` (
  `nid` int(11) NOT NULL,
  `con` mediumtext NOT NULL,
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_news_data`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_page_rec`
--

CREATE TABLE `mallbuilder_page_rec` (
  `id` int(11) NOT NULL auto_increment,
  `totalurl` int(10) default NULL COMMENT '前一天url总数',
  `mostpopularurl` varchar(100) default NULL,
  `pageviews` int(10) default NULL COMMENT '前一天的PV数',
  `totalip` int(10) default '0' COMMENT '前一天ip总数',
  `visitusernum` int(10) default '0' COMMENT '前一天上线的会员数',
  `reguser` int(10) default '0' COMMENT '前一天新注册会员数',
  `pronum` int(10) default '0' COMMENT '前一天发布产品数',
  `newsnum` int(10) default '0' COMMENT '前一天发布资讯数',
  `exhibnum` int(10) default '0',
  `time` datetime default NULL COMMENT '时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_page_rec`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_page_view`
--

CREATE TABLE `mallbuilder_page_view` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(200) default NULL,
  `ip` char(20) default NULL,
  `time` datetime default NULL,
  `username` char(20) default '',
  `fileName` char(30) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`),
  KEY `username` (`username`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_page_view`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_banks`
--

CREATE TABLE `mallbuilder_payment_banks` (
  `id` int(8) NOT NULL auto_increment,
  `pay_uid` int(8) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `bank_addr` varchar(200) default NULL,
  `accounts` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  `add_time` int(11) NOT NULL,
  `censor` varchar(50) default NULL,
  `check_time` int(11) default NULL,
  `testing_cash` decimal(10,2) default NULL,
  `master` varchar(225) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_banks`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_card`
--

CREATE TABLE `mallbuilder_payment_card` (
  `id` int(11) NOT NULL auto_increment,
  `card_num` varchar(30) NOT NULL,
  `total_price` int(11) NOT NULL,
  `password` varchar(30) NOT NULL,
  `statu` tinyint(4) NOT NULL,
  `use_name` varchar(20) default NULL,
  `creat_time` int(10) unsigned NOT NULL,
  `stime` int(10) unsigned default NULL,
  `etime` int(10) unsigned default NULL,
  `pic` varchar(80) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 导出表中的数据 `mallbuilder_payment_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_cashflow`
--

CREATE TABLE `mallbuilder_payment_cashflow` (
  `id` int(10) NOT NULL auto_increment,
  `pay_uid` int(11) default NULL,
  `buyer_email` varchar(50) default NULL COMMENT '买家账号',
  `seller_email` varchar(50) default NULL COMMENT '卖家账号',
  `price` decimal(10,2) default NULL,
  `flow_id` varchar(50) default NULL COMMENT '流水账号',
  `order_id` varchar(15) default NULL COMMENT '外部订单号',
  `note` varchar(255) default NULL,
  `censor` varchar(50) default NULL,
  `time` int(11) unsigned default NULL,
  `statu` tinyint(1) default NULL COMMENT '0取消,1待处理,2已付款,3.发货中,4.成功,5.退货中,6.退货成功',
  `return_url` varchar(200) default NULL,
  `notify_url` varchar(200) default NULL,
  `extra_param` varchar(100) default NULL,
  `type` tinyint(1) unsigned default NULL,
  `display` tinyint(1) unsigned default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_cashflow`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_cashpickup`
--

CREATE TABLE `mallbuilder_payment_cashpickup` (
  `id` int(10) NOT NULL auto_increment,
  `pay_uid` int(8) NOT NULL,
  `cashflowid` varchar(50) default NULL,
  `amount` decimal(10,2) NOT NULL,
  `add_time` int(11) NOT NULL,
  `censor` varchar(50) default NULL,
  `check_time` int(11) default NULL,
  `is_succeed` tinyint(1) default '0',
  `bankflow` varchar(50) default NULL,
  `con` text,
  `bank_id` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_cashpickup`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_type`
--

CREATE TABLE `mallbuilder_payment_type` (
  `payment_id` tinyint(3) NOT NULL auto_increment,
  `payment_type` varchar(20) default NULL,
  `payment_name` varchar(100) default NULL,
  `payment_commission` varchar(8) default '0',
  `payment_desc` text,
  `payment_config` text,
  `active` tinyint(1) default '0',
  `nums` tinyint(3) default '0',
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 导出表中的数据 `mallbuilder_payment_type`
--

INSERT INTO `mallbuilder_payment_type` (`payment_id`, `payment_type`, `payment_name`, `payment_commission`, `payment_desc`, `payment_config`, `active`, `nums`) VALUES
(8, 'alipay', '支付宝', '', '支付宝网站(www.alipay.com) 是国内先进的网上支付平台。需与支付宝公司签约方可使用。', 'a:3:{i:0;a:3:{s:4:"name";s:12:"seller_email";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:1;a:3:{s:4:"name";s:3:"key";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:2;a:3:{s:4:"name";s:7:"partner";s:4:"type";s:4:"text";s:5:"value";s:0:"";}}', 1, 0),
(2, 'chinabank', '网银在线', '', '网银在线与中国工商银行、招商银行、中国建设银行、农业银行、民生银行等数十家金融机构达成协议。全面支持全国19家银行的信用卡及借记卡实现网上支付。网址：http://www.chinabank.com.cn', 'a:2:{i:0;a:3:{s:4:"name";s:17:"chinabank_account";s:4:"type";s:4:"text";s:5:"value";s:2:"11";}i:1;a:3:{s:4:"name";s:13:"chinabank_key";s:4:"type";s:4:"text";s:5:"value";s:3:"111";}}', 1, 0),
(3, 'paypal', 'PayPal', '', 'PayPal 是在线付款解决方案的全球领导者，在全世界有超过七千一百六十万个帐户用户。PayPal 可在 56 个市场以 7 种货币（加元、欧元、英镑、美元、日元、澳元、港元）使用。（网址：http://www.paypal.com）', 'a:1:{i:0;a:3:{s:4:"name";s:14:"paypal_account";s:4:"type";s:4:"text";s:5:"value";s:2:"11";}}', 0, 0),
(4, 'paypalcn', '贝宝', '0', '贝宝是由上海网付易信息技术有限公司与世界领先的网络支付公司—— PayPal 公司通力合作为中国市场度身定做的网络支付服务。（网址：http://www.paypal.com/cn）', 'a:1:{i:0;a:3:{s:4:"name";s:16:"paypalcn_account";s:4:"type";s:4:"text";s:5:"value";s:0:"";}}', 1, 0),
(5, 'tenpay', '财付通', '0', '财付通（www.tenpay.com） - 腾讯旗下在线支付平台，通过国家权威安全认证，支持各大银行网上支付，免支付手续费。', 'a:3:{i:0;a:3:{s:4:"name";s:14:"tenpay_account";s:4:"type";s:4:"text";s:5:"value";s:16:"2088002001004690";}i:1;a:3:{s:4:"name";s:10:"tenpay_key";s:4:"type";s:4:"text";s:5:"value";s:16:"2088002001004690";}i:2;a:3:{s:4:"name";s:16:"tenpay_magic_key";s:4:"type";s:4:"text";s:5:"value";s:16:"2088002001004690";}}', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_user`
--

CREATE TABLE `mallbuilder_payment_user` (
  `pay_uid` int(11) unsigned NOT NULL auto_increment COMMENT '支付用户ID号',
  `userid` int(11) unsigned default NULL COMMENT '网站会员ID',
  `name` varchar(30) default NULL,
  `email` varchar(30) default NULL,
  `login_pass` varchar(32) default NULL,
  `pay_pass` varchar(32) default NULL,
  `tell` varchar(30) default NULL,
  `mobile` varchar(30) default NULL,
  `cash` decimal(10,2) default '0.00',
  `unreachable` decimal(10,2) default '0.00',
  `time` int(10) unsigned default NULL,
  PRIMARY KEY  (`pay_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 导出表中的数据 `mallbuilder_payment_user`
--

INSERT INTO `mallbuilder_payment_user` (`pay_uid`, `userid`, `name`, `email`, `login_pass`, `pay_pass`, `tell`, `mobile`, `cash`, `unreachable`, `time`) VALUES
(6, 10, 'sadsad', 'aasf@asdfa.com', NULL, '098f6bcd4621d373cade4e832627b4f6', 'gfgfgfsdsad', 'd', 9993888.00, 0.00, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points`
--

CREATE TABLE `mallbuilder_points` (
  `id` int(8) NOT NULL auto_increment,
  `points` varchar(200) NOT NULL,
  `img` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 导出表中的数据 `mallbuilder_points`
--

INSERT INTO `mallbuilder_points` (`id`, `points`, `img`) VALUES
(1, '-1|10', 's_red_1.gif'),
(2, '11|40', 's_red_2.gif'),
(3, '41|90', 's_red_3.gif'),
(4, '91|150', 's_red_4.gif'),
(5, '151|250', 's_red_5.gif'),
(6, '251|500', 's_blue_1.gif'),
(7, '501|1000', 's_blue_2.gif'),
(8, '1001|2000', 's_blue_3.gif'),
(9, '2001|5000', 's_blue_4.gif'),
(10, '5001|10000', 's_blue_5.gif'),
(11, '10001|20000', 's_cap_1.gif'),
(12, '20001|50000', 's_cap_2.gif'),
(13, '50001|100000', 's_cap_3.gif'),
(14, '100001|200000', 's_cap_4.gif'),
(15, '200001|500000', 's_cap_5.gif'),
(16, '500001|1000000', 's_crown_1.gif'),
(17, '1000001|2000000', 's_crown_2.gif'),
(18, '2000001|5000000', 's_crown_3.gif'),
(19, '5000001|10000000', 's_crown_4.gif'),
(20, '10000001|10000000000', 's_crown_5.gif');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_products`
--

CREATE TABLE `mallbuilder_products` (
  `id` int(6) NOT NULL auto_increment,
  `userid` int(6) default NULL,
  `user` varchar(30) default NULL,
  `catid` int(6) default NULL,
  `ptype` int(1) default '0',
  `pname` varchar(100) default NULL,
  `keywords` varchar(100) default NULL,
  `brand` varchar(60) default NULL,
  `market_price` float(10,2) default NULL,
  `price` float(10,2) default '0.00',
  `amount` int(6) default '1',
  `sell_amount` int(5) default '0',
  `code` varchar(50) default NULL,
  `pic` varchar(200) default NULL,
  `maintenance` tinyint(1) default '1',
  `invoice` tinyint(1) default '1',
  `credit` int(3) unsigned default NULL,
  `stime_type` tinyint(1) default '1',
  `stime` int(11) default NULL,
  `validTime` tinyint(1) default '0',
  `weight` int(8) unsigned default NULL,
  `cubage` int(8) unsigned default NULL,
  `freight` smallint(6) unsigned default NULL,
  `freight_type` tinyint(1) unsigned default NULL,
  `post_price` float unsigned default NULL,
  `express_price` float unsigned default NULL,
  `ems_price` float unsigned default NULL,
  `province` int(11) default NULL,
  `city` int(11) default NULL,
  `areaid` int(11) default NULL,
  `area` varchar(255) NOT NULL,
  `read_nums` int(6) default '0',
  `rank` int(5) default '0',
  `uptime` datetime default NULL,
  `statu` tinyint(1) NOT NULL default '1' COMMENT '-2，-1，0,1,2库存，违规，没审核，审核，推荐',
  `custom_cat_id` int(10) default NULL,
  `promotion_id` int(11) default '0',
  `point` int(6) default NULL,
  `goodbad` int(6) default NULL,
  `shop_rec` tinyint(1) unsigned default '0' COMMENT '橱窗推荐',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`,`pname`),
  KEY `pname` (`pname`),
  KEY `keywords` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_products`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_cart`
--

CREATE TABLE `mallbuilder_product_cart` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `userid` int(11) unsigned default NULL,
  `pid` int(11) unsigned default NULL,
  `sell_userid` int(11) unsigned default NULL,
  `price` float unsigned default NULL,
  `num` int(5) unsigned default NULL,
  `time` int(11) unsigned default NULL,
  `setmeal` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- 导出表中的数据 `mallbuilder_product_cart`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_cat`
--

CREATE TABLE `mallbuilder_product_cat` (
  `catid` int(9) NOT NULL auto_increment,
  `cat` varchar(50) default NULL,
  `title` text,
  `keyword` text,
  `description` text,
  `nums` int(6) default NULL,
  `isindex` tinyint(1) default '0',
  `char_index` char(1) default NULL,
  `all_char` varchar(50) default NULL,
  `pic` varchar(150) default NULL,
  `brand` text,
  `rec_nums` int(10) default '0',
  `isbuy` tinyint(1) default NULL,
  `ext_table` varchar(30) default NULL,
  `ext_field_cat` int(11) default NULL,
  `is_setmeal` tinyint(1) unsigned default '0',
  `commission` float unsigned default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2147483648 ;

--
-- 导出表中的数据 `mallbuilder_product_cat`
--

INSERT INTO `mallbuilder_product_cat` (`catid`, `cat`, `title`, `keyword`, `description`, `nums`, `isindex`, `char_index`, `all_char`, `pic`, `brand`, `rec_nums`, `isbuy`, `ext_table`, `ext_field_cat`, `is_setmeal`, `commission`) VALUES
(1001, '工业品1', '', '', '', 255, 1, 'g', 'gongyepin', '', '', 151, NULL, NULL, NULL, 0, 0.23),
(1002, '日用百货', '', '', '', 255, 1, 'r', 'riyongbaihuo', '', '', 72, NULL, NULL, NULL, 0, 0),
(1022, '服装服饰', NULL, NULL, NULL, 1, 1, 'f', 'fuzhuangfushi', '', '', 197, 0, '', 0, 1, 0),
(100006, '农业', NULL, NULL, NULL, 0, 1, 'n', 'nongye', '', '', 0, NULL, NULL, NULL, 0, 0),
(100005, '纺织', NULL, NULL, NULL, 0, 1, 'f', 'fangzhi', '', '', 1, NULL, NULL, NULL, 0, 0),
(100004, '化工', NULL, NULL, NULL, 0, 1, 'h', 'huagong', '', '', 1, NULL, NULL, NULL, 0, 0),
(100003, '精细化学品', NULL, NULL, NULL, 0, 1, 'j', 'jingxihuaxuepin', '', '', 14, NULL, NULL, NULL, 0, 0),
(100002, '橡塑', NULL, NULL, NULL, 0, 1, 'x', 'xiangsu', '', '', 4, NULL, NULL, NULL, 0, 0),
(100001, '冶金矿产', NULL, NULL, NULL, 0, 1, 'y', 'yejinkuangchan', '', '', 14, NULL, NULL, NULL, 0, 0),
(100007, '建材', NULL, NULL, NULL, 0, 1, 'j', 'jiancai', '', '', 0, NULL, NULL, NULL, 0, 0),
(100008, '能源', NULL, NULL, NULL, 0, 1, 'n', 'nengyuan', '', '', 1, NULL, NULL, NULL, 0, 0),
(100009, '医药', NULL, NULL, NULL, 0, 1, 'y', 'yiyao', '', '', 13, NULL, NULL, NULL, 0, 0),
(100101, '机械1', NULL, NULL, NULL, 255, 1, 'j', 'jixie', '', '', 17, NULL, NULL, NULL, 0, 0),
(100102, '五金工具1', NULL, NULL, NULL, 255, 1, 'w', 'wujingongju', '', '', 5, NULL, NULL, NULL, 0, 0),
(100103, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 9, NULL, NULL, NULL, 0, 0),
(100104, '电工电气1', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 3, NULL, NULL, NULL, 0, 0),
(100105, '仪器仪表', NULL, NULL, NULL, 255, 1, 'y', 'yiqiyibiao', '', '', 4, NULL, NULL, NULL, 0, 0),
(100106, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', 22, NULL, NULL, NULL, 0, 0),
(100107, '汽车及配件', '', '', '', 255, 1, 'q', 'qichejipeijian', '1309441482.jpg', '', 3, 0, NULL, NULL, 0, 0),
(100108, '交通运输', NULL, NULL, NULL, 255, 1, 'j', 'jiaotongyunshu', '', '', 63, NULL, NULL, NULL, 0, 0),
(100109, '照明工业', NULL, NULL, NULL, 255, 1, 'z', 'zhaominggongye', '', '', 4, NULL, NULL, NULL, 0, 0),
(100201, '美容配饰', NULL, NULL, NULL, 255, 1, 'm', 'meirongpeishi', '', '', 3, NULL, NULL, NULL, 0, 0),
(100202, '家居百货', NULL, NULL, NULL, 255, 1, 'j', 'jiajubaihuo', '', '', 4, NULL, NULL, NULL, 0, 0),
(100203, '礼品', NULL, NULL, NULL, 255, 1, 'l', 'lipin', '', '', 2, NULL, NULL, NULL, 0, 0),
(100204, '玩具', NULL, NULL, NULL, 255, 1, 'w', 'wanju', '', '', 4, NULL, NULL, NULL, 0, 0),
(100205, '办公文教', NULL, NULL, NULL, 255, 1, 'b', 'bangongwenjiao', '', '', 25, NULL, NULL, NULL, 0, 0),
(100206, '食品', NULL, NULL, NULL, 255, 1, 's', 'shipin', '', '', 9, NULL, NULL, NULL, 0, 0),
(100207, '运动休闲', NULL, NULL, NULL, 255, 1, 'y', 'yundongxiuxian', '', '', 1, NULL, NULL, NULL, 0, 0),
(100208, '数码电脑', NULL, NULL, NULL, 255, 1, 's', 'shumadiannao', '', '', 6, NULL, NULL, NULL, 0, 0),
(100209, '家用电器', NULL, NULL, NULL, 255, 1, 'j', 'jiayongdianqi', '', '', 1, NULL, NULL, NULL, 0, 0),
(100210, '建筑建材', NULL, NULL, NULL, 255, 1, 'j', 'jianzhujiancai', '', '', 6, NULL, NULL, NULL, 0, 0),
(102201, '女装', NULL, NULL, NULL, 255, 1, 'n', 'nvzhuang', '', '', 4, 0, '', 0, 0, 0),
(102202, '童装', NULL, NULL, NULL, 255, 1, 't', 'tongzhuang', '', '', 72, NULL, NULL, NULL, 0, 0),
(102203, '男装', NULL, NULL, NULL, 255, 1, 'n', 'nanzhuang', '', '', 44, NULL, NULL, NULL, 0, 0),
(102204, '内衣', NULL, NULL, NULL, 255, 1, 'n', 'neiyi', '', '', 8, NULL, NULL, NULL, 0, 0),
(102205, '配件', NULL, NULL, NULL, 255, 1, 'p', 'peijian', '', '', 2, NULL, NULL, NULL, 0, 0),
(102206, '鞋包', NULL, NULL, NULL, 255, 1, 'x', 'xiebao', '', '1,2,3,4', 8, 0, '', 0, 1, 0),
(102207, '其他', NULL, NULL, NULL, 255, 1, 'q', 'qita', '', '', 2, NULL, NULL, NULL, 0, 0),
(100401, '物流招聘', NULL, NULL, NULL, 0, 1, 'w', 'wuliuzhaopin', '', '', 13, NULL, NULL, NULL, 0, 0),
(100402, '展会广告', NULL, NULL, NULL, 0, 1, 'z', 'zhanhuiguanggao', '', '', -29, NULL, NULL, NULL, 0, 0),
(100403, '印刷服务', NULL, NULL, NULL, 0, 1, 'y', 'yinshuafuwu', '', '', 4, NULL, NULL, NULL, 0, 0),
(100404, '包装服务', NULL, NULL, NULL, 0, 1, 'b', 'baozhuangfuwu', '', '', 1, NULL, NULL, NULL, 0, 0),
(100405, '相关服务', NULL, NULL, NULL, 0, 1, 'x', 'xiangguanfuwu', '', '', 1, NULL, NULL, NULL, 0, 0),
(10000101, '优特钢', NULL, NULL, NULL, 0, 1, 'y', 'youtegang', '', '', 2, NULL, NULL, NULL, 0, 0),
(10000102, '板材、卷材', NULL, NULL, NULL, 0, 1, 'b', 'bancaijuancai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000103, '不锈钢、管材', NULL, NULL, NULL, 0, 1, 'b', 'buxiugangguancai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000104, '建筑钢材、涂镀', '', '', '', 0, 1, 'j', 'jianzhugangcaitudu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000105, '型钢', NULL, NULL, NULL, 0, 1, 'x', 'xinggang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000106, '金属产品', NULL, NULL, NULL, 0, 1, 'j', 'jinshuchanpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000201, '通用塑料', NULL, NULL, NULL, 0, 1, 't', 'tongyongsuliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000202, '工程塑料', NULL, NULL, NULL, 0, 1, 'g', 'gongchengsuliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000203, '再生塑料', NULL, NULL, NULL, 0, 1, 'z', 'zaishengsuliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000204, '合成材料助剂', NULL, NULL, NULL, 0, 1, 'h', 'hechengcailiaozhuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000205, '橡塑加工', NULL, NULL, NULL, 0, 1, 'x', 'xiangsujiagong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000206, '塑料制品', NULL, NULL, NULL, 0, 1, 's', 'suliaozhipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000301, '涂料、油漆', NULL, NULL, NULL, 0, 1, 't', 'tuliaoyouqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000302, '油墨', NULL, NULL, NULL, 0, 1, 'y', 'youmo', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000303, '涂料助剂', NULL, NULL, NULL, 0, 1, 't', 'tuliaozhuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000304, '无机颜料', NULL, NULL, NULL, 0, 1, 'w', 'wujiyanliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000305, '合成胶粘剂', NULL, NULL, NULL, 0, 1, 'h', 'hechengjiaozhanji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000306, '合成材料助剂', NULL, NULL, NULL, 0, 1, 'h', 'hechengcailiaozhuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000401, '烃 类', NULL, NULL, NULL, 0, 1, 't', 'ting lei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000402, '醇 类', NULL, NULL, NULL, 0, 1, 'c', 'chun lei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000403, '羧 酸', NULL, NULL, NULL, 0, 1, '', ' suan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000404, '无机盐', NULL, NULL, NULL, 0, 1, 'w', 'wujiyan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000405, '无机酸', NULL, NULL, NULL, 0, 1, 'w', 'wujisuan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000406, '无机碱', NULL, NULL, NULL, 0, 1, 'w', 'wujijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000501, '纺织原料', NULL, NULL, NULL, 0, 1, 'f', 'fangzhiyuanliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000502, '纱线', NULL, NULL, NULL, 0, 1, 's', 'shaxian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000503, '化学纤维', NULL, NULL, NULL, 0, 1, 'h', 'huaxuexianwei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000504, '坯布', NULL, NULL, NULL, 0, 1, 'p', 'pibu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000505, '面料', NULL, NULL, NULL, 0, 1, 'm', 'mianliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000506, '家纺', NULL, NULL, NULL, 0, 1, 'j', 'jiafang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000601, '粮食', NULL, NULL, NULL, 0, 1, 'l', 'liangshi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000602, '食用油', NULL, NULL, NULL, 0, 1, 's', 'shiyongyou', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000701, '建筑、建材类管材', NULL, NULL, NULL, 0, 1, 'j', 'jianzhujiancaileiguancai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000702, '木质材料', NULL, NULL, NULL, 0, 1, 'm', 'muzhicailiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000703, '管件', NULL, NULL, NULL, 0, 1, 'g', 'guanjian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000704, '石材石料', NULL, NULL, NULL, 0, 1, 's', 'shicaishiliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000705, '建筑玻璃', NULL, NULL, NULL, 0, 1, 'j', 'jianzhuboli', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000706, '防火、防水、防潮材料', NULL, NULL, NULL, 0, 1, 'f', 'fanghuofangshuifangchaocailiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000801, '石油燃料', NULL, NULL, NULL, 0, 1, 's', 'shiyouranliao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000802, '原煤', NULL, NULL, NULL, 0, 1, 'y', 'yuanmei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000803, '能源设备', NULL, NULL, NULL, 0, 1, 'n', 'nengyuanshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000901, '医疗设备', NULL, NULL, NULL, 0, 1, 'y', 'yiliaoshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000902, '原料药设备及机械', NULL, NULL, NULL, 0, 1, 'y', 'yuanliaoyaoshebeijijixie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000903, '中成药', NULL, NULL, NULL, 0, 1, 'z', 'zhongchengyao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000904, '医疗器具', NULL, NULL, NULL, 0, 1, 'y', 'yiliaoqiju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000905, '生物制品', NULL, NULL, NULL, 0, 1, 's', 'shengwuzhipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000906, '原料药', NULL, NULL, NULL, 0, 1, 'y', 'yuanliaoyao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010101, '行业设备1', NULL, NULL, NULL, 255, 1, 'x', 'xingyeshebei', '', '', 9, NULL, NULL, NULL, 0, 0),
(10010102, '通用机械1', NULL, NULL, NULL, 255, 1, 't', 'tongyongjixie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010103, '机械加工1', NULL, NULL, NULL, 255, 1, 'j', 'jixiejiagong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010104, '流体机械1', NULL, NULL, NULL, 255, 1, 'l', 'liutijixie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010105, '通用零部件1', NULL, NULL, NULL, 255, 1, 't', 'tongyonglingbujian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010106, '设备配件及附件1', NULL, NULL, NULL, 255, 1, 's', 'shebeipeijianjifujian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010201, '动力工具1', NULL, NULL, NULL, 255, 1, 'd', 'dongligongju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010202, '切削工具1', NULL, NULL, NULL, 255, 1, 'q', 'qiexiaogongju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010203, '手动工具1', NULL, NULL, NULL, 255, 1, 's', 'shoudonggongju', '', '', 3, NULL, NULL, NULL, 0, 0),
(10010204, '其他工具1', NULL, NULL, NULL, 255, 1, 'q', 'qitagongju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010205, '钳子1', NULL, NULL, NULL, 255, 1, 'q', 'qianzi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010206, '扳手1', NULL, NULL, NULL, 255, 1, 'b', 'banshou', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010207, '通用五金配件1', NULL, NULL, NULL, 255, 1, 't', 'tongyongwujinpeijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010208, '锁具1', NULL, NULL, NULL, 255, 1, 's', 'suoju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010209, '五金附件1', NULL, NULL, NULL, 255, 1, 'w', 'wujinfujian', '', '', 1, NULL, NULL, NULL, 0, 0),
(10010301, '集成电路（IC）1', NULL, NULL, NULL, 255, 1, 'j', 'jichengdianluIC', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010302, '连接器1', NULL, NULL, NULL, 255, 1, 'l', 'lianjieqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010303, '变压器 电感器1', NULL, NULL, NULL, 255, 1, 'b', 'bianyaqi dianganqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010304, '二极管1', NULL, NULL, NULL, 255, 1, 'e', 'erjiguan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010305, '三极管1', NULL, NULL, NULL, 255, 1, 's', 'sanjiguan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010306, '显示器件1', NULL, NULL, NULL, 255, 1, 'x', 'xianshiqijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010307, '电阻器1', NULL, NULL, NULL, 255, 1, 'd', 'dianzuqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010308, '电容器1', NULL, NULL, NULL, 255, 1, 'd', 'dianrongqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010309, '变频器1', NULL, NULL, NULL, 255, 1, 'b', 'bianpinqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010310, 'PCB电路板1', NULL, NULL, NULL, 255, 1, 'P', 'PCBdianluban', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010311, '传感器1', NULL, NULL, NULL, 255, 1, 'c', 'chuanganqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010312, '继电器1', NULL, NULL, NULL, 255, 1, 'j', 'jidianqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010401, '开关1', NULL, NULL, NULL, 255, 1, 'k', 'kaiguan', '', '', 2, NULL, NULL, NULL, 0, 0),
(10010402, '太阳能光伏1', NULL, NULL, NULL, 255, 1, 't', 'taiyangnengguangfu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010403, '电动机 发电机1', NULL, NULL, NULL, 255, 1, 'd', 'diandongji fadianji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010404, '电线电缆1', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010405, '插头 插座1', NULL, NULL, NULL, 255, 1, 'c', 'chatou chazuo', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010406, '电热设备', NULL, NULL, NULL, 255, 1, 'd', 'dianreshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010407, '电池', NULL, NULL, NULL, 255, 1, 'd', 'dianchi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010408, '低压电器', NULL, NULL, NULL, 255, 1, 'd', 'diyadianqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010409, '高压电器', NULL, NULL, NULL, 255, 1, 'g', 'gaoyadianqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010410, '电源', NULL, NULL, NULL, 255, 1, 'd', 'dianyuan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010411, '充电器', NULL, NULL, NULL, 255, 1, 'c', 'chongdianqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010412, '工控系统及装备', NULL, NULL, NULL, 255, 1, 'g', 'gongkongxitongjizhuangbei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010413, '防静电产品', NULL, NULL, NULL, 255, 1, 'f', 'fangjingdianchanpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010414, '配电输电设备', NULL, NULL, NULL, 255, 1, 'p', 'peidianshudianshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010415, '绝缘材料', NULL, NULL, NULL, 255, 1, 'j', 'jueyuancailiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010501, '量仪', NULL, NULL, NULL, 255, 1, 'l', 'liangyi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010502, '室内环保检测仪器', NULL, NULL, NULL, 255, 1, 's', 'shineihuanbaojianceyiqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010503, '衡器', NULL, NULL, NULL, 255, 1, 'h', 'hengqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010504, '温度仪表', NULL, NULL, NULL, 255, 1, 'w', 'wenduyibiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010505, '试验机', NULL, NULL, NULL, 255, 1, 's', 'shiyanji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010506, '分析仪器', NULL, NULL, NULL, 255, 1, 'f', 'fenxiyiqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010507, '传感器', NULL, NULL, NULL, 255, 1, 'c', 'chuanganqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010508, '实验仪器装置', NULL, NULL, NULL, 255, 1, 's', 'shiyanyiqizhuangzhi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010509, '光学仪器', NULL, NULL, NULL, 255, 1, 'g', 'guangxueyiqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010601, '安防监控', NULL, NULL, NULL, 255, 1, 'a', 'anfangjiankong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010602, '防盗报警', NULL, NULL, NULL, 255, 1, 'f', 'fangdaobaojing', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010603, '门禁考勤', NULL, NULL, NULL, 255, 1, 'm', 'menjinkaoqin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010604, '作业防护', NULL, NULL, NULL, 255, 1, 'z', 'zuoyefanghu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010605, '智能交通', NULL, NULL, NULL, 255, 1, 'z', 'zhinengjiaotong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010606, '消防、安检', NULL, NULL, NULL, 255, 1, 'x', 'xiaofanganjian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010607, '防伪识别', NULL, NULL, NULL, 255, 1, 'f', 'fangweishibie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010608, '家居智能', NULL, NULL, NULL, 255, 1, 'j', 'jiajuzhineng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010609, '楼宇对讲设备', NULL, NULL, NULL, 255, 1, 'l', 'louyuduijiangshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010701, '乘用车', NULL, NULL, NULL, 255, 1, 'c', 'chengyongche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010702, '商用车', NULL, NULL, NULL, 255, 1, 's', 'shangyongche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010703, '专用汽车', NULL, NULL, NULL, 255, 1, 'z', 'zhuanyongqiche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010704, '二手汽车', NULL, NULL, NULL, 255, 1, 'e', 'ershouqiche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010705, '汽车影音导航', NULL, NULL, NULL, 255, 1, 'q', 'qicheyingyindaohang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010706, '汽车安全辅助', NULL, NULL, NULL, 255, 1, 'q', 'qicheanquanfuzhu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010707, '汽车内饰用品', NULL, NULL, NULL, 255, 1, 'q', 'qicheneishiyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010708, '汽车外饰用品', NULL, NULL, NULL, 255, 1, 'q', 'qichewaishiyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010709, '汽车小电器', NULL, NULL, NULL, 255, 1, 'q', 'qichexiaodianqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010710, '汽车通讯', NULL, NULL, NULL, 255, 1, 'q', 'qichetongxun', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010711, '美容养护', NULL, NULL, NULL, 255, 1, 'm', 'meirongyanghu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010712, '自驾游装备', NULL, NULL, NULL, 255, 1, 'z', 'zijiayouzhuangbei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010713, '发动系统', NULL, NULL, NULL, 255, 1, 'f', 'fadongxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010714, '车身及附件', NULL, NULL, NULL, 255, 1, 'c', 'cheshenjifujian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010715, '传动系统', NULL, NULL, NULL, 255, 1, 'c', 'chuandongxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010716, '转向系统', NULL, NULL, NULL, 255, 1, 'z', 'zhuanxiangxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010717, '制动系统', NULL, NULL, NULL, 255, 1, 'z', 'zhidongxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010718, '行走系统', NULL, NULL, NULL, 255, 1, 'x', 'xingzouxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010719, '电源、点火系统', NULL, NULL, NULL, 255, 1, 'd', 'dianyuandianhuoxitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010720, '车用仪表', NULL, NULL, NULL, 255, 1, 'c', 'cheyongyibiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010721, '冷却系统', NULL, NULL, NULL, 255, 1, 'l', 'lengquexitong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010722, '汽车改装件', NULL, NULL, NULL, 255, 1, 'q', 'qichegaizhuangjian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010723, '轮胎', NULL, NULL, NULL, 255, 1, 'l', 'luntai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010724, '车灯', NULL, NULL, NULL, 255, 1, 'c', 'chedeng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010725, '摩托车', NULL, NULL, NULL, 255, 1, 'm', 'motuoche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010801, '非机动车配件', NULL, NULL, NULL, 255, 1, 'f', 'feijidongchepeijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010802, '电动车', NULL, NULL, NULL, 255, 1, 'd', 'diandongche', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010803, '二手交通工具', NULL, NULL, NULL, 255, 1, 'e', 'ershoujiaotonggongju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010804, '集装整理设备', NULL, NULL, NULL, 255, 1, 'j', 'jizhuangzhenglishebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010805, '起重装卸设备', NULL, NULL, NULL, 255, 1, 'q', 'qizhongzhuangxieshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010806, '船舶专用配件', NULL, NULL, NULL, 255, 1, 'c', 'chuanbozhuanyongpeijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010901, '专门用途灯具', NULL, NULL, NULL, 255, 1, 'z', 'zhuanmenyongtudengju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010902, '室外照明灯具', NULL, NULL, NULL, 255, 1, 's', 'shiwaizhaomingdengju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010903, '常规照明灯具', NULL, NULL, NULL, 255, 1, 'c', 'changguizhaomingdengju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010904, '气体放电灯', NULL, NULL, NULL, 255, 1, 'q', 'qitifangdiandeng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010905, '指示灯具', NULL, NULL, NULL, 255, 1, 'z', 'zhishidengju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10010906, '白炽灯', NULL, NULL, NULL, 255, 1, 'b', 'baichideng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020101, '发饰、头饰', NULL, NULL, NULL, 255, 1, 'f', 'fashitoushi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020102, '耳饰', NULL, NULL, NULL, 255, 1, 'e', 'ershi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020103, '手饰 衣饰', NULL, NULL, NULL, 255, 1, 's', 'shoushi yishi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020104, '护肤', NULL, NULL, NULL, 255, 1, 'h', 'hufu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020105, '彩妆', NULL, NULL, NULL, 255, 1, 'c', 'caizhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020106, '工具', NULL, NULL, NULL, 255, 1, 'g', 'gongju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020201, '家务清洁整理', NULL, NULL, NULL, 255, 1, 'j', 'jiawuqingjiezhengli', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020202, '家居', NULL, NULL, NULL, 255, 1, 'j', 'jiaju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020203, '厨房用品茶具', NULL, NULL, NULL, 255, 1, 'c', 'chufangyongpinchaju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020204, '保健用品', NULL, NULL, NULL, 255, 1, 'b', 'baojianyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020205, '家纺', NULL, NULL, NULL, 255, 1, 'j', 'jiafang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020206, '母婴用品', NULL, NULL, NULL, 255, 1, 'm', 'muyingyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020301, '节庆装饰', NULL, NULL, NULL, 255, 1, 'j', 'jieqingzhuangshi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020302, '节庆用品', NULL, NULL, NULL, 255, 1, 'j', 'jieqingyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020303, '工艺品', NULL, NULL, NULL, 255, 1, 'g', 'gongyipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020304, '摆挂饰礼品', NULL, NULL, NULL, 255, 1, 'b', 'baiguashilipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020305, '广告促销礼品', NULL, NULL, NULL, 255, 1, 'g', 'guanggaocuxiaolipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020306, '家居日用礼品', NULL, NULL, NULL, 255, 1, 'j', 'jiajuriyonglipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020401, '毛绒玩具', NULL, NULL, NULL, 255, 1, 'm', 'maorongwanju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020402, '益智玩具', NULL, NULL, NULL, 255, 1, 'y', 'yizhiwanju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020403, '创意玩具', NULL, NULL, NULL, 255, 1, 'c', 'chuangyiwanju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020404, '塑胶玩具', NULL, NULL, NULL, 255, 1, 's', 'sujiaowanju', '', '', 3, NULL, NULL, NULL, 0, 0),
(10020405, '模型玩具', NULL, NULL, NULL, 255, 1, 'm', 'moxingwanju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020406, '玩具代理加盟', NULL, NULL, NULL, 255, 1, 'w', 'wanjudailijiameng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020501, '学习文具', NULL, NULL, NULL, 255, 1, 'x', 'xuexiwenju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020502, '簿、本、册', NULL, NULL, NULL, 255, 1, 'b', 'bubence', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020503, '笔类', NULL, NULL, NULL, 255, 1, 'b', 'bilei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020504, '书籍音像', NULL, NULL, NULL, 255, 1, 's', 'shujiyinxiang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020505, '装订涂改', NULL, NULL, NULL, 255, 1, 'z', 'zhuangdingtugai', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020506, '收纳用品', NULL, NULL, NULL, 255, 1, 's', 'shounayongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020601, '休闲食品', NULL, NULL, NULL, 255, 1, 'x', 'xiuxianshipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020602, '酒水', NULL, NULL, NULL, 255, 1, 'j', 'jiushui', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020603, '冲饮品', NULL, NULL, NULL, 255, 1, 'c', 'chongyinpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020604, '粗加工食品', NULL, NULL, NULL, 255, 1, 'c', 'cujiagongshipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020605, '保健食品', NULL, NULL, NULL, 255, 1, 'b', 'baojianshipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020606, '方便食品', NULL, NULL, NULL, 255, 1, 'f', 'fangbianshipin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020701, '户外用品', NULL, NULL, NULL, 255, 1, 'h', 'huwaiyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020702, '球类用品', NULL, NULL, NULL, 255, 1, 'q', 'qiuleiyongpin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020703, '宠物/垂钓', NULL, NULL, NULL, 255, 1, 'c', 'chongwu/chuidiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020704, '健身器材', NULL, NULL, NULL, 255, 1, 'j', 'jianshenqicai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020705, '娱乐/棋牌', NULL, NULL, NULL, 255, 1, 'y', 'yule/qipai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020706, '极限/护具', NULL, NULL, NULL, 255, 1, 'j', 'jixian/huju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020801, '数码产品', NULL, NULL, NULL, 255, 1, 's', 'shumachanpin', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020802, '手机通讯', NULL, NULL, NULL, 255, 1, 's', 'shoujitongxun', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020803, '笔记本电脑及配件', NULL, NULL, NULL, 255, 1, 'b', 'bijibendiannaojipeijian', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020804, '电脑配件', '', '', '', 255, 1, 'd', 'diannaopeijian', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020805, '网络设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluoshebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020806, '办公设备耗材', '', '', '', 255, 1, 'b', 'bangongshebeihaocai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020901, '生活小家电', NULL, NULL, NULL, 255, 1, 's', 'shenghuoxiaojiadian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020902, '厨房家电', NULL, NULL, NULL, 255, 1, 'c', 'chufangjiadian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020903, '大家电', NULL, NULL, NULL, 255, 1, 'd', 'dajiadian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020904, '卫浴家电', NULL, NULL, NULL, 255, 1, 'w', 'weiyujiadian', '', '', 1, NULL, NULL, NULL, 0, 0),
(10020905, '视听系列', NULL, NULL, NULL, 255, 1, 's', 'shitingxilie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10020906, '家电附件', NULL, NULL, NULL, 255, 1, 'j', 'jiadianfujian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021001, '灯具', NULL, NULL, NULL, 255, 1, 'd', 'dengju', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021002, '建筑材料', NULL, NULL, NULL, 255, 1, 'j', 'jianzhucailiao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021003, '厨卫设施', NULL, NULL, NULL, 255, 1, 'c', 'chuweisheshi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021004, '门窗', NULL, NULL, NULL, 255, 1, 'm', 'menchuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021005, '装修器材', NULL, NULL, NULL, 255, 1, 'z', 'zhuangxiuqicai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10021006, '地板瓷砖', NULL, NULL, NULL, 255, 1, 'd', 'dibancizhuan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220101, '热门女装', NULL, NULL, NULL, 255, 1, 'r', 'remennvzhuang', '', '', 1, NULL, NULL, NULL, 0, 0),
(10220102, '女上装', NULL, NULL, NULL, 255, 1, 'n', 'nvshangzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220103, '女裤', NULL, NULL, NULL, 255, 1, 'n', 'nvku', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220104, '职业女装', NULL, NULL, NULL, 255, 1, 'z', 'zhiyenvzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220105, '背心', NULL, NULL, NULL, 255, 1, 'b', 'beixin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220106, '女裙', NULL, NULL, NULL, 255, 1, 'n', 'nvqun', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220201, '童装', NULL, NULL, NULL, 255, 1, 't', 'tongzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220202, '婴儿装', NULL, NULL, NULL, 255, 1, 'y', 'yingerzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220203, '童装品牌', NULL, NULL, NULL, 255, 1, 't', 'tongzhuangpinpai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220204, '童年龄段', NULL, NULL, NULL, 255, 1, 't', 'tongnianlingduan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220205, '孕妇装', NULL, NULL, NULL, 255, 1, 'y', 'yunfuzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220206, '亲子装情侣装', NULL, NULL, NULL, 255, 1, 'q', 'qinzizhuangqinglvzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220301, '休闲男上装', NULL, NULL, NULL, 255, 1, 'x', 'xiuxiannanshangzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220302, '休闲男裤', NULL, NULL, NULL, 255, 1, 'x', 'xiuxiannanku', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220303, '热门男装', NULL, NULL, NULL, 255, 1, 'r', 'remennanzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220304, '热门商务男装', NULL, NULL, NULL, 255, 1, 'r', 'remenshangwunanzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220305, '商务男装', NULL, NULL, NULL, 255, 1, 's', 'shangwunanzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220306, '品牌男装', NULL, NULL, NULL, 255, 1, 'p', 'pinpainanzhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220401, '文胸', NULL, NULL, NULL, 255, 1, 'w', 'wenxiong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220402, '内裤', NULL, NULL, NULL, 255, 1, 'n', 'neiku', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220403, '情趣内衣', NULL, NULL, NULL, 255, 1, 'q', 'qingquneiyi', '', '', 1, NULL, NULL, NULL, 0, 0),
(10220404, '睡衣 浴衣 家居服', NULL, NULL, NULL, 255, 1, 's', 'shuiyi yuyi jiajufu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220405, '保暖塑身', NULL, NULL, NULL, 255, 1, 'b', 'baonuansushen', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220406, '背心 品牌 内衣配件', NULL, NULL, NULL, 255, 1, 'b', 'beixin pinpai neiyipeijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220501, '袜子', NULL, NULL, NULL, 255, 1, 'w', 'wazi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220502, '帽子', NULL, NULL, NULL, 255, 1, 'm', 'maozi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220503, '腰带', NULL, NULL, NULL, 255, 1, 'y', 'yaodai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220504, '丝巾 披肩', NULL, NULL, NULL, 255, 1, 's', 'sijin pijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220505, '围巾', NULL, NULL, NULL, 255, 1, 'w', 'weijin', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220506, '手套 围巾帽子手套套件 领带', NULL, NULL, NULL, 255, 1, 's', 'shoutao weijinmaozishoutaotaojian lingdai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220601, '女鞋', NULL, NULL, NULL, 255, 1, 'n', 'nvxie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220602, '男鞋', NULL, NULL, NULL, 255, 1, 'n', 'nanxie', '', '1,2,3,4', 6, 1, 'mallbuilder_defind_12', 12, 1, 0),
(10220603, '运动鞋', NULL, NULL, NULL, 255, 1, 'y', 'yundongxie', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220604, '女包', NULL, NULL, NULL, 255, 1, 'n', 'nvbao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220605, '男包', NULL, NULL, NULL, 255, 1, 'n', 'nanbao', '', '', 1, NULL, NULL, NULL, 0, 0),
(10220606, '运动包', NULL, NULL, NULL, 255, 1, 'y', 'yundongbao', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220701, '普通运动服', NULL, NULL, NULL, 255, 1, 'p', 'putongyundongfu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220702, '特殊运动服', NULL, NULL, NULL, 255, 1, 't', 'teshuyundongfu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220703, '制服工作服校服表演服', NULL, NULL, NULL, 255, 1, 'z', 'zhifugongzuofuxiaofubiaoyanfu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220704, '加工订制', NULL, NULL, NULL, 255, 1, 'j', 'jiagongdingzhi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220705, '代理加盟', NULL, NULL, NULL, 255, 1, 'd', 'dailijiameng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10220706, '库存服装 服饰', NULL, NULL, NULL, 255, 1, 'k', 'kucunfuzhuang fushi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040101, '国内物流', NULL, NULL, NULL, 0, 1, 'g', 'guoneiwuliu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040102, '国际物流', NULL, NULL, NULL, 0, 1, 'g', 'guojiwuliu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040103, '快递服务', NULL, NULL, NULL, 0, 1, 'k', 'kuaidifuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040104, '招聘职位', NULL, NULL, NULL, 0, 1, 'z', 'zhaopinzhiwei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040105, '求职简历', NULL, NULL, NULL, 0, 1, 'q', 'qiuzhijianli', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040106, '兼职职位', NULL, NULL, NULL, 0, 1, 'j', 'jianzhizhiwei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040201, '展会信息', NULL, NULL, NULL, 0, 1, 'z', 'zhanhuixinxi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040202, '展览服务', NULL, NULL, NULL, 0, 1, 'z', 'zhanlanfuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040203, '展会器材', NULL, NULL, NULL, 0, 1, 'z', 'zhanhuiqicai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040204, '广告服务', NULL, NULL, NULL, 0, 1, 'g', 'guanggaofuwu', '', '', -31, NULL, NULL, NULL, 0, 0),
(10040205, '创意设计', NULL, NULL, NULL, 0, 1, 'c', 'chuangyisheji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040206, '广告器材', NULL, NULL, NULL, 0, 1, 'g', 'guanggaoqicai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040301, '印前处理设备', NULL, NULL, NULL, 0, 1, 'y', 'yinqianchulishebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040302, '印刷设备', NULL, NULL, NULL, 0, 1, 'y', 'yinshuashebei', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040303, '特种印刷', NULL, NULL, NULL, 0, 1, 't', 'tezhongyinshua', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040304, '包装印刷加工', NULL, NULL, NULL, 0, 1, 'b', 'baozhuangyinshuajiagong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040305, '商业印刷加工', NULL, NULL, NULL, 0, 1, 's', 'shangyeyinshuajiagong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040306, '产品印刷加工', NULL, NULL, NULL, 0, 1, 'c', 'chanpinyinshuajiagong', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040401, '玻璃包装容器', NULL, NULL, NULL, 0, 1, 'b', 'bolibaozhuangrongqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040402, '布料包装袋', NULL, NULL, NULL, 0, 1, 'b', 'buliaobaozhuangdai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040403, '纸类包装容器', NULL, NULL, NULL, 0, 1, 'z', 'zhileibaozhuangrongqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040404, '竹木包装容器', NULL, NULL, NULL, 0, 1, 'z', 'zhumubaozhuangrongqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040405, '包装制品配附件', NULL, NULL, NULL, 0, 1, 'b', 'baozhuangzhipinpeifujian', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040406, '胶带', NULL, NULL, NULL, 0, 1, 'j', 'jiaodai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040407, '托盘', NULL, NULL, NULL, 0, 1, 't', 'tuopan', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040408, '绳索、扎带', NULL, NULL, NULL, 0, 1, 's', 'shengsuozhadai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040409, '标签、标牌', NULL, NULL, NULL, 0, 1, 'b', 'biaoqianbiaopai', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040410, '食品包装', NULL, NULL, NULL, 0, 1, 's', 'shipinbaozhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040411, '化妆品包装', NULL, NULL, NULL, 0, 1, 'h', 'huazhuangpinbaozhuang', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040412, '塑料包装容器', NULL, NULL, NULL, 0, 1, 's', 'suliaobaozhuangrongqi', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040501, '翻译服务', NULL, NULL, NULL, 0, 1, 'f', 'fanyifuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040502, '中介服务', NULL, NULL, NULL, 0, 1, 'z', 'zhongjiefuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040503, '商旅服务', NULL, NULL, NULL, 0, 1, 's', 'shanglvfuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040504, '进出口代理', NULL, NULL, NULL, 0, 1, 'j', 'jinchukoudaili', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040505, '管理体系认证', NULL, NULL, NULL, 0, 1, 'g', 'guanlitixirenzheng', '', '', 0, NULL, NULL, NULL, 0, 0),
(10040506, '各行业认证服务', NULL, NULL, NULL, 0, 1, 'g', 'gexingyerenzhengfuwu', '', '', 0, NULL, NULL, NULL, 0, 0),
(1000010101, 'dsfsd', NULL, NULL, NULL, 0, 1, 'd', 'dsfsd', '', '', 0, NULL, NULL, NULL, 0, 0),
(10000107, 'dssd', NULL, NULL, NULL, 0, 1, 'd', 'dssd', '', '', 0, NULL, NULL, NULL, 0, 0),
(1000010701, 'sdfdsfs', NULL, NULL, NULL, 0, 1, 's', 'sdfdsfs', '', '', 0, NULL, NULL, NULL, 0, 0),
(100010, '个', NULL, NULL, NULL, 0, 1, 'g', 'ge', '', '', 0, NULL, NULL, NULL, 0, 0),
(100011, '就', NULL, NULL, NULL, 0, 1, 'j', 'jiu', '', '', 0, NULL, NULL, NULL, 0, 0),
(100012, '就', NULL, NULL, NULL, 0, 1, 'j', 'jiu', '', '', 0, NULL, NULL, NULL, 0, 0),
(100013, '看', NULL, NULL, NULL, 0, 1, 'k', 'kan', '', '', 0, NULL, NULL, NULL, 0, 0),
(100014, '看i', NULL, NULL, NULL, 0, 1, 'k', 'kani', '', '', 0, NULL, NULL, NULL, 0, 0),
(100015, 'iijjk', NULL, NULL, NULL, 0, 1, 'i', 'iijjk', '', '', 0, NULL, NULL, NULL, 0, 0),
(100016, 'kkk', NULL, NULL, NULL, 0, 1, 'k', 'kkk', '', '', 0, NULL, NULL, NULL, 0, 0),
(100017, 'kkkm', NULL, NULL, NULL, 0, 1, 'k', 'kkkm', '', '', 0, NULL, NULL, NULL, 0, 0),
(100018, '加粗', NULL, NULL, NULL, 0, 1, 'j', 'jiacu', '', '', 0, NULL, NULL, NULL, 0, 0),
(100019, '吕', NULL, NULL, NULL, 0, 1, 'l', 'lv', '', '', 0, NULL, NULL, NULL, 0, 0),
(100020, '昌', NULL, NULL, NULL, 0, 1, 'c', 'chang', '', '', 0, NULL, NULL, NULL, 0, 0),
(100021, '发动机', NULL, NULL, NULL, 0, 1, 'f', 'fadongji', '', '', 0, NULL, NULL, NULL, 0, 0),
(100022, '变速箱', NULL, NULL, NULL, 0, 1, 'b', 'biansuxiang', '', '', 0, NULL, NULL, NULL, 0, 0),
(100023, '外观件', NULL, NULL, NULL, 0, 1, 'w', 'waiguanjian', '', '', 0, NULL, NULL, NULL, 0, 0),
(100024, '内饰件', NULL, NULL, NULL, 0, 1, 'n', 'neishijian', '', '', 0, NULL, NULL, NULL, 0, 0),
(100025, '电器', NULL, NULL, NULL, 0, 1, 'd', 'dianqi', '', '', -5, NULL, NULL, NULL, 0, 0),
(100026, '转向机构', NULL, NULL, NULL, 0, 1, 'z', 'zhuanxiangjigou', '', '', 0, NULL, NULL, NULL, 0, 0),
(100027, '制动机构', NULL, NULL, NULL, 0, 1, 'z', 'zhidongjigou', '', '', 0, NULL, NULL, NULL, 0, 0),
(100028, '传动机构', NULL, NULL, NULL, 0, 1, 'c', 'chuandongjigou', '', '', 0, NULL, NULL, NULL, 0, 0),
(1011, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 2, 0, 'mallbuilder_defind_1', NULL, 0, 0),
(1016, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 10, 0, NULL, NULL, 0, 0),
(1018, '钢', NULL, NULL, NULL, 255, 1, 'g', 'gang1', '', '8,10', 15, 1, '', 0, 0, 0),
(1019, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 1, 0, NULL, NULL, 0, 0),
(1020, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 1, 0, NULL, NULL, 0, 0),
(1021, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 3, 0, NULL, NULL, 0, 0),
(100501, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(100502, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(100503, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(100504, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(100505, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(100506, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(100507, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(100508, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(100509, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(100510, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(100511, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(100512, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(100513, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(100514, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(100515, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(100516, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(100517, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101001, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101002, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101003, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101004, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101005, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101006, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101007, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101008, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101009, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101010, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101011, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101012, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101013, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101014, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101015, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101016, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101017, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101101, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101102, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 1, 0, NULL, NULL, 0, 0),
(101103, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101104, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101105, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101106, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101107, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101108, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 1, 0, NULL, NULL, 0, 0),
(101109, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101110, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101111, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101112, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101113, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101114, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101115, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101116, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101117, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(102001, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(102002, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(102003, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(102004, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 1, 0, NULL, NULL, 0, 0),
(102005, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(102006, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(102007, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(102008, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(102009, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(102010, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(102011, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(102012, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(102013, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(102014, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(102015, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(102016, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(102017, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101901, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101902, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101903, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', -1, 0, NULL, NULL, 0, 0),
(101904, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101905, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101906, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 1, 0, NULL, NULL, 0, 0),
(101907, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101908, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 1, 0, NULL, NULL, 0, 0),
(101909, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101910, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101911, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101912, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101913, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101914, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101915, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101916, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101917, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101801, '安防', '', '', '', 255, 1, 'a', 'anfang', '', '', 1, 0, '', 0, 0, 0),
(101802, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 0, 0, 'mallbuilder_product_defind_101', NULL, 0, 0),
(101803, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', 3, 0, NULL, NULL, 0, 0),
(101804, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 3, 0, NULL, NULL, 0, 0),
(101805, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 2, 0, NULL, NULL, 0, 0),
(101806, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101807, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 1, 0, NULL, NULL, 0, 0),
(101808, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101809, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101810, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101811, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101812, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101813, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101814, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 5, 0, NULL, NULL, 0, 0),
(101815, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101816, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101817, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101701, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101702, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101703, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101704, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101705, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101706, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101707, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101708, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101709, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101710, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101711, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101712, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101713, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101714, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101715, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101716, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101717, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101601, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', -1, 0, NULL, NULL, 0, 0),
(101602, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 7, 0, NULL, NULL, 0, 0),
(101603, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', 3, 0, NULL, NULL, 0, 0);
INSERT INTO `mallbuilder_product_cat` (`catid`, `cat`, `title`, `keyword`, `description`, `nums`, `isindex`, `char_index`, `all_char`, `pic`, `brand`, `rec_nums`, `isbuy`, `ext_table`, `ext_field_cat`, `is_setmeal`, `commission`) VALUES
(101604, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101605, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 1, 0, NULL, NULL, 0, 0),
(101606, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101607, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101608, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101609, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 1, 0, NULL, NULL, 0, 0),
(101610, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101611, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101612, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101613, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101614, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101615, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101616, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101617, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101501, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101502, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101503, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101504, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101505, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101506, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101507, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101508, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101509, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101510, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101511, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101512, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101513, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101514, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101515, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101516, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101517, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101401, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101402, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101403, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101404, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101405, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101406, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101407, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101408, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101409, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101410, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101411, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101412, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101413, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101414, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101415, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101416, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101417, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101301, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101302, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101303, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101304, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101305, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101306, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101307, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101308, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101309, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101310, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101311, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101312, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101313, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101314, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101315, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101316, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101317, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(101201, '安防', NULL, NULL, NULL, 0, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(101202, '安防品', NULL, NULL, NULL, 0, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(101203, '安防摄像机', NULL, NULL, NULL, 0, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(101204, '报警器', NULL, NULL, NULL, 0, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101205, '消防', NULL, NULL, NULL, 0, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(101206, '电工电气', NULL, NULL, NULL, 0, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101207, '网络通讯设备', NULL, NULL, NULL, 0, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101208, 'PLC', NULL, NULL, NULL, 0, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(101209, '电线电缆', NULL, NULL, NULL, 0, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(101210, '电子元器件', NULL, NULL, NULL, 0, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(101211, '断路器', NULL, NULL, NULL, 0, 1, 'd', 'duanluqi', '', '', 0, 0, NULL, NULL, 0, 0),
(101212, '卡座', NULL, NULL, NULL, 0, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(101213, '多面板', NULL, NULL, NULL, 0, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(101214, '工业设备', NULL, NULL, NULL, 0, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(101215, '焊机', NULL, NULL, NULL, 0, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(101216, '过滤机', NULL, NULL, NULL, 0, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(101217, '干燥机', NULL, NULL, NULL, 0, 1, 'g', 'ganzaoji', '', '', 0, 0, NULL, NULL, 0, 0),
(102101, '安防', NULL, NULL, NULL, 255, 1, 'a', 'anfang', '', '', 0, 0, NULL, NULL, 0, 0),
(102102, '安防品', NULL, NULL, NULL, 255, 1, 'a', 'anfangpin', '', '', 0, 0, NULL, NULL, 0, 0),
(102103, '安防摄像机', NULL, NULL, NULL, 255, 1, 'a', 'anfangshexiangji', '', '', 0, 0, NULL, NULL, 0, 0),
(102104, '报警器', NULL, NULL, NULL, 255, 1, 'b', 'baojingqi', '', '', 0, 0, NULL, NULL, 0, 0),
(102105, '消防', NULL, NULL, NULL, 255, 1, 'x', 'xiaofang', '', '', 0, 0, NULL, NULL, 0, 0),
(102106, '电工电气', NULL, NULL, NULL, 255, 1, 'd', 'diangongdianqi', '', '', 0, 0, NULL, NULL, 0, 0),
(102107, '网络通讯设备', NULL, NULL, NULL, 255, 1, 'w', 'wangluotongxunshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(102108, 'PLC', NULL, NULL, NULL, 255, 1, 'P', 'PLC', '', '', 0, 0, NULL, NULL, 0, 0),
(102109, '电线电缆', NULL, NULL, NULL, 255, 1, 'd', 'dianxiandianlan', '', '', 0, 0, NULL, NULL, 0, 0),
(102110, '电子元器件', NULL, NULL, NULL, 255, 1, 'd', 'dianziyuanqijian', '', '', 0, 0, NULL, NULL, 0, 0),
(102111, '断路器', NULL, NULL, NULL, 255, 1, 'd', 'duanluqi', '', '8', 0, 1, NULL, NULL, 0, 0),
(102112, '卡座', NULL, NULL, NULL, 255, 1, 'k', 'kazuo', '', '', 0, 0, NULL, NULL, 0, 0),
(102113, '多面板', NULL, NULL, NULL, 255, 1, 'd', 'duomianban', '', '', 0, 0, NULL, NULL, 0, 0),
(102114, '工业设备', NULL, NULL, NULL, 255, 1, 'g', 'gongyeshebei', '', '', 0, 0, NULL, NULL, 0, 0),
(102115, '焊机', NULL, NULL, NULL, 255, 1, 'h', 'hanji', '', '', 0, 0, NULL, NULL, 0, 0),
(102116, '过滤机', NULL, NULL, NULL, 255, 1, 'g', 'guolvji', '', '', 0, 0, NULL, NULL, 0, 0),
(102117, '干燥机', NULL, NULL, NULL, 255, 1, 'g', 'ganzaoji', '', '', 3, 0, NULL, NULL, 0, 0),
(10210101, '摄像头', NULL, NULL, NULL, 255, 1, 's', 'shexiangtou', '', '', 0, NULL, NULL, NULL, 0, 0),
(1021010101, '球机', NULL, NULL, NULL, 0, 1, 'q', 'qiuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(10210102, '球机', NULL, NULL, NULL, 255, 1, 'q', 'qiuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(1021010201, '小球机', NULL, NULL, NULL, 255, 1, 'x', 'xiaoqiuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(1021010202, '大球机', NULL, NULL, NULL, 255, 1, 'd', 'daqiuji', '', '', 0, NULL, NULL, NULL, 0, 0),
(101820, 'dsdasf', NULL, NULL, NULL, 255, 1, 'd', 'dsdasf', '', '8', 0, NULL, '', 0, 0, 0),
(101822, 'vvv', NULL, NULL, NULL, 255, 0, 'v', 'vvv', '', '', 0, NULL, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_comment`
--

CREATE TABLE `mallbuilder_product_comment` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `user` char(20) NOT NULL,
  `fromid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `puid` int(11) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `price` float(30,0) NOT NULL,
  `con` text NOT NULL,
  `uptime` int(12) NOT NULL,
  `goodbad` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_delivery`
--

CREATE TABLE `mallbuilder_product_delivery` (
  `id` int(11) NOT NULL auto_increment,
  `company` varchar(30) character set utf8 NOT NULL COMMENT '快递公司',
  `number` varchar(30) character set utf8 NOT NULL COMMENT '快递单号',
  `time` int(11) NOT NULL COMMENT '发货时间',
  `order_id` varchar(15) character set utf8 NOT NULL,
  `user` varchar(20) character set utf8 NOT NULL,
  `uptime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_delivery`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_detail`
--

CREATE TABLE `mallbuilder_product_detail` (
  `userid` int(11) default NULL,
  `proid` int(11) default NULL,
  `detail` text,
  KEY `proid` (`proid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_product_detail`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_invoice`
--

CREATE TABLE `mallbuilder_product_invoice` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL default '0' COMMENT '发票类型',
  `rise` tinyint(1) NOT NULL default '0' COMMENT '发票抬头',
  `content` tinyint(1) NOT NULL default '0' COMMENT '发票内容',
  `company` varchar(50) default NULL COMMENT '单位名称',
  `number` varchar(30) default NULL COMMENT '纳税人识别号',
  `address` varchar(30) default NULL COMMENT '注册地址',
  `telephone` varchar(30) default NULL COMMENT '注册电话',
  `bank` varchar(30) default NULL COMMENT '开户银行',
  `account` varchar(20) default NULL COMMENT '银行帐户',
  `checked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_invoice`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_order`
--

CREATE TABLE `mallbuilder_product_order` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) unsigned default NULL,
  `order_id` varchar(15) default NULL,
  `buyer_id` int(10) default NULL COMMENT '买家ID',
  `seller_id` int(11) unsigned NOT NULL,
  `buyer_name` varchar(50) default NULL COMMENT '收货人姓名',
  `buyer_addr` varchar(100) default NULL COMMENT '收货人地址',
  `buyer_tel` varchar(30) default NULL COMMENT '收货人电话',
  `buyer_mobile` varchar(15) default NULL,
  `buyer_zip` varchar(6) default NULL COMMENT '收货人邮编',
  `product_price` float default NULL COMMENT '订购价格',
  `logistics_type` varchar(30) default NULL,
  `logistics_price` float default '0',
  `status` int(1) default NULL COMMENT '定单状态',
  `des` varchar(200) default NULL COMMENT '备注',
  `creat_time` int(11) unsigned default NULL COMMENT '下定单时间',
  `uptime` int(11) unsigned default NULL,
  `buyer_comment` tinyint(1) unsigned default '0',
  `seller_comment` tinyint(1) unsigned default '0',
  `invoice` int(11) NOT NULL default '0',
  `logistics` tinyint(11) NOT NULL default '0',
  `deliver_id` int(11) default '0' COMMENT '物流ID',
  `deliver_name` varchar(30) default NULL COMMENT '物流公司',
  `deliver_code` varchar(50) default NULL COMMENT '物流单号',
  `deliver_time` int(10) default '0' COMMENT '配送时间',
  `deliver_addr_id` int(11) default '0' COMMENT '发货地址',
  `time_expand` tinyint(1) NOT NULL default '0' COMMENT '延长时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_order_pro`
--

CREATE TABLE `mallbuilder_product_order_pro` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `order_id` varchar(15) default NULL,
  `buyer_id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned default NULL COMMENT '产品ID',
  `pcatid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '产品名',
  `pic` varchar(100) NOT NULL COMMENT '产品图片',
  `price` float unsigned default NULL,
  `num` int(5) unsigned default NULL,
  `time` int(11) unsigned default NULL,
  `setmeal` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_order_pro`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_report`
--

CREATE TABLE `mallbuilder_product_report` (
  `id` int(11) NOT NULL auto_increment COMMENT '举报id',
  `userid` int(11) NOT NULL COMMENT '举报人id',
  `user` varchar(50) NOT NULL COMMENT '举报人会员名',
  `pid` int(11) NOT NULL COMMENT '被举报的商品id',
  `pname` varchar(100) NOT NULL COMMENT '被举报的商品名称',
  `subject_id` int(11) NOT NULL COMMENT '举报主题id',
  `subject_name` varchar(50) NOT NULL COMMENT '举报主题',
  `content` varchar(100) NOT NULL COMMENT '举报信息',
  `pic` varchar(100) NOT NULL COMMENT '图片1',
  `datetime` int(11) NOT NULL COMMENT '举报时间',
  `shop_id` int(11) NOT NULL COMMENT '被举报商品的店铺id',
  `state` tinyint(1) NOT NULL COMMENT '举报状态(1未处理/2已处理)',
  `handle_type` tinyint(1) NOT NULL COMMENT '举报处理结果(1无效举报/2恶意举报/3有效举报)',
  `handle_message` varchar(100) NOT NULL COMMENT '举报处理信息',
  `handle_datetime` int(11) NOT NULL default '0' COMMENT '举报处理时间',
  `handle_user` varchar(50) NOT NULL default '0' COMMENT '管理员',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='举报表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_report`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_report_subject`
--

CREATE TABLE `mallbuilder_product_report_subject` (
  `id` int(11) NOT NULL auto_increment COMMENT '举报主题id',
  `content` varchar(100) default NULL COMMENT '举报主题内容',
  `type_id` int(11) NOT NULL default '0' COMMENT '举报类型id',
  `type_name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  `desc` varchar(100) default NULL COMMENT '举报类型描述',
  `state` tinyint(1) NOT NULL default '0' COMMENT '举报主题状态(1可用/0失效)',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='举报主题表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_report_subject`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_setmeal`
--

CREATE TABLE `mallbuilder_product_setmeal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `setmeal` varchar(60) NOT NULL default '',
  `price` decimal(10,2) NOT NULL default '0.00',
  `stock` int(11) NOT NULL default '0',
  `sku` varchar(60) NOT NULL default '',
  `property_value_id` text,
  PRIMARY KEY  (`id`),
  KEY `goods_id` (`pid`),
  KEY `price` (`price`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_setmeal`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_property`
--

CREATE TABLE `mallbuilder_property` (
  `id` int(11) NOT NULL auto_increment,
  `displayorder` smallint(6) NOT NULL default '255',
  `name` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_property`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_property_value`
--

CREATE TABLE `mallbuilder_property_value` (
  `id` int(6) NOT NULL auto_increment,
  `field` varchar(100) default NULL COMMENT '字段数据库字段名',
  `field_name` varchar(200) default NULL COMMENT '字段名称',
  `field_desc` varchar(100) default NULL COMMENT '字段描述',
  `is_required` tinyint(1) default '0' COMMENT '是否为必填',
  `default_value` varchar(200) default NULL COMMENT '默认值',
  `field_type` varchar(100) default NULL COMMENT '字段属性(int,char等)',
  `field_length` int(4) default NULL COMMENT '字段长度',
  `is_buy_item` tinyint(1) NOT NULL default '0' COMMENT 'checkbox时 是否为购买项 ',
  `display_type` int(1) default NULL COMMENT '前台显示类型（单行文本框，多行文件框等）',
  `item` varchar(300) default NULL COMMENT '选项列表',
  `is_search` tinyint(1) default '0' COMMENT '是否被搜索',
  `property_id` int(11) default NULL COMMENT '属性ID',
  `module` varchar(30) default NULL,
  `statu` tinyint(1) default '1',
  PRIMARY KEY  (`id`),
  KEY `catid` (`property_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员自定义字段表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_property_value`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_reg_vercode`
--

CREATE TABLE `mallbuilder_reg_vercode` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(50) default NULL,
  `answer` varchar(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_reg_vercode`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_reserve_username`
--

CREATE TABLE `mallbuilder_reserve_username` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_reserve_username`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_return`
--

CREATE TABLE `mallbuilder_return` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '退货记录ID',
  `oid` varchar(15) NOT NULL COMMENT '订单',
  `return_code` varchar(100) NOT NULL COMMENT '退货编号',
  `seller_id` int(10) unsigned NOT NULL COMMENT '卖家ID',
  `seller_name` varchar(20) NOT NULL COMMENT '店铺名称',
  `buyer_id` int(10) unsigned NOT NULL COMMENT '买家ID',
  `buyer_name` varchar(50) NOT NULL COMMENT '买家会员名',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `message` varchar(300) default NULL COMMENT '退货备注',
  `return_addr_id` int(11) NOT NULL,
  `return_addr_name` varchar(30) NOT NULL,
  `return_addr` varchar(150) NOT NULL,
  `return_post` int(6) NOT NULL,
  `return_tel` varchar(20) default NULL,
  `return_mobile` varchar(20) default NULL,
  `statu` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_return`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_return_goods`
--

CREATE TABLE `mallbuilder_return_goods` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '退货商品记录ID',
  `rid` int(10) unsigned NOT NULL COMMENT '退货记录ID',
  `oid` varchar(15) NOT NULL COMMENT '订单ID',
  `pid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `pname` varchar(100) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `returnnum` int(10) unsigned NOT NULL COMMENT '退货数量',
  `pic` varchar(100) default NULL COMMENT '商品图片',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货商品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_return_goods`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_search_word`
--

CREATE TABLE `mallbuilder_search_word` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(80) default NULL,
  `char_index` varchar(80) default NULL,
  `nums` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_search_word`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shipping_address`
--

CREATE TABLE `mallbuilder_shipping_address` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `name` varchar(30) default NULL,
  `provinceid` int(11) default NULL,
  `cityid` int(11) default NULL,
  `areaid` int(11) default NULL,
  `area` varchar(255) character set ucs2 default NULL,
  `addr` varchar(150) default NULL,
  `post` varchar(6) default NULL,
  `tel` varchar(20) default NULL,
  `mobile` varchar(15) default NULL,
  `company` varchar(50) default NULL,
  `con` varchar(200) default NULL,
  `default_receipt` tinyint(1) unsigned default NULL,
  `default_delivery` tinyint(1) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shipping_address`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop`
--

CREATE TABLE `mallbuilder_shop` (
  `userid` int(8) NOT NULL default '0',
  `user` varchar(30) default NULL,
  `catid` char(10) default NULL,
  `company` char(60) default NULL,
  `tel` varchar(30) default NULL,
  `addr` varchar(200) default NULL,
  `provinceid` int(11) default NULL,
  `cityid` int(11) default NULL,
  `areaid` int(11) default NULL,
  `area` varchar(255) default NULL,
  `main_pro` varchar(100) default NULL,
  `logo` varchar(100) default NULL,
  `template` varchar(20) default NULL,
  `stime` int(11) unsigned default NULL,
  `etime` int(11) unsigned default NULL,
  `statu` tinyint(1) default NULL,
  `rank` float default '0',
  `view_times` int(5) unsigned default '0',
  `uptime` int(11) unsigned default NULL,
  `create_time` int(10) unsigned NOT NULL COMMENT '创店时间',
  `shop_statu` tinyint(1) NOT NULL default '0',
  `credit` int(3) unsigned default NULL,
  `shop_collect` int(10) NOT NULL default '0' COMMENT '店铺收藏数量',
  `earnest` float(10,2) unsigned default '0.00',
  `grade` tinyint(1) unsigned default '0' COMMENT '店铺等级',
  `shop_auth` tinyint(1) default '0' COMMENT '店铺认证',
  `shopkeeper_auth` tinyint(1) default '0' COMMENT '店主认证',
  `shop_auth_pic` varchar(100) default NULL,
  `shopkeeper_auth_pic` varchar(100) default NULL,
  KEY `company` (`company`,`cityid`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺表';

--
-- 导出表中的数据 `mallbuilder_shop`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_cat`
--

CREATE TABLE `mallbuilder_shop_cat` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `parent_id` int(9) NOT NULL default '0',
  `displayorder` smallint(6) NOT NULL default '255',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_domin`
--

CREATE TABLE `mallbuilder_shop_domin` (
  `id` int(11) NOT NULL auto_increment,
  `domin` varchar(50) NOT NULL,
  `shop_id` int(8) NOT NULL,
  `shop_name` varchar(60) NOT NULL,
  `member_name` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `domin` (`domin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_domin`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_earnest`
--

CREATE TABLE `mallbuilder_shop_earnest` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `shop_id` int(11) default NULL,
  `money` float default NULL,
  `content` text,
  `admin` varchar(30) default NULL,
  `create_time` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_earnest`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_grade`
--

CREATE TABLE `mallbuilder_shop_grade` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '组名',
  `fee` float NOT NULL default '0' COMMENT '收费标准',
  `desc` text NOT NULL COMMENT '描述',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL default '1' COMMENT '状态 0,1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 导出表中的数据 `mallbuilder_shop_grade`
--

INSERT INTO `mallbuilder_shop_grade` (`id`, `name`, `fee`, `desc`, `create_time`, `status`) VALUES
(1, '系统默认', 10, '系统默认', 1365485867, 1),
(2, '铜牌店铺', 20, '铜牌店铺', 1365485879, 1),
(3, '金牌店铺', 30, '金牌店铺', 1365485898, 0),
(4, '白金店铺', 40, '白金店铺', 1365485914, 1);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_link`
--

CREATE TABLE `mallbuilder_shop_link` (
  `id` int(11) NOT NULL auto_increment,
  `shop_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` varchar(100) NOT NULL,
  `desc` varchar(100) default NULL,
  `displayorder` smallint(6) default '0',
  `status` tinyint(1) NOT NULL default '0' COMMENT '1已审核',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_link`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_navigation`
--

CREATE TABLE `mallbuilder_shop_navigation` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '导航ID',
  `title` varchar(50) NOT NULL COMMENT '导航名称',
  `shop_id` int(10) unsigned NOT NULL default '0' COMMENT '卖家店铺ID',
  `content` text COMMENT '导航内容',
  `sort` tinyint(3) unsigned NOT NULL default '0' COMMENT '导航排序',
  `if_show` tinyint(1) NOT NULL default '0' COMMENT '导航是否显示',
  `add_time` int(10) NOT NULL COMMENT '导航',
  `url` varchar(255) default NULL COMMENT '店铺导航的外链URL',
  `new_open` tinyint(1) unsigned NOT NULL default '0' COMMENT '店铺导航外链是否在新窗口打开：0不开新窗口1开新窗口，默认是0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='卖家店铺导航信息表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_navigation`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_setting`
--

CREATE TABLE `mallbuilder_shop_setting` (
  `shop_id` int(10) NOT NULL,
  `shop_logo` varchar(255) default NULL COMMENT '店铺logo',
  `shop_banner` varchar(255) default NULL COMMENT '店铺横幅',
  `shop_title` varchar(255) default NULL COMMENT 'seo标题',
  `shop_keywords` varchar(255) default NULL COMMENT 'seo关键字',
  `shop_description` varchar(255) default NULL COMMENT 'seo描述',
  `shop_slide` text COMMENT '店铺幻灯片',
  `shop_slideurl` text COMMENT '店铺幻灯片url',
  `common_cat` varchar(255) default NULL COMMENT '常用类别',
  KEY `userid` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_shop_setting`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_template`
--

CREATE TABLE `mallbuilder_shop_template` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `style` varchar(50) NOT NULL COMMENT '风格',
  `temp_file` varchar(60) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `displayorder` smallint(6) NOT NULL default '255',
  `status` tinyint(1) NOT NULL default '0' COMMENT '0、1,停用、启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_template`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_site_spread`
--

CREATE TABLE `mallbuilder_site_spread` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) default NULL,
  `fromip` varchar(50) default NULL,
  `access_num` int(10) default '0',
  `ctime` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_site_spread`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns`
--

CREATE TABLE `mallbuilder_sns` (
  `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
  `original_id` int(11) NOT NULL default '0' COMMENT '原动态ID 默认为0',
  `original_member_id` int(11) NOT NULL default '0' COMMENT '原帖会员编号',
  `original_status` tinyint(1) NOT NULL default '0' COMMENT '原帖的删除状态 0为正常 1为删除',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `member_name` varchar(100) NOT NULL COMMENT '会员名称',
  `member_img` varchar(100) default NULL COMMENT '会员头像',
  `title` varchar(500) default NULL COMMENT '动态标题',
  `content` text NOT NULL COMMENT '动态内容',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态  0正常 1为禁止显示 默认为0',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  `comment_count` int(11) NOT NULL default '0' COMMENT '评论数',
  `copy_count` int(11) NOT NULL default '0' COMMENT '转发数',
  `original_comment_count` int(11) NOT NULL default '0' COMMENT '原帖评论次数',
  `original_copy_count` int(11) NOT NULL default '0' COMMENT '原帖转帖次数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='动态信息表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_friend`
--

CREATE TABLE `mallbuilder_sns_friend` (
  `id` int(11) NOT NULL auto_increment COMMENT 'id值',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `uname` varchar(100) default NULL COMMENT '会员名称',
  `uimg` varchar(100) default NULL COMMENT '会员头像',
  `fuid` int(11) NOT NULL COMMENT '朋友id',
  `funame` varchar(100) NOT NULL COMMENT '好友会员名称',
  `fuimg` varchar(100) default NULL COMMENT '朋友头像',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `state` tinyint(1) NOT NULL default '1' COMMENT '关注状态 1为单方关注 2为双方关注',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_friend`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareproduct`
--

CREATE TABLE `mallbuilder_sns_shareproduct` (
  `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
  `pid` int(11) NOT NULL COMMENT '商品ID',
  `uid` int(11) NOT NULL COMMENT '所属会员ID',
  `uname` varchar(100) NOT NULL COMMENT '会员名称',
  `content` varchar(500) default NULL COMMENT '描述内容',
  `addtime` int(11) NOT NULL COMMENT '分享操作时间',
  `likeaddtime` int(11) NOT NULL default '0' COMMENT '喜欢操作时间',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  `commentcount` int(11) NOT NULL default '0' COMMENT '评论数',
  `isshare` tinyint(1) NOT NULL default '0' COMMENT '是否分享 0为未分享 1为分享',
  `islike` tinyint(1) NOT NULL default '0' COMMENT '是否喜欢 0为未喜欢 1为喜欢',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享商品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_shareproduct`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareproduct_info`
--

CREATE TABLE `mallbuilder_sns_shareproduct_info` (
  `pid` int(11) NOT NULL COMMENT '商品ID',
  `pname` varchar(100) NOT NULL COMMENT '商品名称',
  `image` varchar(100) default NULL COMMENT '商品图片',
  `price` decimal(10,2) NOT NULL default '0.00' COMMENT '商品价格',
  `shopid` int(11) NOT NULL COMMENT '店铺ID',
  `uname` varchar(100) NOT NULL COMMENT '会员名称',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `likenum` int(11) NOT NULL default '0' COMMENT '喜欢数',
  `likemember` text COMMENT '喜欢过的会员ID，用逗号分隔',
  `collectnum` int(11) NOT NULL default '0' COMMENT '收藏数',
  UNIQUE KEY `snsgoods_goodsid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享商品信息表';

--
-- 导出表中的数据 `mallbuilder_sns_shareproduct_info`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareshop`
--

CREATE TABLE `mallbuilder_sns_shareshop` (
  `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
  `shopid` int(11) NOT NULL COMMENT '店铺编号',
  `shopname` varchar(100) NOT NULL COMMENT '店铺名称',
  `uid` int(11) NOT NULL COMMENT '所属会员ID',
  `uname` varchar(100) NOT NULL COMMENT '所属会员名称',
  `content` varchar(500) default NULL COMMENT '描述内容',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享店铺表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_shareshop`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_stop_ip`
--

CREATE TABLE `mallbuilder_stop_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(25) NOT NULL,
  `reason` varchar(50) default '',
  `optime` int(12) unsigned default NULL,
  `stoptime` int(12) unsigned default NULL,
  `autorelease` int(1) default NULL,
  `statu` tinyint(1) NOT NULL default '1',
  `type` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_stop_ip`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_subscribe`
--

CREATE TABLE `mallbuilder_subscribe` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(10) default NULL,
  `email` varchar(60) default NULL,
  `keywords` varchar(50) default NULL,
  `validity` int(3) default NULL COMMENT '有效期,分别30，60，180，360天',
  `frequency` int(2) default NULL COMMENT '接收频率,分别为每1，7，14，30天',
  `lastreceivetime` int(11) default NULL COMMENT '最后接收时间',
  `uptime` int(11) default NULL COMMENT '订阅时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_subscribe`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sub_domain`
--

CREATE TABLE `mallbuilder_sub_domain` (
  `id` int(4) NOT NULL auto_increment,
  `dtype` int(1) NOT NULL,
  `domain` varchar(20) NOT NULL,
  `con` varchar(30) NOT NULL,
  `con2` varchar(20) default NULL,
  `con3` varchar(20) default NULL,
  `des` text,
  `isopen` int(1) default '1',
  `logo` varchar(100) default NULL,
  `web_title` varchar(100) default NULL,
  `web_keyword` varchar(100) default NULL,
  `web_des` varchar(100) default NULL,
  `copyright` text,
  `template` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sub_domain`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tags`
--

CREATE TABLE `mallbuilder_tags` (
  `tagname` varchar(100) NOT NULL,
  `closed` tinyint(1) NOT NULL default '0',
  `total` mediumint(20) unsigned NOT NULL,
  `statu` tinyint(4) NOT NULL COMMENT '0/1  1表示已导入',
  PRIMARY KEY  (`tagname`),
  KEY `total` (`total`),
  KEY `closed` (`closed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_tags`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_talk`
--

CREATE TABLE `mallbuilder_talk` (
  `id` int(11) NOT NULL auto_increment COMMENT '对话id',
  `oid` varchar(15) NOT NULL COMMENT '投诉id',
  `uid` int(11) NOT NULL COMMENT '发言人id',
  `uname` varchar(50) NOT NULL COMMENT '发言人名称',
  `utype` varchar(10) NOT NULL COMMENT '发言人类型(1-买家/2-卖家)',
  `content` varchar(255) NOT NULL COMMENT '发言内容',
  `add_time` int(11) NOT NULL COMMENT '对话发表时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='对话表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_talk`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg`
--

CREATE TABLE `mallbuilder_tg` (
  `id` int(8) NOT NULL auto_increment,
  `catid` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `pic` varchar(255) NOT NULL,
  `market_price` float(10,2) NOT NULL default '0.00',
  `price` float(10,2) unsigned NOT NULL default '0.00',
  `hits` int(6) NOT NULL default '0',
  `sell_amount` int(6) NOT NULL default '0',
  `limit_quantity` int(6) NOT NULL default '0',
  `virtual_quantity` int(6) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL,
  `stock` int(10) NOT NULL default '1',
  `provinceid` int(11) NOT NULL,
  `cityid` int(11) NOT NULL,
  `areaid` int(11) NOT NULL,
  `area` varchar(255) NOT NULL,
  `displayorder` smallint(6) NOT NULL default '255',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg_cat`
--

CREATE TABLE `mallbuilder_tg_cat` (
  `id` int(6) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0' COMMENT '父类id',
  `catname` varchar(30) NOT NULL COMMENT '类别名称',
  `displayorder` smallint(8) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购产品分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg_order`
--

CREATE TABLE `mallbuilder_tg_order` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` varchar(15) NOT NULL COMMENT '订单号',
  `member_id` int(11) default NULL,
  `member_name` varchar(50) NOT NULL,
  `tg_id` int(11) NOT NULL COMMENT '订购产品id',
  `tg_name` varchar(80) NOT NULL COMMENT '订购产品名',
  `tg_pic` varchar(80) default NULL COMMENT '订购产品图片',
  `contact` varchar(30) default NULL,
  `address` varchar(200) default NULL,
  `tel` varchar(20) default NULL,
  `remark` varchar(200) default NULL,
  `admin_remark` varchar(200) default NULL COMMENT '管理员备注',
  `price` decimal(10,2) default '0.00',
  `quantity` varchar(10) NOT NULL,
  `create_time` int(10) default NULL,
  `payment_time` int(10) default NULL,
  `shipping_time` int(10) default NULL,
  `finished_time` int(10) default NULL,
  `status` tinyint(2) default '20',
  `shipping_name` varchar(50) default NULL COMMENT '发货人',
  `shipping_address` varchar(255) default NULL COMMENT '发货地址',
  `shipping_tel` varchar(20) default NULL COMMENT '联系电话',
  `shipping_company` varchar(50) default NULL COMMENT '物流名称',
  `shipping_code` varchar(50) default NULL COMMENT '物流单号',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购订单表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_comment`
--

CREATE TABLE `mallbuilder_user_comment` (
  `id` int(8) NOT NULL auto_increment,
  `userid` int(8) NOT NULL,
  `user` char(20) NOT NULL,
  `byid` int(8) NOT NULL,
  `item1` int(8) NOT NULL,
  `item2` int(8) NOT NULL,
  `item3` int(8) NOT NULL,
  `item4` int(8) NOT NULL,
  `uptime` int(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_connected`
--

CREATE TABLE `mallbuilder_user_connected` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned default NULL,
  `nickname` varchar(50) default NULL,
  `figureurl` varchar(100) default NULL,
  `gender` varchar(10) default NULL,
  `vip` tinyint(1) unsigned default '0',
  `level` tinyint(1) unsigned default '0',
  `type` tinyint(1) default '1',
  `access_token` varchar(80) default NULL,
  `client_id` varchar(80) default NULL,
  `openid` varchar(80) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_connected`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_group`
--

CREATE TABLE `mallbuilder_user_group` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '会员组名',
  `des` text COMMENT '会员组描述',
  `con` text,
  `logo` varchar(100) default NULL COMMENT '会员组ＬＯＧＯ',
  `minilogo` varchar(200) default NULL,
  `statu` tinyint(4) default '1' COMMENT '会员组状态 0,1',
  `creat_time` date default NULL COMMENT '创建时间',
  `groupfee` float default '0',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_read_rec`
--

CREATE TABLE `mallbuilder_user_read_rec` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `tid` int(11) default NULL,
  `type` int(1) default NULL,
  `time` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`,`tid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_read_rec`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_vote`
--

CREATE TABLE `mallbuilder_vote` (
  `id` int(11) NOT NULL auto_increment,
  `newsid` smallint(6) NOT NULL default '0',
  `title` varchar(120) NOT NULL,
  `votetext` text NOT NULL,
  `votetype` tinyint(1) NOT NULL default '0',
  `num` int(6) NOT NULL,
  `limitip` tinyint(1) NOT NULL default '0',
  `time` date NOT NULL default '0000-00-00',
  `tempid` smallint(6) NOT NULL default '0',
  `type` tinyint(4) default NULL,
  `uptime` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_vote`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_con`
--

CREATE TABLE `mallbuilder_web_con` (
  `con_id` int(5) NOT NULL auto_increment,
  `con_desc` mediumtext,
  `con_province` varchar(20) default NULL,
  `con_city` varchar(20) default NULL,
  `con_no` int(2) default '0',
  `con_statu` int(1) default '0',
  `con_title` varchar(30) default NULL,
  `con_linkaddr` varchar(60) default NULL,
  `con_group` tinyint(3) NOT NULL,
  `template` varchar(50) default NULL,
  `title` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `description` varchar(200) default NULL,
  `msg_online` tinyint(1) NOT NULL default '0',
  `lang` varchar(5) default NULL,
  PRIMARY KEY  (`con_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- 导出表中的数据 `mallbuilder_web_con`
--

INSERT INTO `mallbuilder_web_con` (`con_id`, `con_desc`, `con_province`, `con_city`, `con_no`, `con_statu`, `con_title`, `con_linkaddr`, `con_group`, `template`, `title`, `keywords`, `description`, `msg_online`, `lang`) VALUES
(1, '<p>\r\n	关于我们\r\n</p>', '', '', 0, 1, '关于我们', 'aboutus.php?type=1', 5, '', '', '', '', 1, 'cn'),
(2, '<p>帮助</p>', '', '', 0, 1, '帮助手册', 'aboutus.php?type=2', 5, NULL, NULL, NULL, NULL, 0, 'cn'),
(3, '<p>关于我们</p>', '上海', '', 0, 0, '关于我们', '', 0, NULL, NULL, NULL, NULL, 0, 'cn'),
(4, '<p>人才</p>', '', '', 0, 1, '人才招聘', 'aboutus.php?type=4', 5, '', '', '', '', 1, 'cn'),
(5, '<p>联系我们</p>', '', '', 0, 1, '联系我们', 'aboutus.php?type=5', 5, NULL, NULL, NULL, NULL, 0, 'cn'),
(6, '<p>广告联系</p>', '', '', 0, 1, '广告联系', 'aboutus.php?type=6', 5, NULL, NULL, NULL, NULL, 0, 'cn'),
(17, '<p>aaasdfss</p>', '', '', 0, 1, '交换链接', 'change_link.php', 6, '', NULL, NULL, NULL, 0, 'cn'),
(20, NULL, '', '', 0, 1, '在线留言', 'aboutus.php?msg=1', 6, NULL, NULL, NULL, NULL, 0, 'cn'),
(9, '<p>付款方式</p>', '', '', 0, 1, '付款方式', 'aboutus.php?type=9', 6, NULL, NULL, NULL, NULL, 0, 'cn'),
(18, NULL, '', '', 0, 1, '会员服务', 'member_services.php', 6, NULL, NULL, NULL, NULL, 0, 'cn'),
(22, NULL, '', '', 0, 1, 'About Us', 'http://localhost/b2b/aboutus.php?type=22', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(23, NULL, '', '', 0, 1, 'Help', 'http://localhost/b2b/aboutus.php?type=23', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(24, NULL, '', '', 0, 1, 'Payment', 'http://localhost/b2b/aboutus.php?type=24', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(25, NULL, '', '', 0, 1, 'Employment', 'http://localhost/b2b/aboutus.php?type=25', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(26, NULL, '', '', 0, 1, 'Link Exchange', 'change_link.php', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(27, NULL, '', '', 0, 1, 'Advertising ', 'http://localhost/b2b/aboutus.php?type=27', 0, NULL, NULL, NULL, NULL, 0, 'en'),
(28, NULL, '', '', 0, 1, 'Contact Us', 'http://localhost/b2b/aboutus.php?type=28', 0, NULL, NULL, NULL, NULL, 0, 'en');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_config`
--

CREATE TABLE `mallbuilder_web_config` (
  `id` int(5) unsigned NOT NULL auto_increment COMMENT '主键ＩＤ',
  `index` varchar(30) NOT NULL COMMENT '数组下标',
  `value` text NOT NULL COMMENT '数组值',
  `statu` tinyint(1) NOT NULL default '1' COMMENT '状态值，1可能，0不可用',
  `type` varchar(50) default NULL,
  `des` text,
  PRIMARY KEY  (`id`),
  KEY `index` (`index`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站配置表' AUTO_INCREMENT=95 ;

--
-- 导出表中的数据 `mallbuilder_web_config`
--

INSERT INTO `mallbuilder_web_config` (`id`, `index`, `value`, `statu`, `type`, `des`) VALUES
(1, 'opensuburl', '0', 1, 'seo', NULL),
(2, 'rewrite', '0', 1, 'seo', NULL),
(3, 'title', '111', 1, 'seo', NULL),
(4, 'keyword', '111', 1, 'seo', NULL),
(5, 'description', '111', 1, 'seo', NULL),
(6, 'title', 'aa', 1, 'module_product', NULL),
(7, 'keyword', 'aa', 1, 'module_product', NULL),
(8, 'description', 'aa', 1, 'module_product', NULL),
(9, 'title2', 'aa', 1, 'module_product', NULL),
(10, 'keyword2', 'aa', 1, 'module_product', NULL),
(11, 'description2', 'aa', 1, 'module_product', NULL),
(12, 'title3', 'aa', 1, 'module_product', NULL),
(13, 'keyword3', 'aa', 1, 'module_product', NULL),
(14, 'description3', 'aa', 1, 'module_product', NULL),
(15, 'validTime', '7天|15天|1个月|3个月|6个月|1年|永久', 1, 'module_product', NULL),
(16, 'ptype', '全新|二手|闲置|其它', 1, 'module_product', NULL),
(17, 'company', 'Mallbuilder', 1, 'main', NULL),
(18, 'weburl', 'http://localhost/mallbuilder', 1, 'main', NULL),
(19, 'baseurl', '', 1, 'main', NULL),
(20, 'logo', '', 1, 'main', NULL),
(21, 'owntel', '021-64262959', 1, 'main', NULL),
(22, 'email', '250314853@qq.com', 1, 'main', NULL),
(23, 'regname', 'register.php', 1, 'main', NULL),
(24, 'cacheTime', '0', 1, 'main', NULL),
(25, 'money', '￥', 1, 'main', NULL),
(26, 'date_format', '%Y-%m-%d', 1, 'main', NULL),
(27, 'language', 'cn', 1, 'main', NULL),
(28, 'temp', 'default', 1, 'main', NULL),
(29, 'domaincity', '0', 1, 'main', NULL),
(30, 'enable_gzip', '0', 1, 'main', NULL),
(31, 'enable_tranl', '0', 1, 'main', NULL),
(32, 'openstatistics', '1', 1, 'main', NULL),
(33, 'copyright', 'MallBuilder版权所有,正版购买地址http://www.mall-builder.com', 1, 'main', NULL),
(34, 'closetype', '0', 1, 'main', NULL),
(35, 'closecon', '', 1, 'main', NULL),
(36, 'qanggou', '1', 1, 'home', NULL),
(37, 'hot_sell', '1,2,3', 1, 'home', NULL),
(38, 'hot_commen', '1,2,3', 1, 'home', NULL),
(39, 'new_pro', '1', 1, 'home', NULL),
(40, 'index_catid', '1', 1, 'home', NULL),
(41, 'index_newsid', '1', 1, 'home', NULL),
(42, 'list_catid', '1', 1, 'home', NULL),
(43, 'title', 'adf', 1, 'module_company', NULL),
(44, 'keyword', '', 1, 'module_company', NULL),
(45, 'description', '', 1, 'module_company', NULL),
(46, 'title2', '[catname]企业列表', 1, 'module_company', NULL),
(47, 'keyword2', '[catname]企业列表', 1, 'module_company', NULL),
(48, 'description2', '[catname]企业列表', 1, 'module_company', NULL),
(49, 'credit', '7天包换|正品保证|如实描述', 1, 'module_company', NULL),
(50, 'logo_width', '100', 1, 'module_company', NULL),
(51, 'logo_height', '100', 1, 'module_company', NULL),
(52, 'display_cat', '0', 1, 'module_company', NULL),
(53, 'certification', '0', 1, 'module_company', NULL),
(54, 'open_shop_guide', '1', 1, 'module_company', NULL),
(55, 'list_row', '20', 1, 'module_company', NULL),
(56, 'openregemail', '0', 1, 'reg', NULL),
(57, 'user_reg_verf', '0', 1, 'reg', NULL),
(58, 'invite', '1', 1, 'reg', NULL),
(59, 'user_reg', '2', 1, 'reg', NULL),
(60, 'openbbs', '0', 1, 'reg', NULL),
(61, 'inhibit_ip', '0', 1, 'reg', NULL),
(62, 'exception_ip', '127.0.0.1', 1, 'reg', NULL),
(63, 'association', '这里是注册协义，可以在后台注册设置中修改。', 1, 'reg', NULL),
(64, 'closetype', '0', 1, 'reg', NULL),
(65, 'closecon', '', 1, 'reg', NULL),
(66, 'view_0', '1', 1, 'module_tg', NULL),
(67, 'view_1', '1', 1, 'module_tg', NULL),
(68, 'view_2', '1', 1, 'module_tg', NULL),
(69, 'view_3', '1', 1, 'module_tg', NULL),
(70, 'title', '', 1, 'module_tg', NULL),
(71, 'keyword', '', 1, 'module_tg', NULL),
(72, 'description', '', 1, 'module_tg', NULL),
(73, 'title2', '', 1, 'module_tg', NULL),
(74, 'keyword2', '', 1, 'module_tg', NULL),
(75, 'description2', '', 1, 'module_tg', NULL),
(76, 'title3', '', 1, 'module_tg', NULL),
(77, 'keyword3', '', 1, 'module_tg', NULL),
(78, 'description3', '', 1, 'module_tg', NULL),
(79, 'title', 'adf', 1, 'module_shop', NULL),
(80, 'keyword', '', 1, 'module_shop', NULL),
(81, 'description', '', 1, 'module_shop', NULL),
(82, 'title2', '[catname]企业列表', 1, 'module_shop', NULL),
(83, 'keyword2', '[catname]企业列表', 1, 'module_shop', NULL),
(84, 'description2', '[catname]企业列表', 1, 'module_shop', NULL),
(85, 'credit', '7天包换|正品保证|如实描述', 1, 'module_shop', NULL),
(86, 'logo_width', '100', 1, 'module_shop', NULL),
(87, 'logo_height', '100', 1, 'module_shop', NULL),
(88, 'display_cat', '0', 1, 'module_shop', NULL),
(89, 'list_row', '20', 1, 'module_shop', NULL),
(90, 'wmark_type', '1', 1, NULL, NULL),
(91, 'wmark_words', 'B2B-Builder', 1, NULL, NULL),
(92, 'wmark_words_color', '#339900', 1, NULL, NULL),
(93, 'wmark_image', 'logo.gif', 1, NULL, NULL),
(94, 'wmark_locaction', '9', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_con_group`
--

CREATE TABLE `mallbuilder_web_con_group` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(60) default NULL,
  `lang` varchar(5) default NULL,
  `sort` smallint(4) default '0',
  `logo` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 导出表中的数据 `mallbuilder_web_con_group`
--

INSERT INTO `mallbuilder_web_con_group` (`id`, `title`, `lang`, `sort`, `logo`) VALUES
(3, 'Buyer', 'en', 0, NULL),
(4, 'Seller', 'en', 0, NULL),
(5, '购物指南', 'cn', 0, 'http://localhost/b2b/uploadfile/all/2012/07/29/1343557862.jpg'),
(6, '配送方式', 'cn', 0, 'http://localhost/b2b/uploadfile/all/2012/07/29/1343558060.jpg'),
(7, '支付方式', 'cn', 0, 'http://localhost/b2b/uploadfile/all/2012/07/29/1343558124.jpg'),
(8, '售后服务', 'cn', 0, 'http://localhost/b2b/uploadfile/all/2012/07/29/1343558141.jpg'),
(9, '特色服务', 'cn', 0, 'http://localhost/b2b/uploadfile/all/2012/07/29/1343558151.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_link`
--

CREATE TABLE `mallbuilder_web_link` (
  `linkid` int(4) NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `url` varchar(200) default NULL,
  `statu` tinyint(1) NOT NULL default '0',
  `orderid` int(11) NOT NULL default '0',
  `log` varchar(100) default NULL,
  `province` varchar(15) default NULL,
  `city` varchar(15) default NULL,
  `stime` int(11) default NULL,
  `etime` int(11) default NULL,
  `con` text,
  PRIMARY KEY  (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_web_link`
--

