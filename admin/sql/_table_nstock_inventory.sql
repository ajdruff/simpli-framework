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

/*Table structure for table `nstock_inventory` */

DROP TABLE IF EXISTS `nstock_inventory`;

CREATE TABLE `nstock_inventory` (
  `id` int(11) NOT NULL DEFAULT '0',
  `subdomain` varchar(255) NOT NULL,
  `tld` varchar(3) NOT NULL DEFAULT 'com',
  `bin` enum('y','n') NOT NULL DEFAULT 'y',
  `bid` enum('y','n') NOT NULL DEFAULT 'n',
  `price` varchar(255) NOT NULL,
  `currency` enum('EUR','USD') NOT NULL DEFAULT 'USD',
  `time_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seller` varchar(11) NOT NULL DEFAULT '1',
  `time_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified` enum('y') DEFAULT NULL,
  UNIQUE KEY `domain_seller` (`subdomain`,`tld`,`seller`),
  UNIQUE KEY `subdomain` (`subdomain`,`tld`,`verified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
