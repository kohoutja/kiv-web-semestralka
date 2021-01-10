-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 10. led 2021, 01:58
-- Verze serveru: 10.4.17-MariaDB
-- Verze PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `semestralka`
--
CREATE DATABASE IF NOT EXISTS `semestralka` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `semestralka`;

-- --------------------------------------------------------

--
-- Struktura tabulky `assigned_reviewer`
--

CREATE TABLE `assigned_reviewer` (
  `id` int(11) NOT NULL,
  `reviewer` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `reviewed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `assigned_reviewer`
--

INSERT INTO `assigned_reviewer` (`id`, `reviewer`, `post`, `reviewed`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 5, 1, 1),
(4, 1, 2, 1),
(5, 2, 2, 1),
(6, 5, 2, 1),
(7, 1, 3, 0),
(8, 5, 3, 0),
(9, 2, 3, 0),
(10, 1, 5, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(2048) COLLATE utf8_czech_ci NOT NULL,
  `file` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `published` tinyint(2) NOT NULL DEFAULT 0,
  `state` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `post`
--

INSERT INTO `post` (`id`, `author`, `title`, `description`, `file`, `published`, `state`) VALUES
(1, 2, 'První pokusný příspěvek', 'První pokusný příspěvek. Měl by být publikovaný a bez přidaného souboru.', '', 0, 3),
(2, 3, 'Druhý pokusný příspěvek', 'Druhý pokusný příspěvek. Měl by být publikovaný a bez přidaného souboru.', '', 0, 3),
(3, 3, 'Můj první příspěvek', 'Toto je můj nový příspěvek na této knižní konferenci. Snad se bude líbit. Dám do toho, co půjde. A hlavně přidám také nějakou přílohu, aby to nebylo jen takové prosté.', '6.+Php+3+-+MVC,+sablony.pdf', 0, 2),
(4, 3, 'Tak a jedem dál', 'Přátelé, kamarádi, my vám ti medaile přivezeme. Přidávám prezentaci z přednášek KIV/WEB.', '2.+CSS+1+++git.pdf', 0, 1),
(5, 4, 'Tak a jdem na to, tady je můj první příspěvek', 'Můj první příspěvek nebude určitě tak dobrý, jako příspěvky ostatních. Ale co už, snad se budu zlepšovat. Jako přílohu zase přidávám nějakou přednášku z KIV/WEB.', '4.+Php+1+-+úvod+do+php.pdf', 0, 1),
(6, 4, 'Další příspěvek', 'Jdeme na další příspěvek, jsem nadšen jaké ohlasy měl můj první. Díky za ně.', 'doc.pdf', 0, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `reviewer` int(11) NOT NULL,
  `criterion1` int(11) NOT NULL,
  `criterion2` int(11) NOT NULL,
  `criterion3` int(11) NOT NULL,
  `overall` int(11) NOT NULL,
  `text` varchar(1024) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `review`
--

INSERT INTO `review` (`id`, `post`, `reviewer`, `criterion1`, `criterion2`, `criterion3`, `overall`, `text`) VALUES
(1, 1, 1, 2, 3, 5, 3, 'Recenze příspěvku od Franty Vokurky provedena administrátorem.'),
(2, 2, 1, 1, 2, 3, 2, 'Recenze provedena uživatelem admin.'),
(3, 1, 2, 5, 4, 3, 4, 'Tak tento příspěvek je opravdu velmi špatný.'),
(4, 2, 2, 2, 3, 3, 3, 'Tento příspěvek je průměrný hodnotím za 3. Franta Vokurka.'),
(5, 1, 5, 1, 1, 1, 1, 'Tak to je naprostá bomba. Takovou úroveň jsem opravdu nečekal. :D'),
(6, 2, 5, 5, 5, 5, 5, 'Tak tento příspěvek nemůže jeho autor Nový Autor myslet naprosto vážně. Stojí to za starou bačkoru.');

-- --------------------------------------------------------

--
-- Struktura tabulky `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(128) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'reviewer'),
(3, 'author');

-- --------------------------------------------------------

--
-- Struktura tabulky `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `state`
--

INSERT INTO `state` (`id`, `description`) VALUES
(1, 'Čekání na přiřazení recenzentů'),
(2, 'Čekání na recenze'),
(3, 'Čekání na rozhodnutí správce'),
(4, 'Publikováno'),
(5, 'Zamítnuto');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `username` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `fullName` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `fullName`, `role`) VALUES
(1, 'admin@admin.cz', 'admin', 'admin', 'admin', 1),
(2, 'franta.vokurka@email.cz', 'reviewer1', 'reviewer1', 'Franta Vokurka', 2),
(3, 'novy.author@email.cz', 'newauthor', 'newauthor', 'Nový Autor', 3),
(4, 'novy.author2@email.cz', 'newauthor2', 'newauthor2', 'Nový Autor2', 3),
(5, 'novy.recenzent@email.cz', 'newreviewer', 'newreviewer', 'Nový Recenzent', 2);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `assigned_reviewer`
--
ALTER TABLE `assigned_reviewer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewer` (`reviewer`),
  ADD KEY `post` (`post`);

--
-- Klíče pro tabulku `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`),
  ADD KEY `state` (`state`);

--
-- Klíče pro tabulku `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewer_key` (`reviewer`),
  ADD KEY `post_key` (`post`);

--
-- Klíče pro tabulku `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `assigned_reviewer`
--
ALTER TABLE `assigned_reviewer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `assigned_reviewer`
--
ALTER TABLE `assigned_reviewer`
  ADD CONSTRAINT `post` FOREIGN KEY (`post`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `reviewer` FOREIGN KEY (`reviewer`) REFERENCES `user` (`id`);

--
-- Omezení pro tabulku `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`state`) REFERENCES `state` (`id`);

--
-- Omezení pro tabulku `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`post`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`reviewer`) REFERENCES `user` (`id`);

--
-- Omezení pro tabulku `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
