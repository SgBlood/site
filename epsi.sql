-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 21 sep. 2022 à 12:22
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
-- Base de données : `epsi`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(255) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `theme` text NOT NULL,
  `resume` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_post` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `auteur`, `titre`, `theme`, `resume`, `contenu`, `date_post`) VALUES
(1, 'Mimi', 'ceci est un test', 'Autre', 'Cet article n\\\'a pas de résumé ...', 'blablabla', '2022-09-20 10:15:09'),
(2, 'Mimi', 'YOUHOUUUU', 'Actu', 'Wsh mon site il marche', 'Il a prit le métro ce matin, il marche !!! Par contre il ne sait pas voler', '2022-09-21 11:27:42'),
(3, 'Mimi', 'spam 1', 'Autre', 'Cet article n\\\'a pas de résumé ...', 'dzefzfv', '2022-09-21 11:40:53'),
(4, 'Mimi', 'spam 2', 'Autre', 'Cet article n\\\'a pas de résumé ...', 'dlvheuhrorif', '2022-09-21 11:41:01'),
(5, 'Mimi', 'spam 3', 'Autre', 'Cet article n\\\'a pas de résumé ...', 'dfvfd', '2022-09-21 11:41:19'),
(6, 'Mimi', 'spam 4', 'Autre', 'Cet article n\\\'a pas de résumé ...', 'sfsdfsd', '2022-09-21 11:41:30');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_billet` int(11) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_billet`, `auteur`, `commentaire`, `date_commentaire`) VALUES
(1, 1, 'Mimi', 'J\\\'adore ton article vide !', '2022-09-20 08:22:26'),
(2, 1, 'Mimi', 'Un autre commentaire car je m\\\'ennuie', '2022-09-20 08:35:00');

-- --------------------------------------------------------

--
-- Structure de la table `tchat`
--

DROP TABLE IF EXISTS `tchat`;
CREATE TABLE IF NOT EXISTS `tchat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date_tchat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tchat`
--

INSERT INTO `tchat` (`id`, `pseudo`, `message`, `date_tchat`) VALUES
(1, 'Mimi', 'J\\\'inaugure le premier message de ce superbe tchat !', '2022-09-20 10:28:24'),
(2, 'Mimi', 'Et aussi le deuxième car c\\\'est fun (FIRST btw)', '2022-09-20 10:28:54'),
(3, 'michelle', 'Bonjour je m\\\'appelle pas Michelle et je ne suis absolument pas Mimi', '2022-09-20 10:30:39');

-- --------------------------------------------------------

--
-- Structure de la table `visiteurs`
--

DROP TABLE IF EXISTS `visiteurs`;
CREATE TABLE IF NOT EXISTS `visiteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `visiteurs`
--

INSERT INTO `visiteurs` (`id`, `pseudo`, `mdp`, `email`, `date_inscription`) VALUES
(1, 'Mimi', '$2y$10$VL.T5veoLRXT9q0qauYAlOUr3k1PRe0k.mBRqF.FjhHFZuyxng/d6', 'm.m@m.com', '2022-09-20 09:29:53'),
(2, 'michelle', '$2y$10$sniHxjIwANnE5CjPjOo5Vu/aD38gkT8BhRcZCEJIHnx9Bn35xcCMy', 'mich.m@m.com', '2022-09-20 10:29:42');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
