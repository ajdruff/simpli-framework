/*
SQLyog Enterprise - MySQL GUI v6.07
Host - 5.5.24-log : Database - @`NSTOCK_VARS_DB`
*********************************************************************
Server version : 5.5.24-log
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;



USE `@`NSTOCK_VARS_DB``;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `nstock_widgets_codes` */

DROP TABLE IF EXISTS `nstock_widgets_codes`;

CREATE TABLE `nstock_widgets_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_code` varchar(255) DEFAULT NULL,
  `time_added` datetime DEFAULT NULL,
  `time_expires` datetime DEFAULT NULL,
  `paypal_sku` enum('super-widget','widget') DEFAULT 'widget',
  PRIMARY KEY (`id`),
  UNIQUE KEY `widget_code` (`widget_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
