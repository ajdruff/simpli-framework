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

/*Table structure for table `nstock_domains` */

DROP TABLE IF EXISTS `nstock_domains`;

CREATE TABLE `nstock_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subdomain` varchar(255) NOT NULL,
  `tld` varchar(3) NOT NULL DEFAULT 'com',
  `bin` enum('y','n') NOT NULL DEFAULT 'y',
  `bid` enum('y','n') NOT NULL DEFAULT 'n',
  `price` varchar(255) NOT NULL,
  `currency` enum('EUR','USD') NOT NULL DEFAULT 'USD',
  `time_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `featured` enum('y','n') NOT NULL DEFAULT 'n',
  `seller` varchar(11) NOT NULL DEFAULT '1',
  `approved` enum('y','n') NOT NULL DEFAULT 'n',
  `time_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_approved` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reg_available` enum('y','n') NOT NULL DEFAULT 'n',
  `time_list_start` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `time_list_stop` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `list_status` enum('pending','pending_update','active','archived','not listed','dupe') NOT NULL DEFAULT 'pending',
  `not_listed_reason` enum('disapproved','queue') DEFAULT NULL,
  `price_note` varchar(50) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `added_by` varchar(50) NOT NULL,
  `concat_test` varchar(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_sent_list_start` enum('y','n') DEFAULT 'n',
  `email_sent_list_end` enum('y','n') DEFAULT 'n',
  `email_sent_rejected` enum('y','n') DEFAULT 'n',
  `email_sent_not_listed` enum('y','n') DEFAULT 'n',
  `rejected_reason` enum('tos','spam','daily_limit') DEFAULT NULL,
  `reviewer_public_comments` varchar(1024) DEFAULT NULL,
  `on_ticker` enum('y','n') DEFAULT 'n',
  `total_clicks` int(11) DEFAULT NULL,
  `total_impressions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2193 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
