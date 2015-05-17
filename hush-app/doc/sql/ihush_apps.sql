/*
SQLyog 企业版 - MySQL GUI v7.14 
MySQL - 5.1.41 : Database - ihush_apps
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`ihush_apps` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ihush_apps`;

/*Table structure for table `product_0` */

DROP TABLE IF EXISTS `product_0`;

CREATE TABLE `product_0` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*Table structure for table `product_1` */

DROP TABLE IF EXISTS `product_1`;

CREATE TABLE `product_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*Table structure for table `product_2` */

DROP TABLE IF EXISTS `product_2`;

CREATE TABLE `product_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*Table structure for table `product_1` */

DROP TABLE IF EXISTS `product_3`;

CREATE TABLE `product_3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*Table structure for table `product_p` */

DROP TABLE IF EXISTS `product_p`;

CREATE TABLE `product_p` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `product_p` */

LOCK TABLES `product_p` WRITE;

insert  into `product_p`(`id`,`name`,`desc`,`updatetime`) values (1,'Test product 1','Test product 1','2010-08-31 06:04:33'),(2,'Test product 2','Test product 2','2010-08-31 06:04:33'),(3,'Test product 3','Test product 3','2010-08-31 06:04:33'),(4,'Test product 4','Test product 4','2010-08-31 06:04:33'),(5,'Test product 5','Test product 5','2010-08-31 06:04:33'),(6,'Test product 6','Test product 6','2010-08-31 06:04:33'),(7,'Test product 7','Test product 7','2010-08-31 06:04:33'),(8,'Test product 8','Test product 8','2010-08-31 06:04:33'),(9,'Test product 9','Test product 9','2010-08-31 06:04:33'),(10,'Test product 10','Test product 10','2010-08-31 06:04:33');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;