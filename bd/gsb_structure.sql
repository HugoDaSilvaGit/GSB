DROP database IF EXISTS gsb;
CREATE database gsb;

use gsb;

-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 04 Juillet 2011 à 14:08
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `gsbFrais`
--

-- --------------------------------------------------------

--
-- Structure de la table `FraisForfait`
--

CREATE TABLE IF NOT EXISTS `FraisForfait` (
  `idFraisForfait` char(3) NOT NULL,
  `libelleFraisForfait` char(20) DEFAULT NULL,
  `montantFraisForfait` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`idFraisForfait`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `Etat`
--

CREATE TABLE IF NOT EXISTS `Etat` (
  `idEtat` char(2) NOT NULL,
  `libelleEtat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idEtat`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `Visiteur`
--

CREATE TABLE IF NOT EXISTS `Comptable` (
  `idComptable` char(3) NOT NULL,
  `nom` char(30) DEFAULT NULL,
  `prenom` char(30)  DEFAULT NULL, 
  `login` char(20) DEFAULT NULL,
  `mdp` char(20) DEFAULT NULL,
  PRIMARY KEY (`idComptable`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `Visiteur`
--

CREATE TABLE IF NOT EXISTS `Visiteur` (
  `idVisiteur` char(4) NOT NULL,
  `nom` char(30) DEFAULT NULL,
  `prenom` char(30)  DEFAULT NULL, 
  `login` char(20) DEFAULT NULL,
  `mdp` char(20) DEFAULT NULL,
  `adresse` char(30) DEFAULT NULL,
  `cp` int(5) DEFAULT NULL,
  `ville` char(30) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  PRIMARY KEY (`idVisiteur`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `FicheFrais`
--

CREATE TABLE IF NOT EXISTS `FicheFrais` (
  `number` int(2) NOT NULL,
  `idFicheFrais` char(12) NOT NULL UNIQUE,
  `idVisiteur` char(4) NOT NULL,
  `date` date NOT NULL,
  `nbJustificatifs` int(11) DEFAULT NULL,
  `montantValide` decimal(10,2) DEFAULT NULL,
  `dateModif` date DEFAULT NULL,
  `idEtat` char(2) DEFAULT 'CR',
  PRIMARY KEY (`idFicheFrais`),
  FOREIGN KEY (`idEtat`) REFERENCES Etat(`idEtat`),
  FOREIGN KEY (`idVisiteur`) REFERENCES Visiteur(`idVisiteur`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `LigneFraisForfait`
--

CREATE TABLE IF NOT EXISTS `LigneFraisForfait` (
  `idFicheFrais` char(12) NOT NULL,
  `idFraisForfait` char(3) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFraisForfait`,`idFicheFrais`),
  FOREIGN KEY (`idFicheFrais`) REFERENCES FicheFrais(`idFicheFrais`),
  FOREIGN KEY (`idFraisForfait`) REFERENCES FraisForfait(`idFraisForfait`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `LigneFraisHorsForfait`
--

CREATE TABLE IF NOT EXISTS `LigneFraisHorsForfait` (
  `idFicheFrais` char(12) NOT NULL,
  `idLigneFraisHorsForfait` int(2) NOT NULL auto_increment,
  `libelle` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idLigneFraisHorsForfait`,`idFicheFrais`),
  FOREIGN KEY (`idFicheFrais`) REFERENCES FicheFrais(`idFicheFrais`)
) ENGINE=InnoDB;