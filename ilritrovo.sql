-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 08, 2025 alle 12:03
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ilritrovo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `creditcard`
--

CREATE TABLE `creditcard` (
  `idCreditCard` int(11) NOT NULL,
  `number` varchar(16) NOT NULL,
  `cvv` int(3) NOT NULL,
  `expiration` varchar(7) NOT NULL,
  `holder` varchar(128) NOT NULL,
  `type` varchar(16) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `creditcard`
--
DELIMITER $$
CREATE TRIGGER `typeCreditCard` BEFORE INSERT ON `creditcard` FOR EACH ROW BEGIN
    IF LOWER(NEW.type) NOT IN ('visa', 'mastercard', 'american express', 'maestro', 'v-pay', 'pagobancomat') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid credit card type';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `extra`
--

CREATE TABLE `extra` (
  `idExtra` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `price` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `extrainreservation`
--

CREATE TABLE `extrainreservation` (
  `idExtra` int(11) NOT NULL,
  `idReservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `image_user`
--

CREATE TABLE `image_user` (
  `idImage` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `imageURL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `payment`
--

CREATE TABLE `payment` (
  `idPayment` int(11) NOT NULL,
  `total` int(4) NOT NULL,
  `creationTime` date NOT NULL DEFAULT current_timestamp(),
  `state` enum('pending','completed','failed','canceled') NOT NULL,
  `idCreditCard` int(11) NOT NULL,
  `idReservation` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reply`
--

CREATE TABLE `reply` (
  `idReply` int(11) NOT NULL,
  `dateReply` date NOT NULL,
  `body` varchar(512) NOT NULL,
  `idReview` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reservation`
--

CREATE TABLE `reservation` (
  `idReservation` int(11) NOT NULL,
  `timeFrame` varchar(7) NOT NULL,
  `reservationDate` datetime NOT NULL,
  `comment` varchar(256) NOT NULL,
  `people` int(3) NOT NULL,
  `price` int(4) NOT NULL,
  `state` varchar(16) NOT NULL,
  `operationDate` date NOT NULL DEFAULT current_timestamp(),
  `idUser` int(11) NOT NULL,
  `idRoom` int(11) NOT NULL,
  `idTable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `review`
--

CREATE TABLE `review` (
  `idReview` int(11) NOT NULL,
  `stars` int(1) NOT NULL,
  `dateReview` date NOT NULL,
  `body` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `review`
--
DELIMITER $$
CREATE TRIGGER `starsReview` BEFORE INSERT ON `review` FOR EACH ROW BEGIN
  IF NEW.stars NOT IN (1, 2, 3, 4, 5) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid number of stars';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `room`
--

CREATE TABLE `room` (
  `idRoom` int(11) NOT NULL,
  `areaName` varchar(64) NOT NULL,
  `maxGuests` int(3) NOT NULL,
  `tax` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `table`
--

CREATE TABLE `table` (
  `idTable` int(11) NOT NULL,
  `areaName` varchar(64) NOT NULL,
  `maxGuests` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `birthDate` date NOT NULL,
  `phone` int(10) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `ban` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`idCreditCard`),
  ADD UNIQUE KEY `number` (`number`,`idUser`) USING BTREE,
  ADD UNIQUE KEY `number_2` (`number`),
  ADD KEY `idUser` (`idUser`);

--
-- Indici per le tabelle `extra`
--
ALTER TABLE `extra`
  ADD PRIMARY KEY (`idExtra`);

--
-- Indici per le tabelle `extrainreservation`
--
ALTER TABLE `extrainreservation`
  ADD UNIQUE KEY `idExtra` (`idExtra`),
  ADD UNIQUE KEY `idReservation` (`idReservation`);

--
-- Indici per le tabelle `image_user`
--
ALTER TABLE `image_user`
  ADD PRIMARY KEY (`idImage`),
  ADD KEY `idUser` (`idUser`);

--
-- Indici per le tabelle `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`idPayment`),
  ADD KEY `idCreditCard` (`idCreditCard`),
  ADD KEY `idRservation` (`idReservation`);

--
-- Indici per le tabelle `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`idReply`),
  ADD UNIQUE KEY `idReiew` (`idReview`);

--
-- Indici per le tabelle `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`idReservation`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idRoom` (`idRoom`),
  ADD KEY `idEvent` (`idTable`);

--
-- Indici per le tabelle `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`idReview`);

--
-- Indici per le tabelle `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`idRoom`);

--
-- Indici per le tabelle `table`
--
ALTER TABLE `table`
  ADD PRIMARY KEY (`idTable`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `creditcard`
--
ALTER TABLE `creditcard`
  MODIFY `idCreditCard` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `extra`
--
ALTER TABLE `extra`
  MODIFY `idExtra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `image_user`
--
ALTER TABLE `image_user`
  MODIFY `idImage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `payment`
--
ALTER TABLE `payment`
  MODIFY `idPayment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reply`
--
ALTER TABLE `reply`
  MODIFY `idReply` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reservation`
--
ALTER TABLE `reservation`
  MODIFY `idReservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `review`
--
ALTER TABLE `review`
  MODIFY `idReview` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `room`
--
ALTER TABLE `room`
  MODIFY `idRoom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `table`
--
ALTER TABLE `table`
  MODIFY `idTable` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `image_user`
--
ALTER TABLE `image_user`
  ADD CONSTRAINT `image_user_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `idRservation` FOREIGN KEY (`idReservation`) REFERENCES `reservation` (`idReservation`),
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`idCreditCard`) REFERENCES `creditcard` (`idCreditCard`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`idRoom`) REFERENCES `room` (`idRoom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`idTable`) REFERENCES `event` (`idEvent`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
