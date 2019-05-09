-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 11 月 17 日 20:49
-- 服务器版本: 5.5.40
-- PHP 版本: 5.4.33

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hush_core`
--

-- --------------------------------------------------------

--
-- 表的结构 `app`
--

DROP TABLE IF EXISTS `app`;
CREATE TABLE IF NOT EXISTS `app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `icon` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `path` varchar(50) NOT NULL DEFAULT '',
  `pid` int(10) NOT NULL DEFAULT '0',
  `order` int(10) NOT NULL DEFAULT '0',
  `is_app` enum('YES','NO') NOT NULL DEFAULT 'YES',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=100 ;

--
-- 转存表中的数据 `app`
--

INSERT INTO `app` (`id`, `name`, `path`, `pid`, `order`, `is_app`) VALUES
(1, '系统管理', '', 0, 1, 'NO'),
(2, '权限管理', '', 1, 0, 'NO'),
(3, '角色管理', '/acl/roleList', 2, 0, 'YES'),
(4, '用户管理', '/acl/userList', 2, 0, 'YES'),
(5, '资源管理', '/acl/resourceList', 2, 0, 'YES'),
(6, '菜单管理', '/acl/appList', 2, 0, 'YES'),
(7, '测试工具', '', 0, 99, 'NO'),
(8, '测试菜单', '', 7, 0, 'NO'),
(9, '基础接口测试', '/test/apiList', 8, 0, 'YES'),
(10, '系统管理', '', 1, 0, 'NO'),
(11, '系统概览', '/acl/welcome', 10, 0, 'YES'),
(12, '个人设置', '/acl/personal', 10, 0, 'YES');

-- --------------------------------------------------------

--
-- 表的结构 `app_role`
--

