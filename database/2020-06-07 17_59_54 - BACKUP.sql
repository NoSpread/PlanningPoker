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

-- Dumping data for table planningpoker.accounts: ~5 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `username`, `email`, `password`, `last_ip`, `register_ip`, `last_login`, `created_at`) VALUES
	(1, 'admin', 'admin@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$VGFnclVsSC9GSWdLaDRBQQ$smdGmp1TJnjy2CS8O920bLNNgHmL1X0pgPSvGK7Feio', _binary '', _binary 0x30, '2020-06-07 17:47:30', '2020-05-10 21:32:31'),
	(2, 'supporter', 'support@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$Y2diZGZnYmRmYmR5$NEi9nB5oyJq/Qo4DJ4wsa2vOKO2NzNMIOt7yRkB8sUI', _binary '', _binary 0x30, '2020-06-04 21:36:14', '2020-05-10 21:32:32'),
	(3, 'Tatjana', 'tadi@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$YUFZakdRcXJTL1NQMWJRRA$2bd+NmASHiNYtcOeUn+bMH+lb3LbuKvm+dYxhFoa/9k', _binary 0x30, _binary 0x30, '2020-05-10 21:32:32', '2020-05-10 21:32:32'),
	(4, 'Lucas', 'lucas@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$Y2diZGZnYmRmYmR5$NEi9nB5oyJq/Qo4DJ4wsa2vOKO2NzNMIOt7yRkB8sUI', _binary '', _binary 0x30, '2020-06-07 16:38:17', '2020-05-10 21:32:32'),
	(5, 'NoSpread', 'nospread@mail.de', '$argon2id$v=19$m=65536,t=4,p=1$RVNXZ2JyU0lJQk5rU0tsNA$kix2n24+a/AkQbqXpFbB6gZqNXWCsWdZw1Gf6tLrCpo', _binary 0x3A3A31, _binary 0x00000000000000000000000000000001, '2020-05-25 15:02:46', '2020-05-25 15:02:04'),
	(6, 'Orchideev', 'a@a.de', '$argon2id$v=19$m=65536,t=4,p=1$U1M0NWZtWWtlaG84VXp0VA$Tk3Aa5mkMgb9gIK3IsqxDn7iiWWmsOaFx5E86C5sDp0', _binary '', _binary 0x30, '2020-06-06 22:30:53', '2020-06-06 22:30:02');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.auth_tokens: ~12 rows (approximately)
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
INSERT INTO `auth_tokens` (`id`, `selector`, `hashedValidator`, `userid`, `expires`) VALUES
	(14, '363594fbe594', '8dca936b0a6eee56937dc46b8360b4e9f4661bdadd0c051829c82f834fda4209', 2, '2020-07-04 20:28:03'),
	(15, '2b6dafa11d5a', '836568107596086be5255c4a3a40c15c2022c5372d9c43e9c34bece20fd08e46', 2, '2020-07-04 21:36:14'),
	(20, 'af8dbbe4b73e', 'e0ef1de022786c9253f20a95c08d0c34ba6232c0cb6c0f866d073c52c154aafd', 4, '2020-07-06 19:19:49'),
	(21, 'fd509766e53f', 'c6dbc1ce7085b0866ebb1bb281a80ae82a7a9a498a8c6882669bafd7b097edbb', 4, '2020-07-06 19:49:43'),
	(23, 'ee8722c2ba75', '7ad71e25c1c4731269b106319961ae3a1621b21316f2865f335cbacc485c23d7', 6, '2020-07-06 22:30:53'),
	(31, '8245b819805b', '071bcac6d3305e2f641804090e56e569eaa24cfce52acf970558a9d533aaceec', 1, '2020-07-07 15:19:57'),
	(32, 'd77e420499b4', '4cc8c4436fe8eb0c5759d2688263f549820181018c2e2d8c925d20b3e53e5d06', 1, '2020-07-07 16:33:00'),
	(33, 'adfde6d434a5', '8307e0e4eae0ecfb13377423206762e17ade1ca2d215826a03a60e54465fa563', 4, '2020-07-07 16:33:24'),
	(34, 'd34cc01164d4', '4ea500207fbf6a3aa2c6cbd71a287f112f29f71c5c785aca21fa0f1860d3fbc2', 4, '2020-07-07 16:38:17'),
	(35, 'aff0721f58c7', 'eaa44fc8446e7d8fa6daa7e2cc587c46a4241808ab050ee4a59f1600ba72daa0', 1, '2020-07-07 16:39:06'),
	(36, '979e3a21cfd7', 'bae34a39818f80fa070764a8222734dbc52c70dd2104c5da974e557516cc0cf8', 1, '2020-07-07 17:43:30'),
	(37, 'e93c7fd39892', '4f2cd692aa2d0f5eba3e839404a7dde3224e6b136080f16ec4138a847b149074', 1, '2020-07-07 17:47:30');
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;

-- Dumping structure for table planningpoker.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(300) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table planningpoker.games: ~3 rows (approximately)
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` (`id`, `topic`, `start_date`, `end_date`) VALUES
	(32, 'TEST TOPIC', '2020-06-07 16:44:40', '2020-06-07 16:45:43'),
	(33, 'sfsdfsdf', '2020-06-07 17:12:37', '2020-06-07 17:33:04'),
	(34, 'jsiusdgfiusdgf', '2020-06-07 17:47:10', NULL);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

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

-- Dumping data for table planningpoker.invites: ~0 rows (approximately)
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
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

-- Dumping data for table planningpoker.played_cards: ~4 rows (approximately)
/*!40000 ALTER TABLE `played_cards` DISABLE KEYS */;
INSERT INTO `played_cards` (`GameID`, `UserID`, `CardID`) VALUES
	(32, 4, 100),
	(32, 1, 1),
	(34, 1, 3),
	(34, 4, 100);
/*!40000 ALTER TABLE `played_cards` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
