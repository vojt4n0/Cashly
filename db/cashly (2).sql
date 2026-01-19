-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 19. led 2026, 22:17
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `cashly`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazev` varchar(100) NOT NULL,
  `ikona` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazev`, `ikona`) VALUES
(1, 'Jídlo', 'fa-utensils'),
(2, 'Bydlení', 'fa-home'),
(3, 'Doprava', 'fa-bus'),
(4, 'Zábava', 'fa-gamepad'),
(5, 'Nákupy', 'fa-shopping-cart'),
(6, 'Mzda', 'fa-wallet'),
(7, 'Ostatní', 'fa-question');

-- --------------------------------------------------------

--
-- Struktura tabulky `rozpocty`
--

CREATE TABLE `rozpocty` (
  `id` int(11) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `mesic` int(11) NOT NULL,
  `rok` int(11) NOT NULL,
  `castka` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `transakce`
--

CREATE TABLE `transakce` (
  `id` int(11) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `castka` decimal(10,2) DEFAULT NULL,
  `typ` enum('prijem','vydaj') NOT NULL,
  `kategorie_id` int(11) DEFAULT NULL,
  `mena` varchar(10) DEFAULT 'CZK',
  `datum` date NOT NULL,
  `popis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `transakce`
--

INSERT INTO `transakce` (`id`, `uzivatel_id`, `castka`, `typ`, `kategorie_id`, `mena`, `datum`, `popis`) VALUES
(1, 24, 5000.00, 'vydaj', 1, 'CZK', '2025-11-13', 'mcdonald'),
(2, 24, 2000.00, 'prijem', 6, 'CZK', '2025-12-07', ''),
(3, 25, 123.00, 'prijem', 6, 'CZK', '2025-12-10', 'prace'),
(4, 24, 258.00, 'vydaj', 3, 'CZK', '2025-12-10', ''),
(5, 24, 358.00, 'prijem', 2, 'CZK', '2025-12-12', ''),
(6, 24, 321.00, 'vydaj', 1, 'CZK', '2025-12-12', ''),
(7, 24, 556.00, 'prijem', 7, 'CZK', '2025-12-12', ''),
(8, 24, 1358.00, 'prijem', 6, 'CZK', '2025-12-12', 'mzda'),
(9, 24, 558.00, 'vydaj', 3, 'CZK', '2025-12-12', ''),
(10, 24, 32.00, 'vydaj', 2, 'CZK', '2025-12-12', ''),
(12, 24, 38.00, 'prijem', 1, 'CZK', '2025-12-12', ''),
(14, 24, 34.00, 'vydaj', 2, 'CZK', '2025-12-12', ''),
(16, 24, 34.00, 'vydaj', 3, 'CZK', '2025-12-12', ''),
(17, 24, 543.00, 'prijem', 7, 'CZK', '2025-12-12', ''),
(18, 24, 32.00, 'prijem', 7, 'CZK', '2025-12-12', ''),
(19, 24, 234.00, 'vydaj', 3, 'CZK', '2025-12-12', ''),
(20, 24, 324.00, 'vydaj', 1, 'CZK', '2025-12-12', ''),
(21, 24, 23.00, 'vydaj', 3, 'CZK', '2025-12-12', ''),
(22, 24, 234.00, 'vydaj', 1, 'CZK', '2025-12-12', ''),
(23, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(24, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(25, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(26, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(27, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(28, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(29, 24, 32.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(30, 24, 23.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(31, 24, 23.00, 'vydaj', 3, 'CZK', '2025-12-13', ''),
(32, 24, 23.00, 'prijem', 7, 'CZK', '2025-12-13', ''),
(33, 24, 23.00, 'vydaj', 5, 'CZK', '2025-12-13', ''),
(34, 24, 234.00, 'prijem', 4, 'CZK', '2025-12-13', ''),
(35, 24, 23.00, 'vydaj', NULL, 'CZK', '2025-12-13', ''),
(36, 24, 23.00, 'vydaj', NULL, 'CZK', '2025-12-13', ''),
(37, 24, 2332.00, 'prijem', 7, 'CZK', '2025-12-13', 'darek'),
(38, 24, 323.00, 'vydaj', 5, 'CZK', '2025-12-13', ''),
(39, 24, 323.00, 'vydaj', 5, 'CZK', '2025-12-13', ''),
(40, 24, 233.00, 'prijem', 6, 'CZK', '2025-12-13', ''),
(41, 24, 1000.00, 'prijem', NULL, 'CZK', '2025-12-13', ''),
(42, 24, 15.00, 'vydaj', 4, 'CZK', '2025-12-13', ''),
(53, 24, 450.00, 'vydaj', 3, 'CZK', '2026-01-11', 'jizdenka'),
(55, 24, 145.00, 'prijem', 3, 'CZK', '2026-01-15', 'praha'),
(56, 24, 100.00, 'vydaj', 1, 'CZK', '2026-01-16', 'popiss'),
(57, 27, 232.00, 'vydaj', 6, 'CZK', '2026-01-18', 'prvni transakce'),
(58, 27, 323.00, 'prijem', 4, 'CZK', '2026-01-18', ''),
(59, 24, 0.50, 'prijem', 2, 'CZK', '2026-01-19', ''),
(60, 24, 0.50, 'vydaj', NULL, 'CZK', '2026-01-19', '');

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `heslo` varchar(255) NOT NULL,
  `vytvoreno` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `jmeno`, `email`, `heslo`, `vytvoreno`) VALUES
(24, 'Vojta', 'vojta08.hladik@seznam.cz', '$2y$10$p8V15Tww/cSsLPDD1YPRduUklD57jv1YLZbvRyZGLkOXv..zDfREy', '2025-12-06 17:11:53'),
(25, 'vojta', 'ahoj@seznam.cz', '$2y$10$wO7JXH/fkHV.JElb7ujN7unOtQQAAyJZ5zK/HlGz1OUYgSq0QKNNW', '2025-12-09 16:52:41'),
(26, 'nnnn', 'nnnn@seznam.cz', '$2y$10$C6sOtS/tjBZKWWLiWAUh4.PXcFWQRx2liDmJ1j90zMMPHITRIPFFO', '2026-01-12 22:51:24'),
(27, 'Vojtěch', 'Vojtech@seznam.cz', '$2y$10$Gh5NAHQ1dF7.9bkgwkaG/.bDXXZKimMzWO6lW9zdhZCg7yGLUzfFW', '2026-01-18 18:57:51');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `rozpocty`
--
ALTER TABLE `rozpocty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzivatel_id` (`uzivatel_id`);

--
-- Indexy pro tabulku `transakce`
--
ALTER TABLE `transakce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzivatel_id` (`uzivatel_id`),
  ADD KEY `kategorie_id` (`kategorie_id`);

--
-- Indexy pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pro tabulku `rozpocty`
--
ALTER TABLE `rozpocty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `transakce`
--
ALTER TABLE `transakce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `rozpocty`
--
ALTER TABLE `rozpocty`
  ADD CONSTRAINT `rozpocty_ibfk_1` FOREIGN KEY (`uzivatel_id`) REFERENCES `uzivatele` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `transakce`
--
ALTER TABLE `transakce`
  ADD CONSTRAINT `transakce_ibfk_1` FOREIGN KEY (`uzivatel_id`) REFERENCES `uzivatele` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transakce_ibfk_2` FOREIGN KEY (`kategorie_id`) REFERENCES `kategorie` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
