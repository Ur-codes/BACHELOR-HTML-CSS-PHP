-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 16 fév. 2022 à 08:04
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `velibre`
--

-- --------------------------------------------------------

--
-- Structure de la table `stations`
--

DROP TABLE IF EXISTS `stations`;
CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stations`
--

INSERT INTO `stations` (`id`, `nom`, `longitude`, `latitude`) VALUES
(1, 'London Station 1', '-0.3048592879879975', '51.467183648379056'),
(2, 'London Station 2', '0.0005435777308271384', '51.339191508712105'),
(3, 'London Station 3', '-0.14342524594253672', '51.455718379799116'),
(4, 'Station London 4', '-0.2727739980474176', '51.34656891472262'),
(5, 'London Station 5', '-0.1490710808567575', '51.34614002712134'),
(6, 'Station London 6', '-0.08829021948937756', '51.4238743547693'),
(7, 'London Station 7', '-0.22907169615399517', '51.39800614177835'),
(11, 'London Station Random', '-0.1022383371764635', '51.2852832782101'),
(15, 'Paris Station 1', '2.3538487083009856', '48.85577024818978'),
(16, 'Paris Station 2', '2.292781404380149', '48.871038194878636'),
(17, 'Paris Station 3', '2.3408980274976665', '48.826757381274426');

-- --------------------------------------------------------

--
-- Structure de la table `stationsvelosutilisateurs`
--

DROP TABLE IF EXISTS `stationsvelosutilisateurs`;
CREATE TABLE IF NOT EXISTS `stationsvelosutilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstations` int(255) NOT NULL,
  `idvelos` int(255) DEFAULT NULL,
  `idutilisateurs` int(255) DEFAULT NULL,
  `dateRendu` datetime DEFAULT NULL,
  `dateLouer` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stationsvelosutilisateurs`
--

INSERT INTO `stationsvelosutilisateurs` (`id`, `idstations`, `idvelos`, `idutilisateurs`, `dateRendu`, `dateLouer`) VALUES
(1, 1, 4, 1, '2022-02-15 21:35:12', NULL),
(2, 3, 5, 1, '2022-02-15 23:21:39', '2022-02-15 23:21:31'),
(3, 1, 6, 12, NULL, NULL),
(4, 1, 7, NULL, NULL, NULL),
(5, 2, 8, 1, NULL, NULL),
(6, 1, 9, NULL, NULL, NULL),
(7, 1, 10, NULL, NULL, NULL),
(8, 2, 11, 1, NULL, NULL),
(9, 12, 12, NULL, NULL, NULL),
(10, 1, 13, NULL, NULL, NULL),
(11, 1, 14, NULL, NULL, NULL),
(12, 2, 15, NULL, NULL, NULL),
(13, 17, 16, 1, '2022-02-16 08:59:03', '2022-02-16 08:58:58');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` varchar(255) NOT NULL DEFAULT 'non',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `password`, `admin`) VALUES
(1, 'test', 'test', 'non'),
(11, 'toto', 'toto', 'oui'),
(12, 'test2', 'test2', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `velos`
--

DROP TABLE IF EXISTS `velos`;
CREATE TABLE IF NOT EXISTS `velos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etat` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `velos`
--

INSERT INTO `velos` (`id`, `etat`) VALUES
(1, 'non louer'),
(2, 'non louer'),
(3, 'non louer'),
(4, 'non louer'),
(5, 'non louer'),
(6, 'non louer'),
(7, 'non louer'),
(8, 'non louer'),
(9, 'non louer'),
(10, 'non louer'),
(11, 'non louer'),
(12, 'non louer'),
(13, 'non louer'),
(14, 'non louer'),
(15, 'non louer'),
(16, 'non louer');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
