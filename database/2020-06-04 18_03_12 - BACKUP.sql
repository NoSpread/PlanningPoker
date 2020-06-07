-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5985
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for planningpoker
CREATE DATABASE IF NOT EXISTS `planningpoker` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `planningpoker`;

-- Dumping structure for table planningpoker.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(300) NOT NULL,
  `last_ip` varbinary(50) DEFAULT NULL,
  `register_ip` varbinary(50) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.accounts: ~5 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `last_ip`, `register_ip`, `last_login`, `created_at`) VALUES
	(1, 'admin', 'admin@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$TlpkVnouZmxiU0l1MnViUA$ZE2wgGwDe+tu+7W285pKpMj05hC2nY/EYAfaRGbymho', _binary '', _binary 0x30, '2020-06-02 11:45:01', '2020-05-10 21:32:31'),
	(2, 'supporter', 'support@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$dTFPQ1hmT1FIcmdmWTEyNw$xSQZhiN94Q8ZIi0houCiAE6hHElP5m6dKK5EpFTEmpM', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(3, 'Tatjana', 'tadi@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$YUFZakdRcXJTL1NQMWJRRA$2bd+NmASHiNYtcOeUn+bMH+lb3LbuKvm+dYxhFoa/9k', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(4, 'Lucas', 'lucas@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$MUwxbU9pUXFWRHJzMXNkUA$T0HBILjp68/3R/XAMTubEWBFcmkNxTqnRURGd93MhRE', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(5, 'NoSpread', 'nospread@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$RVNXZ2JyU0lJQk5rU0tsNA$kix2n24+a/AkQbqXpFbB6gZqNXWCsWdZw1Gf6tLrCpo', _binary 0x3A3A31, _binary 0x00000000000000000000000000000001, '2020-05-25 15:02:46', '2020-05-25 15:02:04');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Dumping structure for table planningpoker.auth_tokens
CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.auth_tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;

-- Dumping structure for table planningpoker.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(300) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.games: ~11 rows (approximately)
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` (`id`, `topic`, `start_date`, `end_date`) VALUES
	(6, 'TEST TOPIC', '2020-05-11 12:48:07', NULL),
	(8, 'TEST TOPIC', '2020-05-11 12:51:16', NULL),
	(9, 'TEST TOPIC', '2020-05-11 12:52:21', NULL),
	(10, 'TEST TOPIC', '2020-05-11 12:54:23', '2020-05-11 13:00:03'),
	(11, 'TEST TOPIC', '2020-05-11 13:01:46', '2020-05-11 13:02:52'),
	(12, 'TEST TOPIC', '2020-05-11 13:03:15', '2020-05-11 13:03:19'),
	(14, 'TEST TOPIC', '2020-05-24 12:26:55', '2020-05-24 12:26:57'),
	(15, 'TEST TOPIC', '2020-05-24 12:35:02', '2020-05-24 12:35:05'),
	(16, 'TEST TOPIC', '2020-05-24 12:35:53', '2020-05-24 12:35:53'),
	(17, 'TEST TOPIC', '2020-05-24 12:51:58', '2020-05-24 12:51:58'),
	(18, 'TEST TOPIC', '2020-05-24 12:52:49', '2020-05-24 12:52:50');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

-- Dumping structure for table planningpoker.invites
CREATE TABLE IF NOT EXISTS `invites` (
  `Inviter_UserID` int(11) unsigned DEFAULT NULL,
  `Invited_GameID` int(11) unsigned DEFAULT NULL,
  `Invited_UserID` int(11) unsigned DEFAULT NULL,
  `accepted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  UNIQUE KEY `Invited_GameID_Invited_UserID` (`Invited_GameID`,`Invited_UserID`),
  KEY `FK_INVITEDUSERID` (`Invited_UserID`),
  KEY `FK_INVITERID` (`Inviter_UserID`),
  KEY `FK_INVITEDGAMEID` (`Invited_GameID`),
  CONSTRAINT `FK_INVITEDGAMEID` FOREIGN KEY (`Invited_GameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITEDUSERID` FOREIGN KEY (`Invited_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITERID` FOREIGN KEY (`Inviter_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.invites: ~40 rows (approximately)
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
INSERT INTO `invites` (`Inviter_UserID`, `Invited_GameID`, `Invited_UserID`, `accepted`) VALUES
	(1, 8, 1, 1),
	(1, 8, 2, 0),
	(1, 8, 3, 0),
	(1, 8, 4, 0),
	(1, 9, 1, 1),
	(1, 9, 2, 0),
	(1, 9, 3, 0),
	(1, 9, 4, 0),
	(1, 10, 1, 1),
	(1, 10, 2, 0),
	(1, 10, 3, 0),
	(1, 10, 4, 0),
	(1, 11, 1, 1),
	(1, 11, 2, 0),
	(1, 11, 3, 0),
	(1, 11, 4, 0),
	(1, 12, 1, 1),
	(1, 12, 2, 0),
	(1, 12, 3, 0),
	(1, 12, 4, 0),
	(1, 14, 1, 1),
	(1, 14, 2, 0),
	(1, 14, 3, 0),
	(1, 14, 4, 0),
	(1, 15, 1, 1),
	(1, 15, 2, 0),
	(1, 15, 3, 0),
	(1, 15, 4, 0),
	(1, 16, 1, 1),
	(1, 16, 2, 0),
	(1, 16, 3, 0),
	(1, 16, 4, 0),
	(1, 17, 1, 1),
	(1, 17, 2, 0),
	(1, 17, 3, 0),
	(1, 17, 4, 0),
	(1, 18, 1, 1),
	(1, 18, 2, 0),
	(1, 18, 3, 0),
	(1, 18, 4, 0);
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;

-- Dumping structure for table planningpoker.played_cards
CREATE TABLE IF NOT EXISTS `played_cards` (
  `GameID` int(11) unsigned DEFAULT NULL,
  `UserID` int(11) unsigned DEFAULT NULL,
  `CardID` int(11) unsigned DEFAULT NULL,
  UNIQUE KEY `GameID_UserID` (`GameID`,`UserID`),
  KEY `FK_USERID` (`UserID`),
  KEY `FK_GAMEID` (`GameID`),
  CONSTRAINT `FK_GAMEID` FOREIGN KEY (`GameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_USERID` FOREIGN KEY (`UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.played_cards: ~20 rows (approximately)
/*!40000 ALTER TABLE `played_cards` DISABLE KEYS */;
INSERT INTO `played_cards` (`GameID`, `UserID`, `CardID`) VALUES
	(14, 1, 1),
	(14, 2, 2),
	(14, 3, 3),
	(14, 4, 4),
	(15, 1, 1),
	(15, 2, 2),
	(15, 3, 3),
	(15, 4, 4),
	(16, 1, 1),
	(16, 2, 2),
	(16, 3, 3),
	(16, 4, 4),
	(17, 1, 1),
	(17, 2, 2),
	(17, 3, 3),
	(17, 4, 4),
	(18, 1, 1),
	(18, 2, 2),
	(18, 3, 3),
	(18, 4, 4);
/*!40000 ALTER TABLE `played_cards` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
