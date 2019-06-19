-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 19, 2019 alle 17:11
-- Versione del server: 10.1.40-MariaDB
-- Versione PHP: 7.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s265444`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
  `SeatId` varchar(255) NOT NULL,
  `Status` char(1) NOT NULL,
  `Username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `booking`
--

INSERT INTO `booking` (`SeatId`, `Status`, `Username`) VALUES
('A4', 'R', 'u1@p.it'),
('B2', 'P', 'u2@p.it'),
('B3', 'P', 'u2@p.it'),
('B4', 'P', 'u2@p.it'),
('D4', 'R', 'u1@p.it'),
('F4', 'R', 'u2@p.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`Username`, `Password`) VALUES
('u1@p.it', '$2y$10$ybWA.WXfV.Rx3Um6cfl1JujRO7chEOMmBAw8nd9xAnvSCQmjY1wcG'),
('u2@p.it', '$2y$10$kEV9qKJgomIXzPeDpJ9E5uY.gAWzWCFcd8w8PjA3b.DaqBMxWWGDO');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`SeatId`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
