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

-- Table: fb_concours

-- DROP TABLE fb_concours;

CREATE TABLE fb_concours
(
  id serial NOT NULL,
  date_debut timestamp without time zone,
  date_fin timestamp without time zone,
  actif integer, -- 0 inactif...
  nom character varying,
  CONSTRAINT fb_utilisateurs_pkey PRIMARY KEY (id)
)
  WITH (
OIDS=FALSE
);
ALTER TABLE fb_concours
OWNER TO uvewrtiishknof;
COMMENT ON COLUMN fb_concours.actif IS '0 inactif
1 actif';
-- --------------------------------------------------------

--
-- Structure de la table `fb_participation`
--

-- Table: fb_participation

-- DROP TABLE fb_participation;

CREATE TABLE fb_participation
(
  id serial NOT NULL,
  fk_concours_id integer,
  fk_utilisateur_id integer,
  facebook_photo_id character varying(255),
  message character varying(255),
  date_participation timestamp without time zone,
  actif integer,
  facebook_photo_link character varying(255),
  CONSTRAINT fb_participation_pkey PRIMARY KEY (id)
)
  WITH (
OIDS=FALSE
);
ALTER TABLE fb_participation
OWNER TO uvewrtiishknof;


-- --------------------------------------------------------

--
-- Structure de la table `fb_utilisateurs`
--

-- Table: fb_utilisateurs

-- DROP TABLE fb_utilisateurs;

CREATE TABLE fb_utilisateurs
(
  id serial NOT NULL,
  facebook_id character varying(255),
  nom character varying(255),
  prenom character varying(255),
  genre character varying(255),
  localisation character varying(255),
  email character varying(255),
  facebook_link character varying(255),
  date_enregistrement timestamp without time zone,
  CONSTRAINT fb_utilisateurs_pkey1 PRIMARY KEY (id)
)
  WITH (
OIDS=FALSE
);
ALTER TABLE fb_utilisateurs
OWNER TO uvewrtiishknof;

-- --------------------------------------------------------

--
-- Structure de la table `fb_vote`
--

-- Table: fb_concours_prize

-- DROP TABLE fb_concours_prize;

CREATE TABLE fb_concours_prize
(
  id serial NOT NULL,
  fk_concours_id integer,
  prize_position character varying(255),
  prize_name character varying(255),
  actif integer,
  CONSTRAINT fb_concours_prize_pkey PRIMARY KEY (id)
)
  WITH (
OIDS=FALSE
);
ALTER TABLE fb_concours_prize
OWNER TO uvewrtiishknof;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
