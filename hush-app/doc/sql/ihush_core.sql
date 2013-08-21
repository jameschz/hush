/*
SQLyog 企业版 - MySQL GUI v7.14 
MySQL - 5.1.41 : Database - ihush_core
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`ihush_core` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ihush_core`;

/*Table structure for table `app` */

DROP TABLE IF EXISTS `app`;

CREATE TABLE `app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL DEFAULT '',
  `pid` int(10) NOT NULL DEFAULT '0',
  `order` int(10) NOT NULL DEFAULT '0',
  `is_app` enum('YES','NO') NOT NULL DEFAULT 'YES',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `app` */

LOCK TABLES `app` WRITE;

insert  into `app`(`id`,`name`,`path`,`pid`,`order`,`is_app`) values (1,'系统管理','',0,1,'NO'),(2,'核心管理','',1,0,'NO'),(3,'角色管理','/acl/roleList',2,0,'YES'),(4,'用户管理','/acl/userList',2,0,'YES'),(5,'资源管理','/acl/resourceList',2,0,'YES'),(6,'菜单管理','/acl/appList',2,0,'YES'),(7,'常用工具','',0,3,'NO'),(8,'测试菜单','',7,0,'NO'),(9,'测试应用','/test/',8,0,'YES'),(10,'日常管理','',1,0,'NO'),(11,'欢迎界面','/common/',10,0,'YES'),(12,'个人设置','/common/personal',10,0,'YES'),(13,'流程管理','/bpm/',2,0,'YES'),(14,'常用流程','',0,2,'NO'),(15,'申请管理','',14,0,'NO'),(16,'我的申请','/request/sendList',15,0,'YES'),(17,'收到申请','/request/recvList',15,0,'YES');

UNLOCK TABLES;

/*Table structure for table `app_role` */

DROP TABLE IF EXISTS `app_role`;

CREATE TABLE `app_role` (
  `app_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`app_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `app_role` */

LOCK TABLES `app_role` WRITE;

insert  into `app_role`(`app_id`,`role_id`) values (1,1),(1,2),(1,3),(1,4),(1,5),(2,1),(2,2),(3,1),(4,1),(4,2),(5,1),(5,2),(6,1),(6,2),(7,1),(7,2),(7,3),(7,4),(7,5),(8,1),(8,2),(8,3),(8,4),(8,5),(9,1),(9,2),(9,3),(9,4),(9,5),(10,1),(10,2),(10,3),(10,4),(10,5),(11,1),(11,2),(11,3),(11,4),(11,5),(12,1),(12,2),(12,3),(12,4),(12,5),(13,1),(13,2),(14,1),(14,2),(14,3),(14,4),(14,5),(15,1),(15,2),(15,3),(15,4),(15,5),(16,1),(16,2),(16,3),(16,4),(16,5),(17,1),(17,2),(17,3),(17,4),(17,5);

UNLOCK TABLES;

/*Table structure for table `bpm_flow` */

DROP TABLE IF EXISTS `bpm_flow`;

CREATE TABLE `bpm_flow` (
  `bpm_flow_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_name` varchar(100) NOT NULL,
  `bpm_flow_type` int(11) DEFAULT NULL,
  `bpm_flow_desc` text,
  `bpm_flow_status` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bpm_flow` */

LOCK TABLES `bpm_flow` WRITE;

insert  into `bpm_flow`(`bpm_flow_id`,`bpm_flow_name`,`bpm_flow_type`,`bpm_flow_desc`,`bpm_flow_status`) values (1,'请假流程',NULL,'',1);

UNLOCK TABLES;

/*Table structure for table `bpm_flow_op` */

DROP TABLE IF EXISTS `bpm_flow_op`;

