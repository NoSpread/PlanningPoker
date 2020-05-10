-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(300) NOT NULL,
  `last_ip` varbinary(50) DEFAULT NULL,
  `register_ip` varbinary(50) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.accounts: ~4 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `last_ip`, `register_ip`, `last_login`, `created_at`) VALUES
	(1, 'admin', 'admin@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$TlpkVnouZmxiU0l1MnViUA$ZE2wgGwDe+tu+7W285pKpMj05hC2nY/EYAfaRGbymho', _binary '', _binary 0x30, '2020-05-10 22:22:23', '2020-05-10 21:32:31'),
	(2, 'supporter', 'support@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$dTFPQ1hmT1FIcmdmWTEyNw$xSQZhiN94Q8ZIi0houCiAE6hHElP5m6dKK5EpFTEmpM', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(3, 'Tatjana', 'tadi@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$YUFZakdRcXJTL1NQMWJRRA$2bd+NmASHiNYtcOeUn+bMH+lb3LbuKvm+dYxhFoa/9k', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(4, 'Lucas', 'lucas@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$MUwxbU9pUXFWRHJzMXNkUA$T0HBILjp68/3R/XAMTubEWBFcmkNxTqnRURGd93MhRE', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Dumping structure for table planningpoker.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(300) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.games: ~0 rows (approximately)
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` (`id`, `topic`, `start_date`, `end_date`) VALUES
	(3, 'TEST TOPIC', '2020-05-10 22:22:25', NULL);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

-- Dumping structure for table planningpoker.invites
CREATE TABLE IF NOT EXISTS `invites` (
  `Inviter_UserID` int(11) DEFAULT NULL,
  `Invited_GameID` int(11) DEFAULT NULL,
  `Invited_UserID` int(11) DEFAULT NULL,
  `accepted` tinyint(4) NOT NULL DEFAULT 0,
  UNIQUE KEY `Invited_GameID_Invited_UserID` (`Invited_GameID`,`Invited_UserID`),
  KEY `FK_INVITERID` (`Inviter_UserID`),
  KEY `FK_INVITEDUSERID` (`Invited_UserID`),
  KEY `FK_INVITEDGAMEID` (`Invited_GameID`) USING BTREE,
  CONSTRAINT `FK_INVITEDGAMEID` FOREIGN KEY (`Invited_GameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITEDUSERID` FOREIGN KEY (`Invited_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITERID` FOREIGN KEY (`Inviter_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.invites: ~0 rows (approximately)
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
INSERT INTO `invites` (`Inviter_UserID`, `Invited_GameID`, `Invited_UserID`, `accepted`) VALUES
	(1, 3, 1, 1),
	(1, 3, 4, 0),
	(1, 3, 3, 1);
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;

-- Dumping structure for table planningpoker.played_cards
CREATE TABLE IF NOT EXISTS `played_cards` (
  `GameID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CardID` int(11) DEFAULT NULL,
  UNIQUE KEY `GameID_UserID` (`GameID`,`UserID`),
  KEY `FK_USERID` (`UserID`),
  KEY `FK_GAMEID` (`GameID`),
  CONSTRAINT `FK_GAMEID` FOREIGN KEY (`GameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_USERID` FOREIGN KEY (`UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.played_cards: ~0 rows (approximately)
/*!40000 ALTER TABLE `played_cards` DISABLE KEYS */;
INSERT INTO `played_cards` (`GameID`, `UserID`, `CardID`) VALUES
	(3, 1, 1),
	(3, 3, 10);
/*!40000 ALTER TABLE `played_cards` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
