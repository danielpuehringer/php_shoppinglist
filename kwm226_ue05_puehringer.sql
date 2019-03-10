-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jun 2018 um 17:49
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `kwm226_ue05_puehringer`
--
CREATE DATABASE IF NOT EXISTS `kwm226_ue05_puehringer` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kwm226_ue05_puehringer`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `creationDate` date NOT NULL,
  `usernameCreator` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `descr` varchar(128) DEFAULT NULL,
  `lastModDate` date DEFAULT NULL,
  `usernameLastMod` varchar(32) DEFAULT NULL,
  `state` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `product`
--

INSERT INTO `product` (`id`, `creationDate`, `usernameCreator`, `title`, `descr`, `lastModDate`, `usernameLastMod`, `state`) VALUES
(329, '2018-05-05', 'tutor', 'FrischLuft Duftspray', 'Item hat keinen Beschreibungstext', '2018-05-05', 'dani', 1),
(330, '2018-05-10', 'dani', 'Choco Cookies', 'Item hat keinen Beschreibungstext', '2018-06-20', 'tutor', 0),
(331, '2012-05-10', 'stef', 'Fish', 'Item hat keinen Beschreibungstext', '2018-04-25', 'stef', 1),
(332, '2017-07-10', 'dani', 'Ben&JerryÂ´s', 'Item hat keinen Beschreibungstext', '2014-12-26', 'tutor', 1),
(333, '2016-07-10', 'tutor', 'Popcorn', 'Item hat keinen Beschreibungstext', '2018-06-18', 'tutor', 1),
(334, '2018-02-10', 'stef', 'GemÃ¼se', 'Item hat keinen Beschreibungstext', '2018-06-18', 'tutor', 1),
(335, '2017-04-05', 'tutor', 'Pizza', 'Item hat keinen Beschreibungstext', '2018-06-18', 'tutor', 0),
(336, '2017-06-15', 'arnold', 'Schallplatten', 'Item hat keinen Beschreibungstext', '2018-06-18', 'arnold', 0),
(337, '2017-10-12', 'tutor', 'Falco CD', 'Item hat keinen Beschreibungstext', '2018-06-18', 'arnold', 0),
(338, '2018-01-03', 'arnold', 'Swiffers', 'Item hat keinen Beschreibungstext', '2018-06-18', 'tutor', 1),
(339, '2018-06-18', 'dani', 'Geschenk', 'fÃ¼r mein bestn spezl', '2018-06-18', 'arnold', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `userpassword` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`username`, `userpassword`) VALUES
('arnold', '$2y$10$2I6QeGchjCddksXUYAtp4e4bmXC17gbvORJ5oQvQzD3fCiiOnAFGK'),
('dani', '$2y$10$7OUAZQUqM9oXubUazigW8.1JJrsC2x19a.WaBIxnVsMk2kxfiYgby'),
('mrCool', '$2y$10$.mb.nEJz8lrYhHWMjt9GxezcpnqVq4eULNEsqxRGG8zWbvTiZ3C.G'),
('Stef', '$2y$10$a9QDiaHnfhYEcWlwB5yJUOYb/voXARcJaFo/0n7FIPbTaVskW65ZW'),
('tutor', '$2y$10$pwxqWlid3mINxenhD.qx4O6AlAHrNkBxnoH0jXsIGvQdP4dUg3btS');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_task`
--

CREATE TABLE `user_task` (
  `username` varchar(32) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_task`
--

INSERT INTO `user_task` (`username`, `id`) VALUES
('arnold', 336),
('arnold', 337),
('arnold', 338),
('arnold', 339),
('dani', 330),
('dani', 332),
('dani', 336),
('dani', 339),
('mrCool', 339),
('stef', 331),
('stef', 334),
('tutor', 329),
('tutor', 333),
('tutor', 335),
('tutor', 337);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_product_creator` (`usernameCreator`),
  ADD KEY `FK_user_product_lastModUser` (`usernameLastMod`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- Indizes für die Tabelle `user_task`
--
ALTER TABLE `user_task`
  ADD PRIMARY KEY (`username`,`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_user_product_creator` FOREIGN KEY (`usernameCreator`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_user_product_lastModUser` FOREIGN KEY (`usernameLastMod`) REFERENCES `user` (`username`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints der Tabelle `user_task`
--
ALTER TABLE `user_task`
  ADD CONSTRAINT `user_task_ibfk_1` FOREIGN KEY (`id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_task_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
