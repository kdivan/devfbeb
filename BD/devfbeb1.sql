-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 16 Mai 2015 à 20:47
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `devfbeb1`
--

-- --------------------------------------------------------

--
-- Structure de la table `fb_concours`
--

DROP TABLE IF EXISTS `fb_concours`;
CREATE TABLE IF NOT EXISTS `fb_concours` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `actif` int(1) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_resultat` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fb_participation`
--

DROP TABLE IF EXISTS `fb_participation`;
CREATE TABLE IF NOT EXISTS `fb_participation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fk_concours_id` int(255) NOT NULL,
  `fk_utilisateur_id` int(255) NOT NULL,
  `facebook_photo_id` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `date_participation` datetime NOT NULL,
  `actif` int(1) NOT NULL COMMENT '1=actif, 0=desactivé',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fb_utilisateurs`
--

DROP TABLE IF EXISTS `fb_utilisateurs`;
CREATE TABLE IF NOT EXISTS `fb_utilisateurs` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `facebook_id` int(255) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fb_vote`
--

DROP TABLE IF EXISTS `fb_vote`;
CREATE TABLE IF NOT EXISTS `fb_vote` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fk_utilisateur_id` int(255) NOT NULL,
  `fk_participation_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
