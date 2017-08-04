-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:3306
-- Généré le :  Ven 18 Novembre 2016 à 16:51
-- Version du serveur :  5.5.42
-- Version de PHP :  5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `planning`
--

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `evenement_id` int(30) NOT NULL,
  `evenement_title` text NOT NULL,
  `evenement_duree` text NOT NULL,
  `evenement_statut` text NOT NULL,
  `evenement_date` date NOT NULL,
  `evenement_moment` text NOT NULL,
  `chef_projet` text NOT NULL,
  `evenement_desc` text NOT NULL,
  `evenement_ticket` varchar(5) NOT NULL,
  `utilisateur` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `evenement`
--

INSERT INTO `evenement` (`evenement_id`, `evenement_title`, `evenement_duree`, `evenement_statut`, `evenement_date`, `evenement_moment`, `chef_projet`, `evenement_desc`, `evenement_ticket`, `utilisateur`) VALUES;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`evenement_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `evenement_id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;