/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle document
# ------------------------------------------------------------

DROP TABLE IF EXISTS `document`;

CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('profil_image','event_image','post_image') COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Export von Tabelle event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_description` longtext COLLATE utf8_unicode_ci,
  `public_description` longtext COLLATE utf8_unicode_ci,
  `event_start` datetime DEFAULT NULL,
  `event_end` datetime DEFAULT NULL,
  `visitor_limit` int(11) NOT NULL,
  `is_public_event` enum('1','0') COLLATE utf8_unicode_ci DEFAULT NULL,
  `friends_can_see_event` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA764D218E` (`location_id`),
  KEY `IDX_3BAE0AA7F675F31B` (`author_id`),
  CONSTRAINT `FK_3BAE0AA764D218E` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  CONSTRAINT `FK_3BAE0AA7F675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`id`, `location_id`, `author_id`, `title`, `private_description`, `public_description`, `event_start`, `event_end`, `visitor_limit`, `is_public_event`, `friends_can_see_event`, `created`, `updated`)
VALUES
	(1,1,1,'Woop Woop','Lorem ipsum!','2015-01-01 00:00:00','2015-01-01 00:00:00',10,'1','1','2016-01-31 21:13:49',NULL);

/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` decimal(10,7) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `federal_state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `type` enum('user','event','post') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;

INSERT INTO `location` (`id`, `longitude`, `latitude`, `country`, `city`, `zip`, `street`, `street_number`, `federal_state`, `title`, `description`, `type`)
VALUES
	(1,9.6602843,54.3049147,'Germany','Rendsburg','24768','Hohe Str','10','Schleswig-Holstein','Huang Restaurant',NULL,'event'),
	(2,9.6578413,54.3060331,'Germany','Rendsburg','24768','Holsteinerstraße','10','Schleswig-Holstein','Ruby Days',NULL,'event'),
	(3,10.1319234,54.3278263,'Germany','Kiel','24013','Bergstraße','17','Schleswig-Holstein','Tucholsky Center Kiel',NULL,'event'),
	(4,10.1282213,54.3278431,'Germany','Kiel','24013','Legiensraße','40','Schleswig-Holstein','Die Villa',NULL,'event');

/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `is_active` int(11) NOT NULL,
  `is_verified` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location_id` int(11) DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `document_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D64964D218E` (`location_id`),
  KEY `IDX_8D93D649C33F7837` (`document_id`),
  CONSTRAINT `FK_8D93D64964D218E` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  CONSTRAINT `FK_8D93D649C33F7837` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `email`, `firstname`, `lastname`, `birthday`, `gender`, `description`, `password`, `roles`, `is_active`, `is_verified`, `created`, `location_id`, `locale`, `document_id`)
VALUES
	(1,'marie','asdsdsa@sasada.de','addsaas','adkjajkasajks','2011-01-01','male','asdsaasasa','dabjdfbdjkdas','[\"ROLE_USER\"]',1,NULL,'2015-12-22 14:40:02',1,'de',NULL),
	(2,'jean','asdasdas@asdassasd.de',NULL,NULL,NULL,NULL,NULL,'2222.wba4m','[\"ROLE_USER\"]',1,NULL,'2015-12-22 14:40:02',2,'',NULL),
	(3,'jack','asdsaasdsa@asdss.com',NULL,NULL,NULL,NULL,NULL,'$2y$13$V.2222.9OMvgDwtvKrfcMlGiDuPoJp3exp7v0.','[\"ROLE_USER\"]',1,NULL,'2015-12-24 17:43:49',3,'en',NULL),
	(4,'logan','sadsssds@asdadas.de',NULL,NULL,NULL,NULL,NULL,'222222.BZfRrayebDgPAF0RPvqyBBRFT.EA6AaIcCqYMWC','[\"ROLE_USER\"]',1,NULL,'2016-01-07 11:08:03',1,'en',NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
