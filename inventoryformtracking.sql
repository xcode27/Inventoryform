/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.1.41 : Database - inventory_tracking
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`inventory_tracking` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `inventory_tracking`;

/*Table structure for table `form_mapping` */

DROP TABLE IF EXISTS `form_mapping`;

CREATE TABLE `form_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supcode` varchar(25) DEFAULT NULL,
  `storecode` char(25) DEFAULT NULL,
  `formcode` char(12) DEFAULT NULL,
  `formname` char(35) DEFAULT NULL,
  `form_description` char(75) DEFAULT NULL,
  `frequency` int(2) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `form_mapping` */

insert  into `form_mapping`(`id`,`supcode`,`storecode`,`formcode`,`formname`,`form_description`,`frequency`,`date_created`,`date_modified`) values (12,'20160300009','JKMAN-MUN','ORO-FO','ORO FORM','ORO FORM',1,'2019-04-12',NULL),(11,'20160900001','CITYSUPER-FILINVEST','OMG-NA','OMG NAIL FORM','OMG NAIL FORM',1,'2019-03-28',NULL),(10,'20160900001','CITYSUPER-FILINVEST','ORO-FO','ORO FORM','ORO FORM',1,'2019-03-28',NULL),(5,'20160300006','MKT.','ORO-FO','ORO FORM','ORO FORM',2,'2019-03-28',NULL),(6,'20160300006','MKT.','OMG-NA','OMG NAIL FORM','OMG NAIL FORM',2,'2019-03-28',NULL),(8,'20160300006','MOE-RDS','ORO-FO','ORO FORM','ORO FORM',2,'2019-03-28',NULL),(9,'20160300006','MOE-RDS','OMG-NA','OMG NAIL FORM','OMG NAIL FORM',2,'2019-03-28',NULL);

/*Table structure for table `form_submitted` */

DROP TABLE IF EXISTS `form_submitted`;

