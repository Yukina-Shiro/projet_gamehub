-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 26 mars 2026 à 21:38
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gamehub_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `ami`
--

CREATE TABLE `ami` (
  `id_utilisateur1` int(11) NOT NULL,
  `id_utilisateur2` int(11) NOT NULL,
  `statut` enum('attente','valide') DEFAULT 'attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ami`
--

INSERT INTO `ami` (`id_utilisateur1`, `id_utilisateur2`, `statut`) VALUES
(1, 2, 'valide');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_com` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id_message` int(11) NOT NULL,
  `id_emetteur` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL,
  `contenu` text DEFAULT NULL,
  `id_post_partage` int(11) DEFAULT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id_message`, `id_emetteur`, `id_destinataire`, `contenu`, `id_post_partage`, `date_envoi`, `lu`) VALUES
(1, 2, 1, 'Salut !', NULL, '2026-03-26 19:49:03', 1),
(2, 1, 2, 'Helloo !', NULL, '2026-03-26 19:49:31', 1),
(5, 2, 1, 'Je vais jouer à Hollow Knight en live, tu veux passer voir ?', NULL, '2026-03-26 19:52:06', 1),
(6, 1, 2, 'Avec plaisir !', NULL, '2026-03-26 19:52:23', 0);

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id_notif` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL,
  `id_emetteur` int(11) NOT NULL,
  `type` enum('follow','demande_ami','accept_ami','new_post','refus_ami','vote','comment') NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `lu` tinyint(1) DEFAULT 0,
  `date_notif` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `statut` enum('public','ami') DEFAULT 'public',
  `titre` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `is_blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `statut`, `titre`, `description`, `photo`, `date_creation`, `is_blocked`) VALUES
(1, 2, 'public', 'Silksong aux Game Awards', 'Hollow Knight Silksong aurait dû être jeu de l\'année ! Heureusement qu\'il a gagné le prix du meilleur jeu indé, il le mérite.', 'post_2_1774550416.jpg', '2026-03-26 19:40:16', 0);

-- --------------------------------------------------------

--
-- Structure de la table `relation`
--

CREATE TABLE `relation` (
  `suiveur` int(11) NOT NULL,
  `suivi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `relation`
--

INSERT INTO `relation` (`suiveur`, `suivi`) VALUES
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `mdp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` int(10) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_naissance` date DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `email_de_secours` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `pseudo`, `bio`, `mdp`, `email`, `tel`, `date_creation`, `date_naissance`, `nom`, `prenom`, `photo_profil`, `adresse`, `role`, `email_de_secours`) VALUES
(1, 'Admin', 'Je suis un modérateur ! \r\nEnvoyer moi un message si vous avez une question sur GameHub !', '$2y$10$W0M5OnGKFQ425qtPPkjTnOvFcJLsS7S4dz286NLtB/ncLFNpY.k4C', 'Admin@admin.com', NULL, '2026-03-26 18:30:18', '1995-01-01', 'System', 'Admin', 'profil_1_1774550070.jpg', NULL, 'admin', NULL),
(2, 'Gamer06', 'Streamer dans mon temps libre !\r\nJ\'aime les jeux indés et Nintendo', '$2y$10$W0M5OnGKFQ425qtPPkjTnOvFcJLsS7S4dz286NLtB/ncLFNpY.k4C', 'gamer06@test.com', NULL, '2026-03-26 18:30:18', '2005-06-15', 'Kinger', 'Queenie', 'profil_2_1774550319.jpg', NULL, 'user', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `id_utilisateur` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vote`
--

INSERT INTO `vote` (`id_utilisateur`, `id_post`, `vote`) VALUES
(1, 1, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ami`
--
ALTER TABLE `ami`
  ADD PRIMARY KEY (`id_utilisateur1`,`id_utilisateur2`),
  ADD KEY `id_utilisateur2` (`id_utilisateur2`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_post` (`id_post`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_emetteur` (`id_emetteur`),
  ADD KEY `id_destinataire` (`id_destinataire`),
  ADD KEY `id_post_partage` (`id_post_partage`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_destinataire` (`id_destinataire`),
  ADD KEY `id_emetteur` (`id_emetteur`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `relation`
--
ALTER TABLE `relation`
  ADD PRIMARY KEY (`suiveur`,`suivi`),
  ADD KEY `suivi` (`suivi`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id_utilisateur`,`id_post`),
  ADD KEY `id_post` (`id_post`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ami`
--
ALTER TABLE `ami`
  ADD CONSTRAINT `ami_ibfk_1` FOREIGN KEY (`id_utilisateur1`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `ami_ibfk_2` FOREIGN KEY (`id_utilisateur2`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_emetteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`id_post_partage`) REFERENCES `post` (`id_post`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`id_emetteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `relation`
--
ALTER TABLE `relation`
  ADD CONSTRAINT `relation_ibfk_1` FOREIGN KEY (`suiveur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `relation_ibfk_2` FOREIGN KEY (`suivi`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