CREATE TABLE `bpm_flow_op` (
  `bpm_flow_op_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bpm_flow_op_action` varchar(100) NOT NULL,
  `bpm_flow_op_detail` varchar(255) NOT NULL,
  `bpm_flow_op_time` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_op_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_flow_op` */

LOCK TABLES `bpm_flow_op` WRITE;

UNLOCK TABLES;

/*Table structure for table `bpm_flow_role` */

DROP TABLE IF EXISTS `bpm_flow_role`;

CREATE TABLE `bpm_flow_role` (
  `bpm_flow_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_flow_role` */

LOCK TABLES `bpm_flow_role` WRITE;

insert  into `bpm_flow_role`(`bpm_flow_id`,`role_id`) values (1,1),(1,2),(1,3),(1,4),(1,5);

UNLOCK TABLES;

/*Table structure for table `bpm_model` */

DROP TABLE IF EXISTS `bpm_model`;

CREATE TABLE `bpm_model` (
  `bpm_model_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_model_json` text,
  `bpm_model_form` text,
  `bpm_model_status` int(11) NOT NULL,
  PRIMARY KEY (`bpm_model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bpm_model` */

LOCK TABLES `bpm_model` WRITE;

insert  into `bpm_model`(`bpm_model_id`,`bpm_flow_id`,`bpm_model_json`,`bpm_model_form`,`bpm_model_status`) values (1,1,NULL,'<tr><td class=\"field\">请假天数</td><td class=\"value\"><input name=\"field[1]\" class=\"text\" type=\"text\"></td></tr>',0);

UNLOCK TABLES;

/*Table structure for table `bpm_model_field` */

DROP TABLE IF EXISTS `bpm_model_field`;

CREATE TABLE `bpm_model_field` (
  `bpm_model_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_model_id` int(11) NOT NULL,
  `bpm_model_field_name` varchar(100) NOT NULL,
  `bpm_model_field_alias` varchar(255) DEFAULT NULL,
  `bpm_model_field_type` int(11) NOT NULL,
  `bpm_model_field_attr` varchar(255) DEFAULT NULL,
  `bpm_model_field_length` int(11) NOT NULL DEFAULT '0',
  `bpm_model_field_option` text,
  PRIMARY KEY (`bpm_model_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bpm_model_field` */

LOCK TABLES `bpm_model_field` WRITE;

insert  into `bpm_model_field`(`bpm_model_field_id`,`bpm_model_id`,`bpm_model_field_name`,`bpm_model_field_alias`,`bpm_model_field_type`,`bpm_model_field_attr`,`bpm_model_field_length`,`bpm_model_field_option`) values (1,1,'请假天数',NULL,1,'class=text',1000,'');

UNLOCK TABLES;

/*Table structure for table `bpm_node` */

DROP TABLE IF EXISTS `bpm_node`;

CREATE TABLE `bpm_node` (
  `bpm_node_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_node_type` int(11) NOT NULL,
  `bpm_node_attr` int(11) NOT NULL,
  `bpm_node_name` varchar(100) NOT NULL,
  `bpm_node_code` text,
  `bpm_node_next` int(11) DEFAULT '0',
  `bpm_node_pos_left` int(11) DEFAULT NULL,
  `bpm_node_pos_top` int(11) DEFAULT NULL,
  `bpm_node_status` int(11) NOT NULL,
  PRIMARY KEY (`bpm_node_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `bpm_node` */

LOCK TABLES `bpm_node` WRITE;

insert  into `bpm_node`(`bpm_node_id`,`bpm_flow_id`,`bpm_node_type`,`bpm_node_attr`,`bpm_node_name`,`bpm_node_code`,`bpm_node_next`,`bpm_node_pos_left`,`bpm_node_pos_top`,`bpm_node_status`) values (1,1,1,1,'开始申请','pbel.model_form_add(1);\r\nreturn true; // exit\r\npbel.forward(2);',NULL,314,23,0),(2,1,4,2,'组长审核','pbel.audit_by_role(2);\r\nreturn true; // exit\r\npbel.forward(3);',NULL,315,92,0),(3,1,2,2,'组长审核通过','if (pbel.audit_check()) {\r\n  pbel.forward(4);\r\n} else {\r\n  pbel.forward(7);\r\n}',NULL,315,163,0),(4,1,4,2,'经理审核','pbel.audit_by_role(1);\r\nreturn true; // exit\r\npbel.forward(5);',NULL,314,233,0),(5,1,2,2,'经理审核通过','if (pbel.audit_check()) {\r\n  pbel.forward(6);\r\n} else {\r\n  pbel.forward(7);\r\n}',NULL,315,303,0),(6,1,1,3,'请假成功','### PBEL语言可以使用PHP语法 ###',NULL,316,376,0),(7,1,1,3,'请假失败','### PBEL语言可以使用PHP语法 ###',NULL,472,233,0);

UNLOCK TABLES;

/*Table structure for table `bpm_node_path` */

DROP TABLE IF EXISTS `bpm_node_path`;

CREATE TABLE `bpm_node_path` (
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_node_id_from` int(11) NOT NULL,
  `bpm_node_id_to` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`,`bpm_node_id_from`,`bpm_node_id_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_node_path` */

LOCK TABLES `bpm_node_path` WRITE;

insert  into `bpm_node_path`(`bpm_flow_id`,`bpm_node_id_from`,`bpm_node_id_to`) values (1,1,2),(1,2,3),(1,3,4),(1,3,7),(1,4,5),(1,5,6),(1,5,7);

UNLOCK TABLES;

/*Table structure for table `bpm_request` */

DROP TABLE IF EXISTS `bpm_request`;

CREATE TABLE `bpm_request` (
  `bpm_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_node_id` int(11) NOT NULL,
  `bpm_request_subject` varchar(255) NOT NULL,
  `bpm_request_body` text NOT NULL,
  `bpm_request_sent` int(11) NOT NULL,
  `bpm_request_status` int(11) NOT NULL,
  `bpm_request_comment` text NOT NULL,
  PRIMARY KEY (`bpm_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_request` */

LOCK TABLES `bpm_request` WRITE;

UNLOCK TABLES;

/*Table structure for table `bpm_request_audit` */

DROP TABLE IF EXISTS `bpm_request_audit`;

CREATE TABLE `bpm_request_audit` (
  `bpm_request_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_request_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `bpm_request_audit_done` int(11) NOT NULL DEFAULT '0',
  `bpm_request_audit_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bpm_request_audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_request_audit` */

LOCK TABLES `bpm_request_audit` WRITE;

UNLOCK TABLES;

/*Table structure for table `bpm_request_op` */

DROP TABLE IF EXISTS `bpm_request_op`;

CREATE TABLE `bpm_request_op` (
  `bpm_request_op_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `bpm_request_op_action` varchar(100) NOT NULL,
  `bpm_request_op_detail` text NOT NULL,
  `bpm_request_op_time` int(11) NOT NULL,
  PRIMARY KEY (`bpm_request_op_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bpm_request_op` */

LOCK TABLES `bpm_request_op` WRITE;

UNLOCK TABLES;

/*Table structure for table `resource` */

DROP TABLE IF EXISTS `resource`;

CREATE TABLE `resource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `resource` */

LOCK TABLES `resource` WRITE;

insert  into `resource`(`id`,`app_id`,`name`,`description`) values (1,0,'acl_user_add','添加新后台用户'),(2,0,'acl_user_passwd','权限管理中密码修改');

UNLOCK TABLES;

/*Table structure for table `resource_role` */

DROP TABLE IF EXISTS `resource_role`;

CREATE TABLE `resource_role` (
  `resource_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`resource_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `resource_role` */

LOCK TABLES `resource_role` WRITE;

insert  into `resource_role`(`resource_id`,`role_id`) values (1,1),(2,1);

UNLOCK TABLES;

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `alias` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `role` */

LOCK TABLES `role` WRITE;

insert  into `role`(`id`,`name`,`alias`) values (1,'SA','超级管理员'),(2,'AM','普通管理员'),(3,'CS','客服人员'),(4,'LS','物流人员'),(5,'FS','财务人员');

UNLOCK TABLES;

/*Table structure for table `role_priv` */

DROP TABLE IF EXISTS `role_priv`;

CREATE TABLE `role_priv` (
  `role_id` int(10) NOT NULL,
  `priv_id` int(10) NOT NULL,
  PRIMARY KEY (`role_id`,`priv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `role_priv` */

LOCK TABLES `role_priv` WRITE;

insert  into `role_priv`(`role_id`,`priv_id`) values (1,1),(1,2),(1,3),(1,4),(1,5),(2,3),(2,4),(2,5);

UNLOCK TABLES;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `pass` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_pass` (`name`,`pass`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `user` */

LOCK TABLES `user` WRITE;

insert  into `user`(`id`,`name`,`pass`) values (2,'admin','77e2edcc9b40441200e31dc57dbb8829'),(1,'sa','788ef84d5a89a1ce91c310ec164f8d47');

UNLOCK TABLES;

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role` */

LOCK TABLES `user_role` WRITE;

insert  into `user_role`(`user_id`,`role_id`) values (1,1),(2,2);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
