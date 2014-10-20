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

/*Table structure for table `nstock_stats_dn_clicks_history` */

DROP TABLE IF EXISTS `nstock_stats_dn_clicks_history`;

CREATE TABLE `nstock_stats_dn_clicks_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `unique_count` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `hour` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_date_hour` (`domain_id`,`date`,`hour`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
