/*
Navicat MySQL Data Transfer

Source Server         : xamaria
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : smarthome

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-03-05 23:22:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sensores`
-- ----------------------------
DROP TABLE IF EXISTS `sensores`;
CREATE TABLE `sensores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sensores
-- ----------------------------
INSERT INTO `sensores` VALUES ('1', 'admin', 'T', '20', '2021-02-16 21:13:58');
INSERT INTO `sensores` VALUES ('2', 'admin', 'H', '52', '2021-02-16 21:23:58');
INSERT INTO `sensores` VALUES ('3', 'admin', 'T', '18', '2021-03-02 19:19:21');
INSERT INTO `sensores` VALUES ('4', 'admin', 'M', '10', '2021-03-02 19:37:19');
INSERT INTO `sensores` VALUES ('6', 'admin', 'H', '60', '2021-03-02 21:43:03');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user` varchar(20) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `rol` char(1) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('admin', '202cb962ac59075b964b07152d234b70', 'A');