CREATE TABLE `form_submitted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiving_id` int(25) NOT NULL,
  `formname` char(25) NOT NULL,
  `inventory_date` date NOT NULL,
  `remarks` char(75) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `RECEIVING_ID` (`receiving_id`),
  KEY `FORM_NAME` (`formname`),
  KEY `INVENTORY_DATE` (`inventory_date`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `form_submitted` */

insert  into `form_submitted`(`id`,`receiving_id`,`formname`,`inventory_date`,`remarks`) values (1,2,'OMG-NA','2019-04-04','wwr'),(2,2,'ORO-FO','2019-04-04','wwr'),(3,3,'ORO-FO','2019-04-12','on time'),(4,4,'ORO-FO','2019-04-12',NULL),(5,4,'OMG-NA','2019-04-12',NULL),(6,5,'ORO-FO','2019-04-12',NULL),(7,6,'ORO-FO','2019-04-15','complete');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_02_06_050754_create_receivinginventories_table',1),(4,'2019_02_07_053329_create_pocreateds_table',2);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `po_created_details` */

DROP TABLE IF EXISTS `po_created_details`;

CREATE TABLE `po_created_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tran_no` int(11) DEFAULT NULL,
  `head_id` int(11) DEFAULT NULL,
  `controlno` varchar(15) DEFAULT NULL,
  `product_code` varchar(15) DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `qty` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `po_created_details` */

insert  into `po_created_details`(`id`,`tran_no`,`head_id`,`controlno`,`product_code`,`product_name`,`qty`) values (1,126,5,'987654321','201901-0458','OMG MIRROR NAIL POLISH 10ML - ROSE GOLD (2)',1),(2,122,5,'987654321','201901-0460','OMG MIRROR NAIL POLISH 10ML - PINK GOLD (3)',1),(3,125,5,'987654321','201901-0469','OMG MIRROR NAIL POLISH 10ML - RED (4)',1),(4,132,6,'987654321','201801-0560','OMG ONE DIP INSTANT NAIL LACQUER REMOVER 75ML',2),(5,129,6,'987654321','201801-0140','OMG NAIL CARE CUTICLE REMOVER 60ML',2),(6,209,7,'987654321','201901-0033','OMG BUFFER FILE #059',3),(7,325,7,'987654321','201901-0012','OMG CRYSTAL NAIL FILE 018#2',3),(8,1204,44,'123456789','201208-0356','VELATISSIMO SUPPORTARE COLLANT(ORO)',2),(9,1206,44,'123456789','201208-0357','VELATISSIMO SUPPORTARE COLLANT(ORO)',2),(10,1201,44,'123456789','201208-0353','VELATISSIMO SUPPORTARE COLLANT(ORO)',2),(11,1202,44,'123456789','201208-0354','VELATISSIMO SUPPORTARE COLLANT(ORO)',2),(12,1203,44,'123456789','201208-0355','VELATISSIMO SUPPORTARE COLLANT(ORO)',54),(13,1205,44,'123456789','201208-0358','VELATISSIMO SUPPORTARE COLLANT(ORO)',6),(14,1209,44,'123456789','201208-0037','SUPPORT PLUS PANTYHOSE(ORO)',6),(15,15,2,'P000','201901-0137','OMG NAIL POLISH REGULAR 10ML - COLORLESS',1),(16,8,2,'P000','201901-0138','OMG NAIL POLISH REGULAR 10ML - BLACK OUT',2),(17,32,2,'P000','201901-0139','OMG NAIL POLISH REGULAR 10ML - PUSSY RED',3),(18,39,2,'P000','201901-0140','OMG NAIL POLISH REGULAR 10ML - WHITE OUT',4),(19,454,4,'P123456','201901-0551','OMG NAIL POLISH REGULAR 10ML WATSONS - COLORLESS',2),(20,447,4,'P123456','201901-0552','OMG NAIL POLISH REGULAR 10ML WATSONS - BLACK OUT',9),(21,471,4,'P123456','201901-0553','OMG NAIL POLISH REGULAR 10ML WATSONS - PUSSY RED',90),(22,478,4,'P123456','201901-0554','OMG NAIL POLISH REGULAR 10ML WATSONS - WHITE OUT',9),(23,472,4,'P123456','201901-0555','OMG NAIL POLISH REGULAR 10ML WATSONS - SALSA',78),(24,476,4,'P123456','201901-0556','OMG NAIL POLISH REGULAR 10ML WATSONS - TOUCH OF TA',7),(25,703,33,'P123456','201901-0435','SARANGHAE NAIL POLISH 10ML - AN-YEONG-HA-SE-YO',2);

/*Table structure for table `po_created_head` */

DROP TABLE IF EXISTS `po_created_head`;

CREATE TABLE `po_created_head` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controlno` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `po_no` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `store` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `store_name` char(120) COLLATE utf8_unicode_ci NOT NULL,
  `po_date` date NOT NULL,
  `received_by` char(25) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `po_created_head` */

insert  into `po_created_head`(`id`,`controlno`,`po_no`,`store`,`store_name`,`po_date`,`received_by`,`created_at`,`updated_at`) values (1,NULL,'987654321','CITYSUPER-FILINVEST','CITYSUPER INCORPORATED., (FILINVEST ALABANG)','2019-04-12','gerry','2019-04-12 05:56:42',NULL),(2,NULL,'123456789','CITYSUPER-FILINVEST','CITYSUPER INCORPORATED., (FILINVEST ALABANG)','2019-04-12','seph','2019-04-12 07:55:03',NULL),(3,'P000','123456','JKMAN-MUN','JACKMAN EMPORIUM,, MUNOZ','2019-04-12','seph','2019-04-12 09:05:43','2019-04-12 10:20:00'),(4,'P123456','45556','CITYSUPER-FILINVEST','CITYSUPER INCORPORATED., (FILINVEST ALABANG)','2019-04-15','seph','2019-04-15 02:25:03','2019-04-15 02:25:48'),(5,'P1312','6346363','NON','MERCURY DRUG CORP.(3041 CABANATUAN CITY WALTERMART)','2019-04-22','seph','2019-04-22 06:28:41',NULL),(6,'4324324','dwadfw','SCWC','SAMAL CENTRAL WAREHOUSE CLUB  (SCWC)','2019-04-22','seph','2019-04-22 06:29:12',NULL),(7,'525346','986','SCWC','SAMAL CENTRAL WAREHOUSE CLUB  (SCWC)','2019-04-22','seph','2019-04-22 06:38:16',NULL);

/*Table structure for table `po_item_serve` */

DROP TABLE IF EXISTS `po_item_serve`;

CREATE TABLE `po_item_serve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_serve_id` int(25) DEFAULT NULL,
  `product_code` char(25) DEFAULT NULL,
  `product` char(75) DEFAULT NULL,
  `qty_serve` int(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `po_item_serve` */

/*Table structure for table `po_productlist` */

DROP TABLE IF EXISTS `po_productlist`;

CREATE TABLE `po_productlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_no` char(25) DEFAULT NULL,
  `prod_sys_code` char(25) DEFAULT NULL,
  `product` char(75) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `po_productlist` */

/*Table structure for table `po_serve` */

DROP TABLE IF EXISTS `po_serve`;

CREATE TABLE `po_serve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_no` char(25) DEFAULT NULL,
  `store_code` char(25) DEFAULT NULL,
  `store` char(120) DEFAULT NULL,
  `po_date` datetime DEFAULT NULL,
  `dr_sr` char(25) DEFAULT NULL,
  `po_serve_date` date DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `tran_no` int(25) DEFAULT NULL,
  `created_by` char(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `po_serve` */

/*Table structure for table `receivinginventories` */

DROP TABLE IF EXISTS `receivinginventories`;

CREATE TABLE `receivinginventories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_no` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supervisor` char(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `promo` char(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inventory_date` date DEFAULT NULL,
  `received_by` char(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `store_name` char(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `STORE` (`store`),
  KEY `INVENTORY_DATE` (`inventory_date`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `receivinginventories` */

insert  into `receivinginventories`(`id`,`control_no`,`store`,`supervisor`,`promo`,`inventory_date`,`received_by`,`created_at`,`updated_at`,`store_name`) values (2,'P12345','CITYSUPER-FILINVEST','DICK JESUS CABUNOT','MARIA HAYNA HAMBRE','2019-04-04','seph','2019-04-04 06:06:21','2019-04-04 06:26:59','CITYSUPER INCORPORATED., (FILINVEST ALABANG)'),(3,'123456789','CITYSUPER-FILINVEST','DICK JESUS CABUNOT','MARIA HAYNA HAMBRE','2019-04-12','gerry','2019-04-12 05:16:11',NULL,'CITYSUPER INCORPORATED., (FILINVEST ALABANG)'),(4,'P111','MOE-RDS','CHRISTINA  EVANGELISTA','FREDERICO FABILIAR,','2019-04-12','seph','2019-04-12 06:56:51',NULL,'EXPRESSION STATIONERY SHOP., (360 RDS GENERAL TRIAS)'),(5,'P000','JKMAN-MUN','MARY ANN COLOSO','ewdfawd','2019-04-12','seph','2019-04-12 07:00:19',NULL,'JACKMAN EMPORIUM,, MUNOZ'),(6,'P123456','CITYSUPER-FILINVEST','DICK JESUS CABUNOT','MARIA HAYNA HAMBRE','2019-04-15','seph','2019-04-15 02:24:35',NULL,'CITYSUPER INCORPORATED., (FILINVEST ALABANG)');

/*Table structure for table `storemapping` */

DROP TABLE IF EXISTS `storemapping`;

CREATE TABLE `storemapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_code` char(25) DEFAULT NULL,
  `store_name` char(75) DEFAULT NULL,
  `supcode` char(25) DEFAULT NULL,
  `supervisor` char(25) DEFAULT NULL,
  `expected_submission` text,
  `date_created` datetime DEFAULT NULL,
  `created_by` char(25) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `area` char(120) DEFAULT NULL,
  `promo` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `storemapping` */

insert  into `storemapping`(`id`,`store_code`,`store_name`,`supcode`,`supervisor`,`expected_submission`,`date_created`,`created_by`,`date_modified`,`area`,`promo`) values (6,'JKMAN-MUN','JACKMAN EMPORIUM,, MUNOZ','20160300009','MARY ANN COLOSO','[{ \'Date\':\'2019-04-12\'}]','2019-04-12 06:59:52','seph',NULL,'dwadwad','[{ \'Name\':\'ewdfawd\'}]'),(5,'CITYSUPER-FILINVEST','CITYSUPER INCORPORATED., (FILINVEST ALABANG)','20160900001','DICK JESUS CABUNOT','[{ \'Date\':\'2019-03-21\'},{ \'Date\':\'2019-03-07\'}]','2019-03-28 09:54:52','gerry','2019-03-28 10:14:59','ALABANG','[{ \'Name\':\'MARIA HAYNA HAMBRE\'}]'),(3,'MKT.','ALABANG SUPERMARKET CORPORATION','20160300006','CHRISTINA  EVANGELISTA','[{ \'Date\':\'2019-03-09\'},{ \'Date\':\'2019-03-27\'}]','2019-03-28 09:18:45','gerry',NULL,'ALABANG','[{ \'Name\':\'JOCELYN DEPATILLO\'}]'),(4,'MOE-RDS','EXPRESSION STATIONERY SHOP., (360 RDS GENERAL TRIAS)','20160300006','CHRISTINA  EVANGELISTA','[{ \'Date\':\'2019-03-07\'},{ \'Date\':\'2019-03-21\'}]','2019-03-28 09:27:40','gerry',NULL,'CAVITE','[{ \'Name\':\'FREDERICO FABILIAR\'},{ \'Name\':\'\'}]');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

/* Procedure structure for procedure `inventory_monitoring` */

/*!50003 DROP PROCEDURE IF EXISTS  `inventory_monitoring` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`exxel`@`%` PROCEDURE `inventory_monitoring`(`_year` VARCHAR(5),`_form` VARCHAR(10))
BEGIN
	
  SET  @sql_text = CONCAT(
		" 
			SELECT b.store,b.store_name,
			SUM(IF(MONTH(b.`created_at`) = 1, b.tot, 0)) AS JAN, 	
			SUM(IF(MONTH(b.created_at) = 2, b.tot, 0)) AS FEB,	
			SUM(IF(MONTH(b.created_at) = 3, b.tot, 0)) AS MAR,	
			SUM(IF(MONTH(b.created_at) = 4, b.tot, 0)) AS APR,	
			SUM(IF(MONTH(b.created_at) = 5, b.tot, 0)) AS MAY,	
			SUM(IF(MONTH(b.created_at) = 6, b.tot, 0)) AS JUN,	
			SUM(IF(MONTH(b.created_at) = 7, b.tot, 0)) AS JUL,	
			SUM(IF(MONTH(b.created_at) = 8, b.tot, 0)) AS AUG,	
			SUM(IF(MONTH(b.created_at) = 9, b.tot, 0)) AS SEP,	
			SUM(IF(MONTH(b.created_at) = 10, b.tot, 0)) AS OCT,	
			SUM(IF(MONTH(b.created_at) = 11, b.tot, 0)) AS NOV,	
			SUM(IF(MONTH(b.created_at) = 12, b.tot, 0)) AS `DEC`
			FROM ( 
				SELECT x.`receiving_id`,xx.`store`,xx.`store_name`,xx.`created_at`,COUNT(xx.created_at) AS tot 
				FROM form_submitted X,receivinginventories xx 
				WHERE x.`receiving_id` = xx.id 
					AND YEAR(xx.`created_at`) = ",_year,"
					AND x.`formname` = '",_form,"'
					GROUP BY xx.`created_at`
			 )b  GROUP BY b.store
		");
  PREPARE stmt FROM @sql_text;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt; 
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
