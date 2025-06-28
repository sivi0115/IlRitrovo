-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 28, 2025 alle 12:32
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ilRitrovo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `creditcard`
--

CREATE TABLE `creditcard` (
  `idCreditCard` int(11) NOT NULL,
  `holder` varchar(128) NOT NULL,
  `number` varchar(19) NOT NULL,
  `cvv` int(3) NOT NULL,
  `expiration` date NOT NULL,
  `type` varchar(16) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `creditcard`
--

INSERT INTO `creditcard` (`idCreditCard`, `holder`, `number`, `cvv`, `expiration`, `type`, `idUser`) VALUES
(8, 'Marco Cipriani', '5167930012347891', 511, '2026-02-10', 'Visa', 1),
(15, 'Mr Kittyo ', '0192837465019283', 911, '2026-10-10', 'Visa', 1),
(18, 'Roberto Cipriani', '1234567890123456', 911, '2026-10-10', 'Mastercard', 34);

--
-- Trigger `creditcard`
--
DELIMITER $$
CREATE TRIGGER `typeCreditCard` BEFORE INSERT ON `creditcard` FOR EACH ROW BEGIN
    IF LOWER(NEW.type) NOT IN ('Visa', 'Mastercard', 'American Express', 'Maestro', 'V-Pay', 'PagoBANCOMAT') THEN
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
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `extra`
--

INSERT INTO `extra` (`idExtra`, `name`, `price`) VALUES
(1, 'Small flower bouquet', 20),
(2, 'Large flower bouquet', 50),
(3, 'Floral decoration for the room', 100),
(4, 'Balloon centerpiece', 15),
(5, 'Balloon decoration for the room', 40),
(6, 'Live music', 150),
(7, 'Private bartender', 70),
(8, 'Children’s entertainment', 70),
(9, 'Cake (up to 20 people)', 30),
(10, 'Cake (up to 50 people)', 60),
(11, 'Cake (up to 100 people)', 120),
(12, 'Projector rental', 10),
(13, 'Place cards', 25),
(15, 'Fiori Bianchi', 300);

-- --------------------------------------------------------

--
-- Struttura della tabella `extrainreservation`
--

CREATE TABLE `extrainreservation` (
  `idExtra` int(11) NOT NULL,
  `idReservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `extrainreservation`
--

INSERT INTO `extrainreservation` (`idExtra`, `idReservation`) VALUES
(1, 39),
(1, 41);

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

--
-- Dump dei dati per la tabella `payment`
--

INSERT INTO `payment` (`idPayment`, `total`, `creationTime`, `state`, `idCreditCard`, `idReservation`) VALUES
(4, 120, '2025-06-27', 'completed', 18, 41);

-- --------------------------------------------------------

--
-- Struttura della tabella `reply`
--

CREATE TABLE `reply` (
  `idReply` int(11) NOT NULL,
  `dateReply` date NOT NULL,
  `body` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `reply`
--

INSERT INTO `reply` (`idReply`, `dateReply`, `body`) VALUES
(7, '2025-06-28', 'grazie mieow');

-- --------------------------------------------------------

--
-- Struttura della tabella `reservation`
--

CREATE TABLE `reservation` (
  `idReservation` int(11) NOT NULL,
  `timeFrame` enum('lunch','dinner') NOT NULL,
  `reservationDate` date NOT NULL,
  `comment` varchar(256) NOT NULL,
  `people` int(3) NOT NULL,
  `totPrice` int(4) NOT NULL,
  `state` varchar(16) NOT NULL,
  `creationTime` date NOT NULL DEFAULT current_timestamp(),
  `idUser` int(11) NOT NULL,
  `idRoom` int(11) DEFAULT NULL,
  `idTable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `reservation`
--

INSERT INTO `reservation` (`idReservation`, `timeFrame`, `reservationDate`, `comment`, `people`, `totPrice`, `state`, `creationTime`, `idUser`, `idRoom`, `idTable`) VALUES
(36, 'dinner', '2025-07-23', 'Allergia a funghi', 4, 0, 'confirmed', '2025-06-25', 1, NULL, 14),
(37, 'lunch', '2026-01-30', 'allergie varie a funghi', 10, 0, 'confirmed', '2025-06-26', 33, NULL, 13),
(40, 'dinner', '2025-08-15', 'Si fa presente che una portata non deve contenere Glutine\r\n(Celiachia)', 5, 0, 'confirmed', '2025-06-27', 34, NULL, 1),
(41, 'lunch', '2025-08-14', 'Commenti', 100, 120, 'approved', '2025-06-27', 34, 2, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `review`
--

CREATE TABLE `review` (
  `idUser` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `stars` int(1) NOT NULL,
  `creationTime` date NOT NULL,
  `body` varchar(512) NOT NULL,
  `idReply` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `review`
--

INSERT INTO `review` (`idUser`, `idReview`, `stars`, `creationTime`, `body`, `idReply`) VALUES
(1, 27, 4, '2025-06-28', 'ciao w i gattioes', 7);

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
  `tax` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `room`
--

INSERT INTO `room` (`idRoom`, `areaName`, `maxGuests`, `tax`) VALUES
(1, 'The Rustic Retreat', 20, 20),
(2, 'The Grand Hall', 100, 100),
(3, 'The Wine Cellar', 50, 50);

-- --------------------------------------------------------

--
-- Struttura della tabella `tables`
--

CREATE TABLE `tables` (
  `idTable` int(11) NOT NULL,
  `areaName` varchar(64) NOT NULL,
  `maxGuests` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tables`
--

INSERT INTO `tables` (`idTable`, `areaName`, `maxGuests`) VALUES
(1, 'Table 1', 6),
(2, 'Table 2', 3),
(3, 'Table 3', 4),
(4, 'Table 4', 4),
(5, 'Table 5', 4),
(6, 'Table 6', 10),
(7, 'Table 7', 4),
(8, 'Table 8', 4),
(9, 'Table 9', 4),
(10, 'Table 10', 4),
(11, 'Table 11', 4),
(12, 'Table 12', 5),
(13, 'Table 13', 10),
(14, 'Table 14', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `idReview` int(11) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `birthDate` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ban` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`idUser`, `idReview`, `username`, `name`, `surname`, `birthDate`, `phone`, `image`, `role`, `email`, `password`, `ban`) VALUES
(1, 1, 'glaceon00', 'Giovanni', 'Rossi', '2001-01-03', '3407698123', 'https://example.com/image.jpg', 'user', 'glaceooon00@gmail.com', '$2y$10$/.WVhJ7zhz8kkH4j6beh4.VPymJVe/XRTPIhogu9MB1d81VnTrSi2', 0),
(2, NULL, 'admin1', 'Mario', 'Rossi', '2025-06-22', '3409876432', NULL, 'admin', 'marioRossi@gmail.com', '$2y$10$zJYtfuBxaE3b8BodCr4VfulCKwPix09bakPsg15FnFBKKoVNI4eR2', 0),
(33, NULL, 'flareon00', 'Lorenzo', 'Bianchi', '2001-02-01', '3408976543', NULL, 'user', 'lorenzoBianchi@gmail.com', '$2y$10$FAOO6GPdTUn2CUJo6FUzDerBDgQfkM6FG4jhI5fK8/21TwkhT8r3y', 0),
(34, NULL, 'roby50', 'Roberto', 'Cipriani', '1958-08-04', '3409876123', NULL, 'user', 'robertoCipriani@gmail.com', '$2y$10$XnnumRmW2uVqL5tTvU.UauDCPeDL7NW06.0ILZX0UF2QS9PvFsrEq', 0);

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
  ADD PRIMARY KEY (`idReply`);

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
  ADD PRIMARY KEY (`idReview`),
  ADD UNIQUE KEY `idReply` (`idReply`);

--
-- Indici per le tabelle `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`idRoom`);

--
-- Indici per le tabelle `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`idTable`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `creditcard`
--
ALTER TABLE `creditcard`
  MODIFY `idCreditCard` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `extra`
--
ALTER TABLE `extra`
  MODIFY `idExtra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `image_user`
--
ALTER TABLE `image_user`
  MODIFY `idImage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `payment`
--
ALTER TABLE `payment`
  MODIFY `idPayment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `reply`
--
ALTER TABLE `reply`
  MODIFY `idReply` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `reservation`
--
ALTER TABLE `reservation`
  MODIFY `idReservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT per la tabella `review`
--
ALTER TABLE `review`
  MODIFY `idReview` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `room`
--
ALTER TABLE `room`
  MODIFY `idRoom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `tables`
--
ALTER TABLE `tables`
  MODIFY `idTable` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