DROP TABLE IF EXISTS `app_role`;
CREATE TABLE IF NOT EXISTS `app_role` (
  `app_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`app_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_role`
--

INSERT INTO `app_role` (`app_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(12, 6),
(12, 7),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_flow`
--

DROP TABLE IF EXISTS `bpm_flow`;
CREATE TABLE IF NOT EXISTS `bpm_flow` (
  `bpm_flow_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_name` varchar(100) NOT NULL,
  `bpm_flow_type` int(11) DEFAULT NULL,
  `bpm_flow_desc` text,
  `bpm_flow_status` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `bpm_flow`
--

INSERT INTO `bpm_flow` (`bpm_flow_id`, `bpm_flow_name`, `bpm_flow_type`, `bpm_flow_desc`, `bpm_flow_status`) VALUES
(1, '请假流程', NULL, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_flow_op`
--

DROP TABLE IF EXISTS `bpm_flow_op`;
CREATE TABLE IF NOT EXISTS `bpm_flow_op` (
  `bpm_flow_op_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bpm_flow_op_action` varchar(100) NOT NULL,
  `bpm_flow_op_detail` varchar(255) NOT NULL,
  `bpm_flow_op_time` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_op_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `bpm_flow_role`
--

DROP TABLE IF EXISTS `bpm_flow_role`;
CREATE TABLE IF NOT EXISTS `bpm_flow_role` (
  `bpm_flow_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bpm_flow_role`
--

INSERT INTO `bpm_flow_role` (`bpm_flow_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_model`
--

DROP TABLE IF EXISTS `bpm_model`;
CREATE TABLE IF NOT EXISTS `bpm_model` (
  `bpm_model_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_model_json` text,
  `bpm_model_form` text,
  `bpm_model_status` int(11) NOT NULL,
  PRIMARY KEY (`bpm_model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `bpm_model`
--

INSERT INTO `bpm_model` (`bpm_model_id`, `bpm_flow_id`, `bpm_model_json`, `bpm_model_form`, `bpm_model_status`) VALUES
(1, 1, NULL, '<tr><td class="field">请假天数</td><td class="value"><input name="field[1]" class="text" type="text"></td></tr>', 0);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_model_field`
--

DROP TABLE IF EXISTS `bpm_model_field`;
CREATE TABLE IF NOT EXISTS `bpm_model_field` (
  `bpm_model_field_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_model_id` int(11) NOT NULL,
  `bpm_model_field_name` varchar(100) NOT NULL,
  `bpm_model_field_alias` varchar(255) DEFAULT NULL,
  `bpm_model_field_type` int(11) NOT NULL,
  `bpm_model_field_attr` varchar(255) DEFAULT NULL,
  `bpm_model_field_length` int(11) NOT NULL DEFAULT '0',
  `bpm_model_field_option` text,
  PRIMARY KEY (`bpm_model_field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `bpm_model_field`
--

INSERT INTO `bpm_model_field` (`bpm_model_field_id`, `bpm_model_id`, `bpm_model_field_name`, `bpm_model_field_alias`, `bpm_model_field_type`, `bpm_model_field_attr`, `bpm_model_field_length`, `bpm_model_field_option`) VALUES
(1, 1, '请假天数', NULL, 1, 'class=text', 1000, '');

-- --------------------------------------------------------

--
-- 表的结构 `bpm_node`
--

DROP TABLE IF EXISTS `bpm_node`;
CREATE TABLE IF NOT EXISTS `bpm_node` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `bpm_node`
--

INSERT INTO `bpm_node` (`bpm_node_id`, `bpm_flow_id`, `bpm_node_type`, `bpm_node_attr`, `bpm_node_name`, `bpm_node_code`, `bpm_node_next`, `bpm_node_pos_left`, `bpm_node_pos_top`, `bpm_node_status`) VALUES
(1, 1, 1, 1, '开始申请', 'pbel.model_form_add(1);\r\nreturn true; // exit\r\npbel.forward(2);', NULL, 314, 23, 0),
(2, 1, 4, 2, '组长审核', 'pbel.audit_by_role(2);\r\nreturn true; // exit\r\npbel.forward(3);', NULL, 315, 92, 0),
(3, 1, 2, 2, '组长审核通过', 'if (pbel.audit_check()) {\r\n  pbel.forward(4);\r\n} else {\r\n  pbel.forward(7);\r\n}', NULL, 315, 163, 0),
(4, 1, 4, 2, '经理审核', 'pbel.audit_by_role(1);\r\nreturn true; // exit\r\npbel.forward(5);', NULL, 314, 233, 0),
(5, 1, 2, 2, '经理审核通过', 'if (pbel.audit_check()) {\r\n  pbel.forward(6);\r\n} else {\r\n  pbel.forward(7);\r\n}', NULL, 315, 303, 0),
(6, 1, 1, 3, '请假成功', '### PBEL语言可以使用PHP语法 ###', NULL, 316, 376, 0),
(7, 1, 1, 3, '请假失败', '### PBEL语言可以使用PHP语法 ###', NULL, 472, 233, 0);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_node_path`
--

DROP TABLE IF EXISTS `bpm_node_path`;
CREATE TABLE IF NOT EXISTS `bpm_node_path` (
  `bpm_flow_id` int(11) NOT NULL,
  `bpm_node_id_from` int(11) NOT NULL,
  `bpm_node_id_to` int(11) NOT NULL,
  PRIMARY KEY (`bpm_flow_id`,`bpm_node_id_from`,`bpm_node_id_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bpm_node_path`
--

INSERT INTO `bpm_node_path` (`bpm_flow_id`, `bpm_node_id_from`, `bpm_node_id_to`) VALUES
(1, 1, 2),
(1, 2, 3),
(1, 3, 4),
(1, 3, 7),
(1, 4, 5),
(1, 5, 6),
(1, 5, 7);

-- --------------------------------------------------------

--
-- 表的结构 `bpm_request`
--

DROP TABLE IF EXISTS `bpm_request`;
CREATE TABLE IF NOT EXISTS `bpm_request` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `bpm_request_audit`
--

DROP TABLE IF EXISTS `bpm_request_audit`;
CREATE TABLE IF NOT EXISTS `bpm_request_audit` (
  `bpm_request_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_request_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `bpm_request_audit_done` int(11) NOT NULL DEFAULT '0',
  `bpm_request_audit_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bpm_request_audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `bpm_request_op`
--

DROP TABLE IF EXISTS `bpm_request_op`;
CREATE TABLE IF NOT EXISTS `bpm_request_op` (
  `bpm_request_op_id` int(11) NOT NULL AUTO_INCREMENT,
  `bpm_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `bpm_request_op_action` varchar(100) NOT NULL,
  `bpm_request_op_detail` text NOT NULL,
  `bpm_request_op_time` int(11) NOT NULL,
  PRIMARY KEY (`bpm_request_op_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `resource`
--

DROP TABLE IF EXISTS `resource`;
CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `resource`
--

INSERT INTO `resource` (`id`, `app_id`, `name`, `description`) VALUES
(1, 0, 'acl_role_add', '添加后台角色'),
(2, 0, 'acl_role_edit', '编辑后台角色'),
(3, 0, 'acl_user_add', '添加后台用户'),
(4, 0, 'acl_user_edit', '编辑后台用户'),
(5, 0, 'acl_user_passwd', '修改后台用户密码'),
(6, 0, 'acl_resource_add', '添加资源权限'),
(7, 0, 'acl_resource_edit', '编辑资源权限'),
(8, 0, 'acl_app_add', '添加后台菜单'),
(9, 0, 'acl_app_edit', '编辑后台菜单');

-- --------------------------------------------------------

--
-- 表的结构 `resource_role`
--

DROP TABLE IF EXISTS `resource_role`;
CREATE TABLE IF NOT EXISTS `resource_role` (
  `resource_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`resource_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `resource_role`
--

INSERT INTO `resource_role` (`resource_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `alias` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`, `alias`) VALUES
(1, 'SA', '超管'),
(2, 'AM', '普管'),
(3, 'FI', '财务'),
(4, 'CS', '客服'),
(5, 'OP', '运营'),
(6, 'BD', '商务'),
(7, 'EDIT', '编辑');

-- --------------------------------------------------------

--
-- 表的结构 `role_priv`
--

DROP TABLE IF EXISTS `role_priv`;
CREATE TABLE IF NOT EXISTS `role_priv` (
  `role_id` int(10) NOT NULL,
  `priv_id` int(10) NOT NULL,
  PRIMARY KEY (`role_id`,`priv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `role_priv`
--

INSERT INTO `role_priv` (`role_id`, `priv_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `passr` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name_pass` (`name`,`pass`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `type`, `name`, `pass`, `passr`) VALUES
(1, 0, 'sa', '788ef84d5a89a1ce91c310ec164f8d47', '');

-- --------------------------------------------------------

--
-- 表的结构 `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
