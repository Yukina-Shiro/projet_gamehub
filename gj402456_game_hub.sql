-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 jan. 2026 à 10:40
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
-- Base de données : `gj402456_game_hub`
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
(4, 5, 'valide'),
(6, 4, 'valide');

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

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_utilisateur`, `id_post`, `commentaire`, `date_com`) VALUES
(1, 6, 10, 'ahahah trop drôle ce mec', '2026-01-24 19:54:30'),
(2, 5, 10, 'moé bof', '2026-01-24 19:55:40'),
(3, 4, 10, 'bof bof tout ça', '2026-01-24 19:56:27'),
(4, 5, 8, 'bite', '2026-01-25 18:06:38'),
(5, 5, 8, '1', '2026-01-25 18:07:01'),
(6, 8, 8, 'c moi le padreeeee', '2026-01-26 10:30:21');

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
(1, 4, 5, 'Regarde ce post !', 10, '2026-01-24 21:39:11', 1),
(2, 4, 5, 'wsh', NULL, '2026-01-25 05:38:46', 1),
(3, 5, 4, 'wsh', NULL, '2026-01-25 05:49:54', 0);

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

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id_notif`, `id_destinataire`, `id_emetteur`, `type`, `message`, `lu`, `date_notif`) VALUES
(2, 4, 5, 'accept_ami', 'a accepté votre demande d\'ami.', 1, '2026-01-24 17:32:17'),
(3, 4, 5, 'follow', 'a commencé à vous suivre.', 1, '2026-01-24 17:32:44'),
(4, 5, 4, 'follow', 'a commencé à vous suivre.', 1, '2026-01-24 17:33:08'),
(6, 4, 5, 'accept_ami', 'a accepté votre demande d\'ami.', 1, '2026-01-24 17:52:09'),
(8, 4, 6, 'demande_ami', 'veut être votre ami.', 1, '2026-01-24 18:16:48'),
(9, 4, 6, 'follow', 'a commencé à vous suivre.', 1, '2026-01-24 18:16:50'),
(10, 6, 4, 'accept_ami', 'a accepté votre demande d\'ami.', 1, '2026-01-24 18:17:11'),
(11, 6, 4, 'new_post', 'a publié un nouveau post !', 1, '2026-01-24 18:17:53'),
(12, 5, 4, 'new_post', 'a publié un nouveau post !', 1, '2026-01-24 18:17:53'),
(15, 6, 5, 'refus_ami', 'a refusé votre demande d\'ami.', 1, '2026-01-24 18:33:35'),
(16, 5, 6, 'vote', 'a liké votre post.', 1, '2026-01-24 19:53:49'),
(17, 5, 6, 'comment', 'a commenté votre post.', 1, '2026-01-24 19:54:30'),
(18, 5, 4, 'vote', 'a disliké votre post.', 1, '2026-01-24 19:56:13'),
(19, 5, 4, 'comment', 'a commenté votre post.', 1, '2026-01-24 19:56:27'),
(21, 4, 5, 'new_post', 'a publié un nouveau post !', 1, '2026-01-24 21:52:14'),
(22, 5, 4, 'vote', 'a liké votre post.', 1, '2026-01-25 05:47:34'),
(23, 5, 4, 'vote', 'a disliké votre post.', 1, '2026-01-25 05:47:35'),
(24, 6, 5, 'comment', 'a commenté votre post.', 0, '2026-01-25 18:06:38'),
(25, 6, 5, 'comment', 'a commenté votre post.', 0, '2026-01-25 18:07:01'),
(26, 4, 8, 'vote', 'a liké votre post.', 0, '2026-01-26 10:30:01'),
(27, 6, 8, 'vote', 'a liké votre post.', 0, '2026-01-26 10:30:05'),
(28, 6, 8, 'comment', 'a commenté votre post.', 0, '2026-01-26 10:30:21');

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
(3, 5, 'public', 'Zelda', 'Le perso principale qui tape avec une épée Link la franchement il est nul il parle même pas', NULL, '2026-01-24 15:55:42', 0),
(4, 4, 'public', 'Mathias', 'Il est bg ce mec hein', '6974e684770d3.png', '2026-01-24 16:34:28', 0),
(6, 4, 'public', 'yo', 'yo', '6974eadb765ee.png', '2026-01-24 16:52:59', 0),
(7, 5, 'ami', 'ozeihf', 'efizio', NULL, '2026-01-24 17:33:55', 0),
(8, 6, 'public', 'bite', 'pipi', 'post_6_1769274954.jpg', '2026-01-24 18:15:54', 0),
(9, 4, 'public', 'efo', 'oui', NULL, '2026-01-24 18:17:53', 0),
(10, 5, 'public', 'azdaz', 'dzad', NULL, '2026-01-24 18:18:41', 0),
(11, 5, 'public', 'aa', 'aa', NULL, '2026-01-24 21:52:10', 0),
(12, 5, 'public', 'bb', 'aa', NULL, '2026-01-24 21:52:14', 0),
(13, 8, 'public', 'oui', 'oui2', 'post_8_1769419865.jpg', '2026-01-26 10:31:05', 0);

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
(4, 5),
(5, 4),
(6, 4);

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
(3, 'Dondon', NULL, '$2y$10$MWaE.izADeBx4GRv0SUY9uJ6S.Mjjp7iRNvj08lh2NE', 'Dondon@test.com', NULL, '2026-01-24 14:24:13', '2006-03-20', 'Donzion', 'Nathan', NULL, NULL, 'user', NULL),
(4, 'Girl Power', 'ehifezioo', '$2y$10$W0M5OnGKFQ425qtPPkjTnOvFcJLsS7S4dz286NLtB/ncLFNpY.k4C', 'Enora@test.com', NULL, '2026-01-24 14:29:49', '2005-01-01', 'Saunier', 'Enora', 'profil_4_6974eac58575f.png', NULL, 'admin', NULL),
(5, 'Kake', '', '$2y$10$gweyQs.UuQik/bvJlD92heTedKlQxFZEB.gjdQv0wKaK5w/r/Scy.', 'Kake@test.com', NULL, '2026-01-24 14:54:37', '2005-10-05', 'Millot', 'Baptiste', 'profil_5_1769273562.jpg', NULL, 'user', NULL),
(6, 'Gemini', NULL, '$2y$10$HnUAZEs1F57ZAt2oxlGwNOw42cfMT.ZFIgVR70bvsa51vbrJjTLBi', 'Gemini@test.com', NULL, '2026-01-24 16:34:42', '2002-02-02', 'Garcia', 'Jimmy', NULL, NULL, 'user', NULL),
(7, 'nono', NULL, '$2y$10$BG3hmrX/xvoZQQE3syI65O46QBrfRtLGSOzhGSlPGyAgP9Y8yHyq.', 'nono@test.com', NULL, '2026-01-25 14:21:45', '2001-01-01', 'nono', 'nono', NULL, NULL, 'user', NULL),
(8, 'caca', NULL, '$2y$10$BUmRmo0jYstMaDB/v5Y4qOOMAlKQUAfgPKisTHNRRb2NdxY71ryyq', 'redahalim06@gmail.com', 744853693, '2026-01-26 09:29:40', '2005-04-28', 'payan', 'tom', NULL, NULL, 'user', NULL);

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
(4, 10, -1),
(5, 10, 1),
(6, 10, 1),
(8, 6, 1),
(8, 8, 1);

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
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
