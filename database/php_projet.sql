-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 10, 2020 at 01:23 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_projet`
--

-- --------------------------------------------------------

--
-- Table structure for table `redacteur`
--

DROP TABLE IF EXISTS `redacteur`;
CREATE TABLE IF NOT EXISTS `redacteur` (
  `IDRedacteur` int(10) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `Prenom` varchar(50) COLLATE utf8_bin NOT NULL,
  `AdresseMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `MotDePasse` varchar(100) COLLATE utf8_bin NOT NULL,
  `Pseudo` varchar(25) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`IDRedacteur`),
  UNIQUE KEY `Pseudo` (`Pseudo`),
  UNIQUE KEY `AdresseMail` (`AdresseMail`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `redacteur`
--

INSERT INTO `redacteur` (`IDRedacteur`, `Nom`, `Prenom`, `AdresseMail`, `MotDePasse`, `Pseudo`) VALUES
(1, 'Dudon', 'Adrien', 'dudonadrien@gmail.com', '$2y$10$AtGwxYCNVr4fUMleQ7LYRO2FvubHBTcE48CC7Jkv.nCNsiJq/umsO', 'Deewens');

-- --------------------------------------------------------

--
-- Table structure for table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `IDReponse` int(10) NOT NULL AUTO_INCREMENT,
  `IDSujet` int(10) NOT NULL,
  `IDRedacteur` int(10) NOT NULL,
  `DateRep` datetime NOT NULL,
  `TexteReponse` varchar(400) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`IDReponse`),
  KEY `IDRedacteur` (`IDRedacteur`),
  KEY `IDSujet` (`IDSujet`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `reponse`
--

INSERT INTO `reponse` (`IDReponse`, `IDSujet`, `IDRedacteur`, `DateRep`, `TexteReponse`) VALUES
(1, 9, 1, '2019-10-24 20:17:21', 'te'),
(2, 9, 1, '2019-10-24 20:56:34', 'test'),
(5, 9, 1, '2019-10-24 21:26:48', 'test'),
(6, 6, 1, '2019-10-24 21:27:17', 'ceci est un putain de test !'),
(7, 7, 1, '2019-10-24 21:29:29', 'Pour rÃ©soudre une proposition de valeur qualitative, la conjecture pousse les analystes Ã  budgetiser les sourcing compÃ©titivitÃ©.'),
(8, 8, 1, '2019-10-24 21:31:38', 'Pour rÃ©soudre une proposition de valeur qualitative, la conjecture pousse les analystes Ã  budgetiser les sourcing compÃ©titivitÃ©.'),
(9, 9, 1, '2019-10-25 09:17:39', 'Bonjour'),
(10, 9, 1, '2019-10-25 16:47:02', 'test'),
(14, 9, 1, '2019-10-25 16:50:03', 'test'),
(15, 9, 1, '2019-10-25 16:50:08', 'bien le bonsoir'),
(16, 9, 1, '2019-10-25 16:50:24', 'test'),
(17, 9, 1, '2019-10-25 16:51:12', 'coucu'),
(18, 8, 1, '2019-10-25 20:01:56', 'Hey!'),
(19, 8, 1, '2019-10-25 20:02:08', 'test'),
(20, 8, 1, '2019-10-25 20:02:33', 'test'),
(21, 8, 1, '2019-10-25 20:04:04', 'test2'),
(22, 8, 1, '2019-10-25 20:06:28', 'test50'),
(23, 8, 1, '2019-10-25 20:06:40', '25'),
(24, 5, 1, '2019-10-25 20:07:24', 'test'),
(25, 5, 1, '2019-10-25 20:07:33', 'test'),
(26, 6, 1, '2019-10-25 20:08:36', 'coucou!'),
(27, 9, 1, '2019-10-25 20:52:22', 'test'),
(28, 9, 1, '2019-10-25 21:07:45', 'r'),
(29, 8, 1, '2019-10-26 08:56:15', 'Coucou !'),
(30, 8, 1, '2019-10-26 08:59:58', 'Il me semble judicieux de rappeler la cÃ©lÃ¨bre citation de Michel Rocard, on ne peut pas accueillir toute la misÃ¨re du monde. Jadis, cela avait sucitÃ© de nombreux dÃ©chainements de haine envers cette mÃªme personne. Et aujourd\'hui, on s\'aperÃ§oit que notre prÃ©sident Ã©met le mÃªme'),
(31, 8, 1, '2019-10-26 09:05:14', 'Il me semble judicieux de rappeler la cÃ©lÃ¨bre citation de Michel Rocard, on ne peut pas accueillir toute la misÃ¨re du monde. Jadis, cela avait sucitÃ© de nombreux dÃ©chainements de haine envers cette mÃªme personne. Et aujourd\'hui, on s\'aperÃ§oit que notre prÃ©sident Ã©met le mÃªme');

-- --------------------------------------------------------

--
-- Table structure for table `sujet`
--

DROP TABLE IF EXISTS `sujet`;
CREATE TABLE IF NOT EXISTS `sujet` (
  `IDSujet` int(10) NOT NULL AUTO_INCREMENT,
  `IDRedacteur` int(10) NOT NULL,
  `TitreSujet` varchar(400) COLLATE utf8_bin NOT NULL,
  `TexteSujet` mediumtext COLLATE utf8_bin NOT NULL,
  `DateSujet` datetime NOT NULL,
  PRIMARY KEY (`IDSujet`),
  KEY `IDRedacteur` (`IDRedacteur`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sujet`
--

INSERT INTO `sujet` (`IDSujet`, `IDRedacteur`, `TitreSujet`, `TexteSujet`, `DateSujet`) VALUES
(5, 1, 'test', 'test', '2019-10-23 18:04:42'),
(6, 1, 'test2', 'test2', '2019-10-23 18:04:53'),
(7, 1, 'Mon petit discours', 'Mesdames, mesdemoiselles, messieurs,\r\n\r\nAssurer la libertÃ© et la dignitÃ© de la personne humaine, c\'est le premier de tous les devoirs politiques.\r\n\r\nPermettez-moi de le dire franchement : la pauvretÃ© qui s\'accroÃ®t est un problÃ¨me majeur, de nature Ã  remettre en cause notre projet de sociÃ©tÃ©.\r\n\r\nQue croyez-vous que nos interlocuteurs aient trouvÃ© Ã  rÃ©pondre ? Pas grand-chose... Le chemin qui reste Ã  parcourir est, certes, considÃ©rable : encore faut-il savoir mettre en oeuvre une dynamique cohÃ©rente.\r\n\r\nDans ce contexte difficile, ma conviction profonde, voyez-vous, c\'est que la pauvretÃ© qui s\'accroÃ®t pose la question de la solidaritÃ© et de la dÃ©mocratie, en prenant le temps d\'aller Ã©couter les autres, prendre le temps d\'aller les comprendre. Ce temps n\'est jamais perdu, au travers d\'un humanisme Ã©clairÃ© et d\'une dÃ©termination sans faille.\r\n\r\nPour terminer, je voudrais rappeler une parole de Coluche qui disait : Â« Si tous ceux qui n\'ont rien n\'en demandaient pas plus, il serait bien facile de contenter tout le monde Â». Si vous avez besoin de quelque chose, nos adversaires vous expliqueront comment vous en passer.\r\n\r\nL\'avenir est porteur d\'espoirs pour chacun d\'entre nous avec un programme plus humain, plus juste et plus fraternel. Tous ensemble, marchons vers l\'avenir !\r\n\r\nMesdames, mesdemoiselles, messieurs, je vous remercie de votre attention.', '2019-10-23 18:10:46'),
(8, 1, 'Phrase constructive', 'Â« Ne cherchons pas Ã  imiter. En effet, je tiens Ã  souligner que la pauvretÃ© qui s\'accroÃ®t nous invite Ã  agir, agir dÃ¨s Ã  prÃ©sent et ensemble, en prenant le temps d\'aller Ã©couter les autres, prendre le temps d\'aller les comprendre. Ce temps n\'est jamais perdu, avec la ferme volontÃ© de gagner ce difficile combat. C\'est ma prioritÃ©. Â»', '2019-10-23 18:18:12'),
(9, 1, '&lt;script&gt;alert(document.cookie);&lt;/script&gt;', '&lt;script&gt;alert(document.cookie);&lt;/script&gt;', '2019-10-23 19:05:40');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`IDRedacteur`) REFERENCES `redacteur` (`IDRedacteur`),
  ADD CONSTRAINT `reponse_ibfk_2` FOREIGN KEY (`IDSujet`) REFERENCES `sujet` (`IDSujet`);

--
-- Constraints for table `sujet`
--
ALTER TABLE `sujet`
  ADD CONSTRAINT `sujet_ibfk_1` FOREIGN KEY (`IDRedacteur`) REFERENCES `redacteur` (`IDRedacteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
