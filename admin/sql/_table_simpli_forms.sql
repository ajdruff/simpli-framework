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

/*Table structure for table `simpli_forms` */

DROP TABLE IF EXISTS `simpli_forms`;

CREATE TABLE `simpli_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_name` varchar(255) NOT NULL,
  `fields` text,
  `status` enum('new','saved','deleted','sspam','spam') NOT NULL DEFAULT 'new',
  `alert_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alert_recipients` varchar(255) DEFAULT NULL,
  `time_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
