/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50712
 Source Host           : localhost
 Source Database       : agency

 Target Server Type    : MySQL
 Target Server Version : 50712
 File Encoding         : utf-8

 Date: 09/09/2016 18:07:18 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `username` char(16) NOT NULL COMMENT '用户名',
  `nickname` char(16) NOT NULL COMMENT '昵称',
  `password` char(32) NOT NULL COMMENT '密码',
  `ctime` int(10) NOT NULL COMMENT '注册时间',
  `ip_limit` text COMMENT '限制IP（空值为不限制）',
  `last_login_time` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `last_login_ip` char(16) DEFAULT NULL COMMENT '上次登录IP',
  `status` tinyint(1) NOT NULL COMMENT '状态(0:封禁;1:正常;)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `admin`
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES ('1', 'admin', 'Admin', 'e10adc3949ba59abbe56e057f20f883e', '1472195276', '', '1473403941', '::1', '1'), ('2', 'eric', '暴暴', 'e10adc3949ba59abbe56e057f20f883e', '1472199287', '', null, null, '1');
COMMIT;

-- ----------------------------
--  Table structure for `brand`
-- ----------------------------
DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `fid` int(10) unsigned NOT NULL COMMENT '父ID',
  `name` varchar(64) NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `category`
-- ----------------------------
BEGIN;
INSERT INTO `category` VALUES ('1', '0', '江西省'), ('2', '0', '黑龙江省'), ('3', '1', '南昌市'), ('4', '2', '哈尔滨市'), ('5', '2', '鸡西市'), ('6', '4', '香坊区'), ('7', '4', '南岗区'), ('8', '6', '和兴路'), ('9', '7', '西大直街'), ('10', '8', '东北林业大学'), ('11', '9', '哈尔滨工业大学'), ('12', '8', '哈尔滨师范大学'), ('13', '1', '赣州市'), ('14', '13', '赣县'), ('15', '13', '于都县'), ('16', '14', '茅店镇'), ('17', '14', '大田乡'), ('18', '16', '义源村'), ('19', '16', '上坝村');
COMMIT;

-- ----------------------------
--  Table structure for `express`
-- ----------------------------
DROP TABLE IF EXISTS `express`;
CREATE TABLE `express` (
  `eid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '快递ID',
  `vendor` varchar(16) NOT NULL COMMENT '快递公司',
  `track_id` varchar(255) NOT NULL COMMENT '快递单号',
  `price` int(10) unsigned NOT NULL COMMENT '快递费',
  `shipped` int(10) unsigned DEFAULT NULL COMMENT '发货日期',
  `delivered` int(10) unsigned DEFAULT NULL COMMENT '送达时间',
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `express`
-- ----------------------------
BEGIN;
INSERT INTO `express` VALUES ('1', 'fed', '12345', '6', '2016', null);
COMMIT;

-- ----------------------------
--  Table structure for `item`
-- ----------------------------
DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `iid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `name` varchar(64) NOT NULL COMMENT '商品简称',
  `description` text NOT NULL COMMENT '商品描述',
  `category` int(10) unsigned NOT NULL COMMENT '类别',
  `brand` int(10) NOT NULL COMMENT '品牌',
  `price` int(10) unsigned NOT NULL COMMENT '售价',
  `cost` int(10) unsigned NOT NULL COMMENT '成本',
  PRIMARY KEY (`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `oid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `agency` tinyint(3) unsigned NOT NULL COMMENT '代购者ID',
  `iid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `property` varchar(255) NOT NULL COMMENT '商品属性',
  `price` int(10) unsigned NOT NULL COMMENT '售价',
  `cost` int(10) unsigned NOT NULL COMMENT '成本',
  `count` int(10) unsigned NOT NULL COMMENT '购买数量',
  `eid1` int(10) unsigned DEFAULT NULL COMMENT '快递ID',
  `eid2` int(10) unsigned DEFAULT NULL COMMENT '快递ID',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `endtime` int(10) unsigned DEFAULT NULL COMMENT '订单结束时间',
  `status` tinyint(3) NOT NULL COMMENT '订单状态(1:完成;0:进行中;2:已付款未送达;3:已送达未付款;-1:取消;)',
  `comment` text COMMENT '用户要求',
  `evaluation` text COMMENT '用户评价',
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `order`
-- ----------------------------
BEGIN;
INSERT INTO `order` VALUES ('1', '1', '0', '0', '', '200000', '120000', '2', '1', null, '1471578868', '1471599183', '1', null, null);
COMMIT;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `phone` char(16) DEFAULT NULL COMMENT '手机号',
  `real_name` varchar(32) DEFAULT NULL COMMENT '名',
  `nickname` varchar(32) DEFAULT NULL COMMENT '昵称',
  `wechat` char(64) DEFAULT NULL COMMENT '微信号',
  `email` varchar(128) DEFAULT NULL COMMENT '电子邮箱',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '性别(1:男;0:女;2:未知;)',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `last_purchase_time` int(10) unsigned DEFAULT NULL COMMENT '最近一次购买时间',
  `total_purchase` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总花费',
  `total_order_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总订单数量',
  `ctime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `profile` text COMMENT '用户描述',
  `invite_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '介绍人UID',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user`
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('1', '13764426340', '顾云龙', '暴暴', 'guyunlong0811', 'guyunlong0811@126.com', '1', '27', '1471602801', '200000', '1', '1471513434', 'test', '0'), ('2', '18019116118', '赵梦姣', null, null, 'mengjiao871025@126.com', '0', '0', null, '0', '0', '1471513434', 'aaa', '0'), ('3', '18019116118', '顾歆妤', '肉包', 'Ivy', 'Ivy.gu@126.com', '0', '0', null, '0', '0', '1473234218', null, '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
