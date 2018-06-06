-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:3306
-- Tid vid skapande: 06 jun 2018 kl 17:53
-- Serverversion: 10.1.23-MariaDB-9+deb9u1
-- PHP-version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `mydb`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `jordfukt`
--

CREATE TABLE `jordfukt` (
  `id` int(11) NOT NULL,
  `sensor` int(11) NOT NULL,
  `fukt` int(11) NOT NULL,
  `jordtemp` varchar(50) NOT NULL,
  `volt` varchar(50) NOT NULL,
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `jordfukt`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `jordfuktdag`
--

CREATE TABLE `jordfuktdag` (
  `id` int(11) NOT NULL,
  `sensor` int(11) NOT NULL DEFAULT '0',
  `fukt` int(11) NOT NULL DEFAULT '0',
  `jordtemp` varchar(50) NOT NULL DEFAULT '0',
  `volt` varchar(50) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `jordfuktdag`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `loggbok`
--

CREATE TABLE `loggbok` (
  `id` int(11) NOT NULL,
  `rubrik` varchar(250) NOT NULL DEFAULT '',
  `dokument` longtext NOT NULL,
  `bild` varchar(250) NOT NULL,
  `datum` date NOT NULL,
  `tid` time NOT NULL,
  `zon` int(11) NOT NULL DEFAULT '0',
  `redigerad` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `loggbok`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `luftfukt`
--

CREATE TABLE `luftfukt` (
  `id` int(11) NOT NULL,
  `temp` varchar(50) NOT NULL DEFAULT '0',
  `fukt` varchar(50) NOT NULL DEFAULT '0',
  `heat` varchar(50) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `luftfukt`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `luftfuktdag`
--

CREATE TABLE `luftfuktdag` (
  `id` int(11) NOT NULL,
  `temp` varchar(50) NOT NULL DEFAULT '0',
  `fukt` varchar(50) NOT NULL DEFAULT '0',
  `heat` varchar(50) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `luftfuktdag`
--




-- --------------------------------------------------------

--
-- Tabellstruktur `pumpar`
--

CREATE TABLE `pumpar` (
  `id` int(11) NOT NULL,
  `pumpnr` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL,
  `typ` tinyint(1) NOT NULL,
  `aktiverad` tinyint(1) NOT NULL,
  `startad` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stod` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `pumpar`
--

INSERT INTO `pumpar` (`id`, `pumpnr`, `namn`, `typ`, `aktiverad`, `startad`, `stod`) VALUES
(1, 2501, 'Waterpump', 0, 0, '2018-06-06 08:22:01', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `pumptimer`
--

CREATE TABLE `pumptimer` (
  `id` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL DEFAULT '',
  `start` time NOT NULL,
  `slut` time NOT NULL,
  `mon` tinyint(1) NOT NULL DEFAULT '0',
  `tis` tinyint(1) NOT NULL DEFAULT '0',
  `ons` tinyint(1) NOT NULL DEFAULT '0',
  `tor` tinyint(1) NOT NULL DEFAULT '0',
  `fre` tinyint(1) NOT NULL DEFAULT '0',
  `lor` tinyint(1) NOT NULL DEFAULT '0',
  `son` tinyint(1) NOT NULL DEFAULT '0',
  `pump` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `pumptimer`
--

INSERT INTO `pumptimer` (`id`, `namn`, `start`, `slut`, `mon`, `tis`, `ons`, `tor`, `fre`, `lor`, `son`, `pump`) VALUES
(17, 'Test', '10:19:00', '10:22:00', 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `regndata`
--

CREATE TABLE `regndata` (
  `id` int(11) NOT NULL,
  `regnnr` int(11) NOT NULL,
  `regnar` int(11) NOT NULL,
  `regnfukt` int(11) NOT NULL,
  `volt` varchar(50) NOT NULL,
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `regndata`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `regndatadag`
--

CREATE TABLE `regndatadag` (
  `id` int(11) NOT NULL,
  `regnnr` int(11) NOT NULL,
  `regnar` int(11) NOT NULL,
  `regnfukt` int(11) NOT NULL,
  `volt` varchar(50) NOT NULL,
  `datum` date NOT NULL,
  `tid` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `regndatadag`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `regnsensor`
--

CREATE TABLE `regnsensor` (
  `id` int(11) NOT NULL,
  `regnid` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL,
  `volt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `regnsensor`
--

INSERT INTO `regnsensor` (`id`, `regnid`, `namn`, `volt`) VALUES
(1, 1501, 'Rainsensor 1', '4.12');

-- --------------------------------------------------------

--
-- Tabellstruktur `sensorer`
--

CREATE TABLE `sensorer` (
  `id` int(11) NOT NULL,
  `sensornr` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL,
  `typ` tinyint(1) NOT NULL,
  `pump` int(11) NOT NULL,
  `fuktighet` int(11) NOT NULL,
  `autostart` tinyint(1) NOT NULL,
  `aktiverad` tinyint(1) NOT NULL DEFAULT '0',
  `volt` varchar(50) NOT NULL DEFAULT '0',
  `avlast` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `sensorer`
--

INSERT INTO `sensorer` (`id`, `sensornr`, `namn`, `typ`, `pump`, `fuktighet`, `autostart`, `aktiverad`, `volt`, `avlast`) VALUES
(1, 4501, 'Soilsensor', 0, 1, 65, 0, 0, '4.14', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `sirenvilt`
--

CREATE TABLE `sirenvilt` (
  `id` int(11) NOT NULL,
  `sirennr` int(11) NOT NULL DEFAULT '0',
  `namn` varchar(50) NOT NULL DEFAULT 'Viltsiren'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `sirenvilt`
--

INSERT INTO `sirenvilt` (`id`, `sirennr`, `namn`) VALUES
(1, 8501, 'Animal Siren');

-- --------------------------------------------------------

--
-- Tabellstruktur `uppgifter`
--

CREATE TABLE `uppgifter` (
  `id` int(11) NOT NULL,
  `torrjord` int(11) NOT NULL,
  `blotjord` int(11) NOT NULL,
  `lastemp` tinyint(1) NOT NULL DEFAULT '0',
  `namn` varchar(100) NOT NULL DEFAULT '',
  `pumptid` int(11) NOT NULL DEFAULT '5',
  `fukttid` int(11) NOT NULL DEFAULT '30',
  `hogvarme` tinyint(1) NOT NULL DEFAULT '0',
  `hogtemp` varchar(50) NOT NULL DEFAULT '25',
  `omstart` tinyint(1) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `tid` time NOT NULL,
  `sirentid` int(11) NOT NULL,
  `torrregn` int(11) NOT NULL,
  `blotregn` int(11) NOT NULL,
  `nattvatt` tinyint(1) NOT NULL,
  `starten` time NOT NULL,
  `stoppen` time NOT NULL,
  `nattlagepa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `uppgifter`
--

INSERT INTO `uppgifter` (`id`, `torrjord`, `blotjord`, `lastemp`, `namn`, `pumptid`, `fukttid`, `hogvarme`, `hogtemp`, `omstart`, `datum`, `tid`, `sirentid`, `torrregn`, `blotregn`, `nattvatt`, `starten`, `stoppen`, `nattlagepa`) VALUES
(1, 980, 255, 0, 'Wireless System', 3, 60, 1, '28', 0, '2018-06-06', '17:53:53', 2000, 1023, 0, 1, '22:00:00', '07:00:00', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `users`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `vilt`
--

CREATE TABLE `vilt` (
  `id` int(11) NOT NULL,
  `viltnr` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL,
  `typ` tinyint(1) NOT NULL,
  `aktiverad` tinyint(1) NOT NULL,
  `manuellt` tinyint(1) NOT NULL,
  `volt` varchar(50) NOT NULL,
  `viltsiren` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `vilt`
--

INSERT INTO `vilt` (`id`, `viltnr`, `namn`, `typ`, `aktiverad`, `manuellt`, `volt`, `viltsiren`) VALUES
(1, 6501, 'Animal Sensor', 0, 0, 0, '4.09', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `viltstatus`
--

CREATE TABLE `viltstatus` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `tid` time NOT NULL,
  `viltnr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `viltstatus`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `vilttimer`
--

CREATE TABLE `vilttimer` (
  `id` int(11) NOT NULL,
  `namn` varchar(50) NOT NULL DEFAULT '',
  `start` time NOT NULL,
  `slut` time NOT NULL,
  `mon` tinyint(1) NOT NULL DEFAULT '0',
  `tis` tinyint(1) NOT NULL DEFAULT '0',
  `ons` tinyint(1) NOT NULL DEFAULT '0',
  `tor` tinyint(1) NOT NULL DEFAULT '0',
  `fre` tinyint(1) NOT NULL DEFAULT '0',
  `lor` tinyint(1) NOT NULL DEFAULT '0',
  `son` tinyint(1) NOT NULL DEFAULT '0',
  `vilt` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `vilttimer`
--

INSERT INTO `vilttimer` (`id`, `namn`, `start`, `slut`, `mon`, `tis`, `ons`, `tor`, `fre`, `lor`, `son`, `vilt`) VALUES
(1, 'Test timer', '15:52:00', '16:00:00', 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `zoner`
--

CREATE TABLE `zoner` (
  `id` int(11) NOT NULL,
  `namn` varchar(250) NOT NULL,
  `besk` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `zoner`
--

INSERT INTO `zoner` (`id`, `namn`, `besk`) VALUES
(1, 'Potato Zone', 'Zone for my potato garden');

-- --------------------------------------------------------

--
-- Tabellstruktur `zonpump`
--

CREATE TABLE `zonpump` (
  `id` int(11) NOT NULL,
  `zon` int(11) NOT NULL DEFAULT '0',
  `pump` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `zonpump`
--

INSERT INTO `zonpump` (`id`, `zon`, `pump`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `zonregn`
--

CREATE TABLE `zonregn` (
  `id` int(11) NOT NULL,
  `regn` int(11) NOT NULL,
  `zon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `zonregn`
--

INSERT INTO `zonregn` (`id`, `regn`, `zon`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `zonsensor`
--

CREATE TABLE `zonsensor` (
  `id` int(11) NOT NULL,
  `zon` int(11) NOT NULL DEFAULT '0',
  `sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `zonsensor`
--

INSERT INTO `zonsensor` (`id`, `zon`, `sensor`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `zonvilt`
--

CREATE TABLE `zonvilt` (
  `id` int(11) NOT NULL,
  `zon` int(11) NOT NULL DEFAULT '0',
  `vilt` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `zonvilt`
--

INSERT INTO `zonvilt` (`id`, `zon`, `vilt`) VALUES
(1, 1, 1);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `jordfukt`
--
ALTER TABLE `jordfukt`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `jordfuktdag`
--
ALTER TABLE `jordfuktdag`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `loggbok`
--
ALTER TABLE `loggbok`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `luftfukt`
--
ALTER TABLE `luftfukt`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `luftfuktdag`
--
ALTER TABLE `luftfuktdag`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `postitall`
--
ALTER TABLE `postitall`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `pumpar`
--
ALTER TABLE `pumpar`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `pumptimer`
--
ALTER TABLE `pumptimer`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `regndata`
--
ALTER TABLE `regndata`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `regndatadag`
--
ALTER TABLE `regndatadag`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `regnsensor`
--
ALTER TABLE `regnsensor`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `sensorer`
--
ALTER TABLE `sensorer`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `sirenvilt`
--
ALTER TABLE `sirenvilt`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `uppgifter`
--
ALTER TABLE `uppgifter`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index för tabell `vilt`
--
ALTER TABLE `vilt`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `viltstatus`
--
ALTER TABLE `viltstatus`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `vilttimer`
--
ALTER TABLE `vilttimer`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `zoner`
--
ALTER TABLE `zoner`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `zonpump`
--
ALTER TABLE `zonpump`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `zonregn`
--
ALTER TABLE `zonregn`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `zonsensor`
--
ALTER TABLE `zonsensor`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `zonvilt`
--
ALTER TABLE `zonvilt`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `jordfukt`
--
ALTER TABLE `jordfukt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `jordfuktdag`
--
ALTER TABLE `jordfuktdag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `loggbok`
--
ALTER TABLE `loggbok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `luftfukt`
--
ALTER TABLE `luftfukt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `luftfuktdag`
--
ALTER TABLE `luftfuktdag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `postitall`
--

--
-- AUTO_INCREMENT för tabell `pumpar`
--
ALTER TABLE `pumpar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `pumptimer`
--
ALTER TABLE `pumptimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `regndata`
--
ALTER TABLE `regndata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `regndatadag`
--
ALTER TABLE `regndatadag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `regnsensor`
--
ALTER TABLE `regnsensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `sensorer`
--
ALTER TABLE `sensorer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `sirenvilt`
--
ALTER TABLE `sirenvilt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `uppgifter`
--
ALTER TABLE `uppgifter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `vilt`
--
ALTER TABLE `vilt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `viltstatus`
--
ALTER TABLE `viltstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT för tabell `vilttimer`
--
ALTER TABLE `vilttimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `zoner`
--
ALTER TABLE `zoner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `zonpump`
--
ALTER TABLE `zonpump`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `zonregn`
--
ALTER TABLE `zonregn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `zonsensor`
--
ALTER TABLE `zonsensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `zonvilt`
--
ALTER TABLE `zonvilt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
