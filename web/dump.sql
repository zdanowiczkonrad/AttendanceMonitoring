-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 03, 2012 at 02:47 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inzynier`
--

-- --------------------------------------------------------

--
-- Table structure for table `bbbzajecia_prowadzacych`
--

CREATE TABLE IF NOT EXISTS `bbbzajecia_prowadzacych` (
  `IdProwadzacy` mediumint(5) unsigned NOT NULL,
  `IdZajecia` mediumint(5) unsigned NOT NULL,
  KEY `IdProwadzacy` (`IdProwadzacy`),
  KEY `IdZajecia` (`IdZajecia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `bbbzajecia_prowadzacych`
--

INSERT INTO `bbbzajecia_prowadzacych` (`IdProwadzacy`, `IdZajecia`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `formy_kursow`
--

CREATE TABLE IF NOT EXISTS `formy_kursow` (
  `IdFormy` smallint(3) NOT NULL AUTO_INCREMENT,
  `NazwaFormy` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`IdFormy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `formy_kursow`
--

INSERT INTO `formy_kursow` (`IdFormy`, `NazwaFormy`) VALUES
(1, 'Wyk³ad'),
(2, 'Æwiczenia'),
(3, 'Laboratorium'),
(4, 'Projekt'),
(5, 'Seminarium'),
(6, 'Inne');

-- --------------------------------------------------------

--
-- Table structure for table `grupy_ocen`
--

CREATE TABLE IF NOT EXISTS `grupy_ocen` (
  `IdGrupyOcen` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `NazwaGrupy` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `WagaOceny` smallint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdGrupyOcen`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `grupy_ocen`
--

INSERT INTO `grupy_ocen` (`IdGrupyOcen`, `NazwaGrupy`, `WagaOceny`) VALUES
(1, 'Kolokwium', 10),
(2, 'Wej', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kursy`
--

CREATE TABLE IF NOT EXISTS `kursy` (
  `KodKursu` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `NazwaKursu` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `IdFormy` smallint(3) unsigned NOT NULL,
  `ECTS` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`KodKursu`),
  KEY `IdFormy` (`IdFormy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `kursy`
--

INSERT INTO `kursy` (`KodKursu`, `NazwaKursu`, `IdFormy`, `ECTS`) VALUES
('ARES00509W', 'Inteligentne budynki', 1, 2),
('ARES00508W', 'Technologie WWW', 1, 2),
('ARES00409S', 'Seminarium dyplomowe', 5, 2),
('ARES00506P', 'Projekt specjalnoœciowy ART', 4, 2),
('ARES00507P', 'Sieci neuronowe i neurosterowniki', 4, 2),
('ARES00510P', 'E-media', 4, 2),
('ARES00510W', 'E-media', 1, 2),
('ZMZ000340W', 'Podstawy zarz¹dzania jakoœci¹', 1, 2),
('TEST00001', 'Kurs testowy', 6, 24);

-- --------------------------------------------------------

--
-- Table structure for table `obecnosci`
--

CREATE TABLE IF NOT EXISTS `obecnosci` (
  `IdTermin` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `NrIndeksu` mediumint(7) unsigned NOT NULL,
  `Typ` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `DataObecnosci` datetime DEFAULT NULL,
  KEY `IdTermin` (`IdTermin`),
  KEY `NrIndeksu` (`NrIndeksu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=57 ;

--
-- Dumping data for table `obecnosci`
--

INSERT INTO `obecnosci` (`IdTermin`, `NrIndeksu`, `Typ`, `DataObecnosci`) VALUES
(52, 180001, 2, '2012-11-02 23:40:32'),
(50, 180001, 2, '2012-11-01 00:00:00'),
(51, 180001, 1, '2012-11-01 10:25:24'),
(49, 180001, 1, '2012-11-01 07:20:20'),
(49, 180000, 1, '2012-11-02 05:15:16'),
(52, 180000, 1, '2012-11-03 02:38:37'),
(56, 180394, 1, '2012-11-03 02:11:21'),
(56, 180001, 1, '2012-11-03 02:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `oceny`
--

CREATE TABLE IF NOT EXISTS `oceny` (
  `IdOceny` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `Wartosc` decimal(3,2) unsigned NOT NULL,
  `IdGrupyOcen` mediumint(9) unsigned DEFAULT NULL,
  `Opis` varchar(256) COLLATE utf8_polish_ci DEFAULT NULL,
  `IdZajecia` int(20) unsigned NOT NULL,
  `NrIndeksu` mediumint(7) unsigned NOT NULL,
  `IdTermin` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`IdOceny`),
  KEY `IdZajecia` (`IdZajecia`),
  KEY `NrIndeksu` (`NrIndeksu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `prowadzacy`
--

CREATE TABLE IF NOT EXISTS `prowadzacy` (
  `IdProwadzacy` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Imie` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `Nazwisko` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `Tytul` varchar(24) COLLATE utf8_polish_ci NOT NULL,
  `Uid` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`IdProwadzacy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `prowadzacy`
--

INSERT INTO `prowadzacy` (`IdProwadzacy`, `Imie`, `Nazwisko`, `Tytul`, `Uid`) VALUES
(14, 'Mateusz', 'Gorczyca', 'Dr in¿.', '14'),
(12, 'Ewa', 'Skubalska-Rafaj³owicz', 'Dr hab. in¿.', '10'),
(13, 'Ewaryst', 'Rafaj³owicz', 'Prof.zw. dr hab.', '13'),
(15, 'Andrzej', 'Rusiecki', 'Dr in¿.', '16'),
(16, ' Krzysztof', 'Halawa', 'Dr in¿.', '17'),
(17, 'Piotr', 'Ciskowski', 'Dr in¿.', '18'),
(18, 'Tomasz', 'Krysiak', 'Dr in¿.', '19'),
(19, 'Andrzej', 'Jab³oñski', 'Dr in¿.', '21'),
(20, 'Wojciech', 'Bo¿ejko', 'Dr hab.', '2063065954');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `IdSali` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Sala` varchar(8) NOT NULL,
  `Budynek` varchar(4) NOT NULL,
  PRIMARY KEY (`IdSali`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`IdSali`, `Sala`, `Budynek`) VALUES
(5, '114', 'D-20'),
(6, '115', 'D-20'),
(7, '208', 'C-3'),
(8, '305', 'C-3'),
(9, '319', 'C-3'),
(10, '21', 'C-3'),
(11, '019', 'C-3'),
(12, '215', 'C-3'),
(13, '201', 'C-1');

-- --------------------------------------------------------

--
-- Table structure for table `studenci`
--

CREATE TABLE IF NOT EXISTS `studenci` (
  `NrIndeksu` mediumint(7) unsigned NOT NULL,
  `Imie` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `Nazwisko` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `Uid` varchar(25) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`NrIndeksu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `studenci`
--

INSERT INTO `studenci` (`NrIndeksu`, `Imie`, `Nazwisko`, `Uid`) VALUES
(180394, 'Konrad', 'Zdanowicz', '2333014408'),
(180001, 'Piotrek', 'Nowakowski', '1247245666'),
(180000, 'Jan', 'Kowalski', '12345678');

-- --------------------------------------------------------

--
-- Table structure for table `terminy`
--

CREATE TABLE IF NOT EXISTS `terminy` (
  `IdTermin` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Data` datetime NOT NULL,
  `IdZajecia` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`IdTermin`),
  KEY `IdZajecia` (`IdZajecia`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=57 ;

--
-- Dumping data for table `terminy`
--

INSERT INTO `terminy` (`IdTermin`, `Data`, `IdZajecia`) VALUES
(4, '2012-05-14 11:17:34', 1),
(3, '2012-05-07 11:15:25', 1),
(5, '2012-05-21 11:17:58', 1),
(31, '2012-10-09 15:55:00', 2),
(32, '2012-10-02 15:55:00', 2),
(8, '2012-05-11 13:16:26', 3),
(9, '2012-05-25 13:17:34', 3),
(10, '2012-05-03 18:55:10', 4),
(11, '2012-05-24 18:47:48', 4),
(12, '2012-05-31 18:48:56', 4),
(13, '2012-05-17 18:49:46', 4),
(14, '2012-05-10 18:51:13', 4),
(34, '2012-11-06 15:55:00', 2),
(30, '2012-10-23 15:55:00', 2),
(28, '2012-11-12 07:00:00', 14),
(33, '2012-10-30 15:55:00', 2),
(29, '2012-10-16 15:55:00', 2),
(35, '2012-11-13 15:55:00', 2),
(36, '2012-11-20 15:55:00', 2),
(37, '2012-11-27 15:55:00', 2),
(38, '2012-12-04 15:55:00', 2),
(39, '2012-12-11 15:55:00', 2),
(40, '2012-12-18 15:55:00', 2),
(41, '2012-12-25 15:55:00', 2),
(42, '2013-01-01 15:55:00', 2),
(43, '2012-10-31 15:00:00', 15),
(50, '2012-09-24 11:45:33', 35),
(49, '2012-09-22 19:20:47', 35),
(51, '2012-10-05 12:51:18', 36),
(52, '2012-10-09 16:09:52', 22),
(56, '2012-11-03 02:11:09', 37);

-- --------------------------------------------------------

--
-- Table structure for table `userzy`
--

CREATE TABLE IF NOT EXISTS `userzy` (
  `IdUser` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `IdProwadzacy` mediumint(5) unsigned DEFAULT NULL,
  `UserLogin` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `CzyAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `PassHash` varchar(256) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`IdUser`),
  UNIQUE KEY `login` (`UserLogin`),
  KEY `idProwadzacy` (`IdProwadzacy`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `userzy`
--

INSERT INTO `userzy` (`IdUser`, `IdProwadzacy`, `UserLogin`, `CzyAdmin`, `PassHash`) VALUES
(1, 1, NULL, 0, 'd41d8cd98f00b204e9800998ecf8427e'),
(2, NULL, 'admin', 1, 'd41d8cd98f00b204e9800998ecf8427e'),
(3, NULL, '12345', 0, '827ccb0eea8a706c4c34a16891f84e7b'),
(4, NULL, 'test', 0, '098f6bcd4621d373cade4e832627b4f6');

-- --------------------------------------------------------

--
-- Table structure for table `zajecia`
--

CREATE TABLE IF NOT EXISTS `zajecia` (
  `IdZajecia` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `KodGrupy` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `IdSali` mediumint(8) NOT NULL,
  `Dzien` enum('pon','wt','sr','czw','pt','so','ndz') COLLATE utf8_polish_ci NOT NULL,
  `Godzina` time NOT NULL,
  `GodzinaKoniec` time NOT NULL,
  `Tydzien` enum('T','TN','TP') COLLATE utf8_polish_ci NOT NULL,
  `KodKursu` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `IdProwadzacy` mediumint(5) NOT NULL,
  PRIMARY KEY (`IdZajecia`),
  KEY `KodKursu` (`KodKursu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=38 ;

--
-- Dumping data for table `zajecia`
--

INSERT INTO `zajecia` (`IdZajecia`, `KodGrupy`, `IdSali`, `Dzien`, `Godzina`, `GodzinaKoniec`, `Tydzien`, `KodKursu`, `IdProwadzacy`) VALUES
(22, NULL, 9, 'wt', '16:00:00', '18:15:00', 'T', 'ARES00506P', 15),
(18, NULL, 5, 'pon', '08:15:00', '11:00:00', 'T', 'ARES00409S', 12),
(19, NULL, 6, 'sr', '08:15:00', '11:00:00', 'T', 'ARES00409S', 12),
(20, NULL, 7, 'sr', '15:00:00', '17:15:00', 'T', 'ARES00409S', 12),
(21, NULL, 8, 'pon', '16:10:00', '18:45:00', 'T', 'ARES00506P', 14),
(23, NULL, 9, 'wt', '18:30:00', '20:35:00', 'T', 'ARES00506P', 15),
(24, NULL, 9, 'sr', '10:45:00', '13:00:00', 'T', 'ARES00506P', 16),
(25, NULL, 9, 'czw', '08:00:00', '10:30:00', 'TN', 'ARES00507P', 17),
(26, NULL, 9, 'czw', '10:45:00', '13:00:00', 'TN', 'ARES00507P', 17),
(27, NULL, 9, 'czw', '08:00:00', '10:30:00', 'TP', 'ARES00507P', 17),
(28, NULL, 10, 'pon', '13:15:00', '16:00:00', 'T', 'ARES00508W', 18),
(29, NULL, 10, 'wt', '10:15:00', '12:45:00', 'T', 'ARES00509W', 19),
(30, NULL, 11, 'czw', '12:00:00', '15:00:00', 'TN', 'ARES00510P', 20),
(31, NULL, 11, 'czw', '12:00:00', '15:00:00', 'TP', 'ARES00510P', 20),
(32, NULL, 12, 'czw', '15:15:00', '17:50:00', 'TP', 'ARES00510P', 20),
(33, NULL, 10, 'wt', '13:00:00', '15:30:00', 'T', 'ARES00510W', 20),
(34, NULL, 13, 'pt', '18:00:00', '20:25:00', 'T', 'ZMZ000340W', 20),
(35, NULL, 7, 'pon', '11:38:00', '20:40:00', 'T', 'ARES00510W', 13),
(36, NULL, 7, 'pt', '12:50:00', '14:00:00', 'T', 'ARES00510W', 12),
(37, NULL, 5, 'so', '00:00:00', '22:00:00', 'T', 'TEST00001', 20);

-- --------------------------------------------------------

--
-- Table structure for table `zajecia_studentow`
--

CREATE TABLE IF NOT EXISTS `zajecia_studentow` (
  `IdZajecia` mediumint(5) unsigned NOT NULL,
  `NrIndeksu` mediumint(7) unsigned NOT NULL,
  KEY `IdZajecia` (`IdZajecia`),
  KEY `NrIndeksu` (`NrIndeksu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `zajecia_studentow`
--

INSERT INTO `zajecia_studentow` (`IdZajecia`, `NrIndeksu`) VALUES
(37, 180394),
(35, 180000),
(22, 180001),
(36, 180001),
(22, 180000),
(37, 180001),
(35, 180001);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
