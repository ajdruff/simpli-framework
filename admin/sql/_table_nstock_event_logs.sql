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

/*Table structure for table `nstock_event_logs` */

DROP TABLE IF EXISTS `nstock_event_logs`;

CREATE TABLE `nstock_event_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(255) NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1161 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
