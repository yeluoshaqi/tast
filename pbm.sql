-- MySQL dump 10.13  Distrib 5.6.25, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pbm
-- ------------------------------------------------------
-- Server version	5.6.25-0ubuntu0.15.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) unsigned NOT NULL COMMENT 'parent_id',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `sort` smallint(4) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COMMENT='区域表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--

LOCK TABLES `city` WRITE;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
INSERT INTO `city` VALUES (119,1,'客服',0);
/*!40000 ALTER TABLE `city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `item_id` int(11) unsigned NOT NULL COMMENT '订单ＩＤ',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型，默认１为客服向服务人员评价',
  `score` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '评分',
  `item` varchar(10) NOT NULL COMMENT '选项',
  `content` text NOT NULL COMMENT '内容',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评价时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='评价表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field`
--

DROP TABLE IF EXISTS `field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `source_type` varchar(20) NOT NULL COMMENT '资源类型',
  `source_id` smallint(4) unsigned NOT NULL COMMENT '资源ID',
  `name` varchar(200) NOT NULL COMMENT '字段名称',
  `color` varchar(20) NOT NULL COMMENT '颜色',
  `count` smallint(2) unsigned NOT NULL COMMENT '次数',
  `type` varchar(20) NOT NULL DEFAULT 'text' COMMENT '类型',
  `is_number` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是数字',
  `unit` varchar(20) NOT NULL COMMENT '单位',
  `sort` smallint(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='附加字段表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field`
--

LOCK TABLES `field` WRITE;
/*!40000 ALTER TABLE `field` DISABLE KEYS */;
/*!40000 ALTER TABLE `field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_value`
--

DROP TABLE IF EXISTS `field_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `log_id` int(10) unsigned NOT NULL COMMENT '服务记录ID',
  `field_id` smallint(4) unsigned NOT NULL COMMENT '字段ID',
  `value` varchar(255) NOT NULL COMMENT '字段值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=229 DEFAULT CHARSET=utf8 COMMENT='字段值';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_value`
--

LOCK TABLES `field_value` WRITE;
/*!40000 ALTER TABLE `field_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `field_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `host_routes`
--

DROP TABLE IF EXISTS `host_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `host_routes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL COMMENT '回复信息的标识',
  `wx_id` smallint(6) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `motify_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='回复信息路由表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `host_routes`
--

LOCK TABLES `host_routes` WRITE;
/*!40000 ALTER TABLE `host_routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `host_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icoupon_list`
--

DROP TABLE IF EXISTS `icoupon_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icoupon_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) DEFAULT NULL,
  `desc` varchar(120) DEFAULT NULL,
  `coupon_price` int(11) NOT NULL DEFAULT '0',
  `totalnum` int(11) NOT NULL DEFAULT '0',
  `least_price` int(11) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `pay_channels` varchar(255) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icoupon_list`
--

LOCK TABLES `icoupon_list` WRITE;
/*!40000 ALTER TABLE `icoupon_list` DISABLE KEYS */;
INSERT INTO `icoupon_list` VALUES (1,'test143142342349-0==9=0=','test01',10,10000,15111,2015,2015,0,'1','2015-08-30 08:36:00','2015-08-31 01:09:39'),(2,'title','title',10,1000,10,2015,2015,0,'1','2015-08-30 08:44:05','2015-10-15 02:23:32'),(3,'title','title',10,1000,10,2015,2015,1440924476,'1','2015-08-30 08:47:56','2015-10-15 02:23:32'),(4,'title','title',10,1000,10,2015,2015,1440924530,'1','2015-08-30 08:48:50','2015-10-15 02:23:32'),(5,'title','title',10,1000,10,2015,2015,1440924642,'1','2015-08-30 08:50:42','2015-10-15 02:23:32'),(6,'title','title',10,1000,10,2015,2015,1440924666,'1','2015-08-30 08:51:06','2015-10-15 02:23:32'),(7,'test14314234234','test01',10,10000,15,0,0,1440926536,'1','2015-08-30 09:22:16','2015-10-15 02:23:32');
/*!40000 ALTER TABLE `icoupon_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icoupon_sncode`
--

DROP TABLE IF EXISTS `icoupon_sncode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icoupon_sncode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `sncode` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fan_id` int(11) DEFAULT NULL,
  `order_sn` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_price` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `usetime` int(10) NOT NULL,
  `starttime` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  `lingqu_time` int(11) DEFAULT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_icoupon_sncode_on_fan_id` (`fan_id`),
  KEY `index_icoupon_sncode_on_cid` (`cid`),
  KEY `index_icoupon_sncode_on_starttime_and_endtime` (`starttime`,`endtime`),
  KEY `index_icoupon_sncode_on_sncode` (`sncode`),
  KEY `index_icoupon_sncode_on_endtime` (`endtime`),
  KEY `index_icoupon_sncode_on_order_sn` (`order_sn`),
  KEY `index_icoupon_sncode_on_lingqu_time` (`lingqu_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icoupon_sncode`
--

LOCK TABLES `icoupon_sncode` WRITE;
/*!40000 ALTER TABLE `icoupon_sncode` DISABLE KEYS */;
/*!40000 ALTER TABLE `icoupon_sncode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `wx_id` smallint(4) unsigned NOT NULL COMMENT '公众账号ID',
  `name` varchar(50) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `nickname` varchar(100) NOT NULL COMMENT '微信昵称',
  `openid` varchar(200) NOT NULL COMMENT '微信OPENID',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `last_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上次访问时间',
  `city` int(11) unsigned NOT NULL COMMENT 'city id',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，默认0为未激活',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=766 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,1,'test','15313019855','小良','asdfasjdfhajklsdhfj;HIFDSHKFJHJKHEUIHF','2015-05-06 01:23:00','0000-00-00 00:00:00',0,'test',1,'');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `wx_id` smallint(4) unsigned NOT NULL COMMENT '微信ID',
  `top_id` smallint(4) unsigned NOT NULL COMMENT '一级菜单ID',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `type` varchar(50) NOT NULL COMMENT '菜单类型',
  `value` varchar(255) NOT NULL COMMENT '值',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '9999' COMMENT '排序，默认最大',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='微信菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `member_id` int(11) unsigned NOT NULL COMMENT '会员ID',
  `type` tinyint(2) unsigned NOT NULL COMMENT '类型',
  `content` text NOT NULL COMMENT '内容',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='在线留言';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'ID',
  `keyword` varchar(50) NOT NULL,
  `wx_id` smallint(4) unsigned NOT NULL COMMENT '微信iD',
  `front_id` smallint(4) unsigned DEFAULT NULL COMMENT '前图文ID',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `desc` varchar(255) DEFAULT NULL COMMENT '简介',
  `content` text COMMENT '内容',
  `link` varchar(200) DEFAULT NULL COMMENT '链接地址',
  `userfile` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图文回复表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nurse_date`
--

DROP TABLE IF EXISTS `nurse_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nurse_date` (
  `nurse_id` int(11) unsigned NOT NULL COMMENT 'nurse id',
  `date` varchar(20) NOT NULL COMMENT 'date',
  `time` varchar(100) NOT NULL COMMENT 'time',
  PRIMARY KEY (`nurse_id`,`date`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务人员值班日期';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nurse_date`
--

LOCK TABLES `nurse_date` WRITE;
/*!40000 ALTER TABLE `nurse_date` DISABLE KEYS */;
/*!40000 ALTER TABLE `nurse_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '订单类型，默认１为微信订单，２为电话订单',
  `order_num` varchar(20) NOT NULL COMMENT '订单号',
  `member_id` int(11) unsigned NOT NULL COMMENT '子女ID',
  `service_id` int(11) unsigned NOT NULL COMMENT '服务ID',
  `fee` double(8,2) NOT NULL COMMENT '费用',
  `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否支付，默认１为未支付',
  `pay_num` varchar(20) NOT NULL COMMENT '微信支付订单号',
  `count` smallint(2) unsigned NOT NULL COMMENT '剩余次数',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，默认1为新订单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COMMENT='订单信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (191,1,'201508037792w',1,1,9.90,2,'22',0,'2015-08-03 00:49:01',1),(192,1,'201508296263w',1,1,9.90,2,'33',0,'2015-08-29 08:07:59',1),(193,1,'201508291541w',1,1,9.90,2,'44',0,'2015-08-29 08:08:35',1),(194,2,'14408441131063',0,2,19.80,1,'',0,'2015-08-29 10:28:33',1),(195,2,'14409203499296',0,2,19.80,1,'',0,'2015-08-30 07:39:09',1),(196,2,'14409203547503',0,2,19.80,1,'',0,'2015-08-30 07:39:14',1);
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `num` varchar(20) NOT NULL COMMENT '编号',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ＩＤ',
  `family_id` int(11) unsigned NOT NULL COMMENT '家人ＩＤ',
  `nurse_id` int(11) unsigned NOT NULL COMMENT '护士ＩＤ',
  `name` varchar(50) NOT NULL COMMENT '家人姓名',
  `mobile` varchar(15) NOT NULL COMMENT '手机号码',
  `city` int(11) unsigned NOT NULL COMMENT 'city',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `date` varchar(20) NOT NULL COMMENT '上门日期',
  `time` varchar(20) NOT NULL COMMENT '上门时间',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `motify_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `entry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '录入时间',
  `is_comment` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否评价，默认１为未评价',
  `status` smallint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=513 DEFAULT CHARSET=utf8 COMMENT='预约表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item`
--

LOCK TABLES `order_item` WRITE;
/*!40000 ALTER TABLE `order_item` DISABLE KEYS */;
INSERT INTO `order_item` VALUES (489,'2015080349841',191,136,0,'test','15313019855',32,'test','2015-08-03','08:00-09:00','11','2015-08-03 00:49:01','2015-08-03 00:49:01','0000-00-00 00:00:00',0,1),(490,'2015080397382',191,136,0,'test','15313019855',32,'test','2015-08-10','08:00-09:00','11','2015-08-03 00:49:01','2015-08-03 00:49:01','0000-00-00 00:00:00',0,1),(491,'2015080340423',191,136,0,'test','15313019855',32,'test','2015-08-17','08:00-09:00','11','2015-08-03 00:49:01','2015-08-03 00:49:01','0000-00-00 00:00:00',0,1),(492,'2015080380594',191,136,0,'test','15313019855',32,'test','2015-08-24','08:00-09:00','11','2015-08-03 00:49:01','2015-08-03 00:49:01','0000-00-00 00:00:00',0,1),(493,'2015082977401',192,136,0,'test','15313019855',32,'test','2015-08-01','11:00-12:00','','2015-08-29 08:07:59','2015-08-29 08:07:59','0000-00-00 00:00:00',0,1),(494,'2015082998682',192,136,0,'test','15313019855',32,'test','2015-08-08','11:00-12:00','','2015-08-29 08:07:59','2015-08-29 08:07:59','0000-00-00 00:00:00',0,1),(495,'2015082952063',192,136,0,'test','15313019855',32,'test','2015-08-15','11:00-12:00','','2015-08-29 08:07:59','2015-08-29 08:07:59','0000-00-00 00:00:00',0,1),(496,'2015082992604',192,136,0,'test','15313019855',32,'test','2015-08-22','11:00-12:00','','2015-08-29 08:07:59','2015-08-29 08:07:59','0000-00-00 00:00:00',0,1),(497,'2015082959121',193,136,0,'test','15313019855',32,'test','2015-08-01','11:00-12:00','','2015-08-29 08:08:35','2015-08-29 08:08:35','0000-00-00 00:00:00',0,1),(498,'2015082949122',193,136,0,'test','15313019855',32,'test','2015-08-08','11:00-12:00','','2015-08-29 08:08:35','2015-08-29 08:08:35','0000-00-00 00:00:00',0,1),(499,'2015082980193',193,136,0,'test','15313019855',32,'test','2015-08-15','11:00-12:00','','2015-08-29 08:08:35','2015-08-29 08:08:35','0000-00-00 00:00:00',0,1),(500,'2015082978504',193,136,0,'test','15313019855',32,'test','2015-08-22','11:00-12:00','','2015-08-29 08:08:35','2015-08-29 08:08:35','0000-00-00 00:00:00',0,1),(501,'201508294457m',194,0,0,'','',0,'','','08:00-09:00','','2015-08-29 10:28:33','2015-08-29 10:28:33','0000-00-00 00:00:00',0,1),(502,'201508295639m',194,0,0,'','',0,'','1999-12-07','08:00-09:00','','2015-08-29 10:28:33','2015-08-29 10:28:33','0000-00-00 00:00:00',0,1),(503,'201508297224m',194,0,0,'','',0,'','1999-12-14','08:00-09:00','','2015-08-29 10:28:33','2015-08-29 10:28:33','0000-00-00 00:00:00',0,1),(504,'201508293760m',194,0,0,'','',0,'','1999-12-21','08:00-09:00','','2015-08-29 10:28:33','2015-08-29 10:28:33','0000-00-00 00:00:00',0,1),(505,'201508303731m',195,0,0,'','',0,'','','08:00-09:00','','2015-08-30 07:39:09','2015-08-30 07:39:09','0000-00-00 00:00:00',0,1),(506,'201508305271m',195,0,0,'','',0,'','1999-12-07','08:00-09:00','','2015-08-30 07:39:09','2015-08-30 07:39:09','0000-00-00 00:00:00',0,1),(507,'201508304806m',195,0,0,'','',0,'','1999-12-14','08:00-09:00','','2015-08-30 07:39:09','2015-08-30 07:39:09','0000-00-00 00:00:00',0,1),(508,'201508302830m',195,0,0,'','',0,'','1999-12-21','08:00-09:00','','2015-08-30 07:39:09','2015-08-30 07:39:09','0000-00-00 00:00:00',0,1),(509,'201508306646m',196,0,0,'2342','',0,'','','08:00-09:00','','2015-08-30 07:39:14','2015-08-30 07:39:14','0000-00-00 00:00:00',0,1),(510,'201508304731m',196,0,0,'2342','',0,'','1999-12-07','08:00-09:00','','2015-08-30 07:39:14','2015-08-30 07:39:14','0000-00-00 00:00:00',0,1),(511,'201508304929m',196,0,0,'2342','',0,'','1999-12-14','08:00-09:00','','2015-08-30 07:39:14','2015-08-30 07:39:14','0000-00-00 00:00:00',0,1),(512,'201508301440m',196,0,0,'2342','',0,'','1999-12-21','08:00-09:00','','2015-08-30 07:39:14','2015-08-30 07:39:14','0000-00-00 00:00:00',0,1);
/*!40000 ALTER TABLE `order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parent`
--

DROP TABLE IF EXISTS `parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `member_id` int(11) unsigned NOT NULL COMMENT '会员ID',
  `title` varchar(50) NOT NULL COMMENT '称呼',
  `name` varchar(50) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `birthday` varchar(20) NOT NULL COMMENT '生日',
  `city` int(11) unsigned NOT NULL COMMENT 'city',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别，默认1为男性，2为女性',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COMMENT='父母信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parent`
--

LOCK TABLES `parent` WRITE;
/*!40000 ALTER TABLE `parent` DISABLE KEYS */;
INSERT INTO `parent` VALUES (136,1,'自己','test','15313019855','1970-01-01',32,'test',1,'2015-08-02 12:16:14','2015-08-02 12:16:14');
/*!40000 ALTER TABLE `parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `register` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别：默认１为男性',
  `idcard` varchar(50) NOT NULL COMMENT '身份证号码',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='服务人员注册表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `register`
--

LOCK TABLES `register` WRITE;
/*!40000 ALTER TABLE `register` DISABLE KEYS */;
/*!40000 ALTER TABLE `register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '服务名称',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `price` double(8,2) NOT NULL COMMENT '价格',
  `original` double(8,2) NOT NULL COMMENT '原价',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，默认0为未使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='服务信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'标准套餐','4次基本检测，一份健康报告',9.90,99.90,'0000-00-00 00:00:00','0000-00-00 00:00:00',2),(2,'标准套餐2','5次基本检测，二份健康报告',19.80,298.80,'0000-00-00 00:00:00','0000-00-00 00:00:00',2);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_log`
--

DROP TABLE IF EXISTS `service_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录的日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='服务记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_log`
--

LOCK TABLES `service_log` WRITE;
/*!40000 ALTER TABLE `service_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text`
--

DROP TABLE IF EXISTS `text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text` (
  `id` smallint(4) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `wx_id` smallint(4) unsigned NOT NULL COMMENT '微信ID',
  `content` varchar(255) NOT NULL COMMENT '回复内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文本回复表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text`
--

LOCK TABLES `text` WRITE;
/*!40000 ALTER TABLE `text` DISABLE KEYS */;
/*!40000 ALTER TABLE `text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` smallint(4) unsigned NOT NULL COMMENT '分组ID',
  `name` varchar(50) NOT NULL COMMENT '登录名',
  `password` varchar(200) NOT NULL COMMENT '密码',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `last_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上次访问时间',
  `last_ip` varchar(20) NOT NULL COMMENT '上次访问IP',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，默认0为未激活',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,1,'test','05a671c66aefea124cc08b76ea6d30bb','0000-00-00 00:00:00','0000-00-00 00:00:00','',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wx`
--

DROP TABLE IF EXISTS `wx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '账号名称',
  `token` varchar(200) NOT NULL COMMENT 'token',
  `appid` varchar(200) NOT NULL COMMENT 'appid',
  `appsecret` varchar(200) NOT NULL COMMENT 'appsecret',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `motify_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='微信公众账号表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wx`
--

LOCK TABLES `wx` WRITE;
/*!40000 ALTER TABLE `wx` DISABLE KEYS */;
/*!40000 ALTER TABLE `wx` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-02 14:44:11
