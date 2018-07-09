-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_silanis.access
CREATE TABLE IF NOT EXISTS `access` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- Dumping data for table db_silanis.access: ~16 rows (approximately)
/*!40000 ALTER TABLE `access` DISABLE KEYS */;
INSERT INTO `access` (`access_id`, `group_id`, `menu_id`, `created_by`, `created_time`) VALUES
	(11, 3, 1, 'administrator', '2018-04-07 09:33:07'),
	(12, 3, 2, 'administrator', '2018-04-07 09:33:07'),
	(18, 2, 2, 'administrator', '2018-04-07 11:04:38'),
	(19, 2, 3, 'administrator', '2018-04-07 11:04:38'),
	(20, 2, 5, 'administrator', '2018-04-07 11:04:38'),
	(21, 6, 3, 'administrator', '2018-07-01 07:08:35'),
	(22, 6, 5, 'administrator', '2018-07-01 07:08:35'),
	(35, 4, 2, 'administrator', '2018-07-01 18:45:46'),
	(36, 4, 5, 'administrator', '2018-07-01 18:45:46'),
	(43, 1, 1, 'administrator', '2018-07-01 19:09:51'),
	(44, 1, 2, 'administrator', '2018-07-01 19:09:51'),
	(45, 1, 3, 'administrator', '2018-07-01 19:09:51'),
	(46, 1, 5, 'administrator', '2018-07-01 19:09:51'),
	(47, 1, 6, 'administrator', '2018-07-01 19:09:51'),
	(48, 1, 9, 'administrator', '2018-07-01 19:09:51'),
	(49, 1, 10, 'administrator', '2018-07-01 19:09:51');
/*!40000 ALTER TABLE `access` ENABLE KEYS */;

-- Dumping structure for table db_silanis.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table db_silanis.groups: ~3 rows (approximately)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `name`, `description`) VALUES
	(1, 'admin', 'Administrator'),
	(2, 'member', 'member1'),
	(3, 'op1', 'test');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Dumping structure for table db_silanis.list_menu
CREATE TABLE IF NOT EXISTS `list_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) NOT NULL,
  `menu_desc` varchar(50) NOT NULL,
  `url` varchar(250) NOT NULL,
  `icon_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `menu_order` tinyint(4) NOT NULL COMMENT 'urutan di menu',
  `parent_id` int(11) NOT NULL COMMENT 'id parent menunya',
  `ashead` tinyint(1) NOT NULL COMMENT 'sebagai header=1, child=0, biasa=2',
  `created_by` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL,
  `modified_by` varchar(50) NOT NULL,
  `modified_time` datetime NOT NULL,
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `menu_name` (`menu_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table db_silanis.list_menu: ~8 rows (approximately)
/*!40000 ALTER TABLE `list_menu` DISABLE KEYS */;
INSERT INTO `list_menu` (`menu_id`, `menu_name`, `menu_desc`, `url`, `icon_name`, `status`, `menu_order`, `parent_id`, `ashead`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES
	(1, 'management', 'Management', '#', 'fa fa-sitemap', 1, 1, 0, 1, 'admin', '2018-04-04 10:11:47', 'administrator', '2018-07-01 18:39:37'),
	(2, 'account', 'Account', 'account', 'fa fa-adjust', 1, 2, 0, 0, 'admin', '2018-04-04 11:03:59', 'administrator', '2018-07-01 18:39:55'),
	(3, 'groups', 'Groups', 'auth/groups', 'fa fa-users', 1, 3, 1, 0, '', '0000-00-00 00:00:00', 'administrator', '2018-07-01 12:35:23'),
	(5, 'home', 'Home', 'home', 'fa fa-home', 1, 4, 0, 0, 'admin@admin.com', '2018-04-05 08:25:21', 'administrator', '2018-07-01 17:08:41'),
	(6, 'menus', 'List Menu', 'auth/menus', 'fa fa-bars', 1, 5, 1, 0, 'admin@admin.com', '2018-04-05 15:28:55', 'administrator', '2018-07-01 13:21:22'),
	(8, 'testmenu', 'test 1', 'testmenu1', 'fa fa-archive', 1, 6, 0, 0, 'administrator', '2018-07-01 08:01:15', '', '0000-00-00 00:00:00'),
	(9, 'kegiatan_uji', 'kegiatan Uji', 'kegiatan_uji', 'fa fa-adjust', 1, 0, 0, 1, 'administrator', '2018-07-01 19:02:11', 'administrator', '2018-07-01 19:07:50'),
	(10, 'ujiberkala', 'Uji Berkala', 'uji_berkala', 'fa fa-adjust', 1, 0, 9, 0, 'administrator', '2018-07-01 19:08:45', 'administrator', '2018-07-01 19:09:04');
/*!40000 ALTER TABLE `list_menu` ENABLE KEYS */;

-- Dumping structure for table db_silanis.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table db_silanis.login_attempts: ~0 rows (approximately)
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;

-- Dumping structure for table db_silanis.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `full_name` varchar(50) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto` varchar(50) NOT NULL COMMENT 'additional',
  `foto_ttd` varchar(50) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nrk` varchar(20) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `status_kepegawaian` varchar(10) NOT NULL COMMENT 'PNS, NON PNS',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table db_silanis.users: ~3 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `full_name`, `company`, `phone`, `foto`, `foto_ttd`, `nip`, `nrk`, `lokasi`, `status_kepegawaian`) VALUES
	(1, '127.0.0.1', 'administrator', '$2y$08$O3aWEdDD5l1f6V.Xa2b7c.H8DnaPMDzrOeFm8v1ZDw1zonBWkzGr.', '', 'admin@admin.com', '', '2zwwe-wLi0LmWR0NAaNzGu3b42e25090ef40589a', 1521082658, NULL, 1268889823, 1531095307, 1, 'Admin', 'Admin1', 'Admin Full', 'ADMIN', '0', '', '', '', '', '', ''),
	(2, '::1', 'a@b.com', '$2y$08$Y5XnPqpmhZxJriuP8qcD4.oUQRyvHk.wgfpQZZusXfBrmf0XMSTcC', NULL, 'a@b.com', NULL, NULL, NULL, NULL, 1521537014, 1523073800, 1, 'a', 'member1', '', '-', '0091', '', '', '123', '12', '22', ''),
	(7, '::1', 'albi', '$2y$08$/nHPxYpwndRW0ERqMvd5s.cs6v8hyBGLpZjMNxZUAzZwC6h33NAEG', NULL, 'test@sdfd.com', NULL, NULL, NULL, NULL, 1530445832, 1530446632, 1, 'Albi', 'Albani', 'Ahmad Fadhilah', '', '123456', '', '', '123456', '12345', 'PG', '');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table db_silanis.users_groups
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- Dumping data for table db_silanis.users_groups: ~3 rows (approximately)
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
	(36, 1, 1),
	(29, 2, 2),
	(37, 7, 2);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
