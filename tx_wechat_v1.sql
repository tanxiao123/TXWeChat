/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50562
Source Host           : localhost:3306
Source Database       : tx_wechat_v1

Target Server Type    : MYSQL
Target Server Version : 50562
File Encoding         : 65001

Date: 2020-07-30 17:47:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tx_admin
-- ----------------------------
DROP TABLE IF EXISTS `tx_admin`;
CREATE TABLE `tx_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `login_number` datetime DEFAULT NULL,
  `login_ip` varchar(10) DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL COMMENT '1：可用\r\n            0：禁用',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_admin
-- ----------------------------
INSERT INTO `tx_admin` VALUES ('1', 'admin', 'admin', null, 'aAS821f', null, null, 'aA8gJstIhSqAg', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for tx_admin_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tx_admin_group_access`;
CREATE TABLE `tx_admin_group_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_admin_group_access
-- ----------------------------
INSERT INTO `tx_admin_group_access` VALUES ('1', '1', '1', null, null, null, null);

-- ----------------------------
-- Table structure for tx_attachment
-- ----------------------------
DROP TABLE IF EXISTS `tx_attachment`;
CREATE TABLE `tx_attachment` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL COMMENT '1、图片；2、音乐；3、视频',
  `create_time` datetime DEFAULT NULL,
  `upload_dir` varchar(100) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for tx_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tx_auth_group`;
CREATE TABLE `tx_auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL COMMENT '0：禁用\r\n            1：启用',
  `rules` varchar(255) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_auth_group
-- ----------------------------
INSERT INTO `tx_auth_group` VALUES ('1', '超级管理员权限', '1', 'all', null, null, null, null);

-- ----------------------------
-- Table structure for tx_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tx_auth_rule`;
CREATE TABLE `tx_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `rule` varchar(255) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL COMMENT '1：菜单\r\n            2：接口访问',
  `status` varchar(2) DEFAULT NULL COMMENT '0：禁用\r\n            1：正常',
  `icon` varchar(100) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_auth_rule
-- ----------------------------
INSERT INTO `tx_auth_rule` VALUES ('1', '控制台', '#', '1', '1', null, '0', '1', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('2', '系统配置', '#', '1', '1', null, '0', '1', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('24', '访问权限管理', '/admin/group/index', '1', '1', 'layui-icon layui-icon-group', '2', '2', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('3', '微信配置', '#', '1', '1', null, '0', '1', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('23', '菜单删除操作权限', '/admin/rule/remove', '2', '1', null, '12', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('16', '菜单添加操作权限', '/admin/rule/add', '2', '1', null, '12', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('12', '菜单管理', '/admin/rule/index', '1', '1', 'layui-icon layui-icon-template-1', '2', '2', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('25', '访问请求权限', '/admin/group/getRuleList', '2', '1', null, '24', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('15', '菜单请求访问权限', '/admin/rule/getrulelist', '2', '1', null, '12', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('22', '测试菜单1', '/admin/index/test', '1', '1', '', '1', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('20', '菜单编辑操作权限', '/admin/rule/edit', '2', '1', null, '12', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('21', '菜单禁用启用权限', '/admin/rule/state', '2', '1', null, '12', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('26', '访问编辑权限', '/admin/group/edit', '2', '1', null, '24', '3', null, null, null, null);
INSERT INTO `tx_auth_rule` VALUES ('27', '访问权限授权', '/admin/group/apply', '2', '1', null, '24', '3', null, null, null, null);

-- ----------------------------
-- Table structure for tx_config
-- ----------------------------
DROP TABLE IF EXISTS `tx_config`;
CREATE TABLE `tx_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `var_name` varchar(100) DEFAULT NULL,
  `var_value` varchar(255) DEFAULT NULL,
  `level` varchar(2) DEFAULT NULL COMMENT '1：全局使用\r\n            2：私有管理端使用\r\n            3：私有应用端使用',
  `status` varchar(2) DEFAULT NULL COMMENT '0：禁用\r\n            1：正常',
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_config
-- ----------------------------
INSERT INTO `tx_config` VALUES ('1', '应用名称', 'app_name', 'TxWeChat', '1', '1', null, '2020-07-23 15:00:05', null, '2020-07-23 15:00:08');
INSERT INTO `tx_config` VALUES ('2', '登录失败错误次数', 'login_times', '3', '1', '1', null, null, null, null);

-- ----------------------------
-- Table structure for tx_fans
-- ----------------------------
DROP TABLE IF EXISTS `tx_fans`;
CREATE TABLE `tx_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_fans
-- ----------------------------

-- ----------------------------
-- Table structure for tx_log
-- ----------------------------
DROP TABLE IF EXISTS `tx_log`;
CREATE TABLE `tx_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_url` varchar(255) DEFAULT NULL,
  `request_header` varchar(255) DEFAULT NULL,
  `request_body` varchar(255) DEFAULT NULL,
  `response_body` varchar(255) DEFAULT NULL,
  `request_ip` varchar(10) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_log
-- ----------------------------

-- ----------------------------
-- Table structure for tx_wechats
-- ----------------------------
DROP TABLE IF EXISTS `tx_wechats`;
CREATE TABLE `tx_wechats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) DEFAULT NULL,
  `encodingaeskey` varchar(255) DEFAULT NULL,
  `level` varchar(2) DEFAULT NULL COMMENT '1、普通订阅号2、认证订阅号3、普通服务号4、认证服务号/认证媒体/政府订阅号',
  `name` varchar(30) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `original` varchar(50) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `appid` varchar(50) DEFAULT NULL,
  `app_secret` varchar(255) DEFAULT NULL,
  `is_default` varchar(2) DEFAULT NULL COMMENT '1：是\r\n            2：否',
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_wechats
-- ----------------------------

-- ----------------------------
-- Table structure for tx_wxapps
-- ----------------------------
DROP TABLE IF EXISTS `tx_wxapps`;
CREATE TABLE `tx_wxapps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) DEFAULT NULL,
  `encodingaeskey` varchar(255) DEFAULT NULL,
  `level` varchar(2) DEFAULT NULL COMMENT '接口权限级别:1.未认证2.已认证',
  `account` varchar(30) DEFAULT NULL,
  `original` varchar(50) DEFAULT NULL,
  `appid` varchar(50) DEFAULT NULL,
  `secret` varchar(50) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `appdomain` varchar(255) DEFAULT NULL,
  `is_default` varchar(2) DEFAULT NULL COMMENT '1：是\r\n            2：否',
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of tx_wxapps
-- ----------------------------
