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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table planningpoker.auth_tokens
CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` char(12) DEFAULT NULL,
  `hashedValidator` char(64) DEFAULT NULL,
  `userid` int(11) unsigned NOT NULL,
  `expires` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USERID_AUTH` (`userid`),
  CONSTRAINT `FK_USERID_AUTH` FOREIGN KEY (`userid`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table planningpoker.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(300) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table planningpoker.invites
CREATE TABLE IF NOT EXISTS `invites` (
  `Inviter_UserID` int(11) unsigned DEFAULT NULL,
  `Invited_GameID` int(11) unsigned DEFAULT NULL,
  `Invited_UserID` int(11) unsigned DEFAULT NULL,
  `accepted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `Invited_GameID_Invited_UserID` (`Invited_GameID`,`Invited_UserID`),
  KEY `FK_INVITEDUSERID` (`Invited_UserID`),
  KEY `FK_INVITERID` (`Inviter_UserID`),
  KEY `FK_INVITEDGAMEID` (`Invited_GameID`),
  CONSTRAINT `FK_INVITEDGAMEID` FOREIGN KEY (`Invited_GameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITEDUSERID` FOREIGN KEY (`Invited_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_INVITERID` FOREIGN KEY (`Inviter_UserID`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
