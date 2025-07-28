-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 11 juil. 2025 à 22:47
-- Version du serveur : 8.0.42-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maxit`
--

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` int NOT NULL,
  `solde` decimal(15,2) DEFAULT '0.00',
  `numero` varchar(50) NOT NULL,
  `utilisateur_id` int NOT NULL,
  `type` enum('compte_principal','compte_secondaire') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id`, `solde`, `numero`, `utilisateur_id`, `type`, `created_at`, `updated_at`) VALUES
(1, 100000.00, 'CPT001', 1, 'compte_principal', '2025-07-09 14:37:22', '2025-07-09 14:37:22'),
(2, 50000.00, 'CPT002', 2, 'compte_principal', '2025-07-09 14:37:22', '2025-07-09 14:37:22'),
(3, 25000.00, 'CPT003', 2, 'compte_secondaire', '2025-07-09 14:37:22', '2025-07-09 14:37:22'),
(4, 75000.00, 'CPT000003', 3, 'compte_principal', '2025-07-09 18:27:36', '2025-07-09 18:27:36'),
(5, 1000.00, 'CPT563564', 8, 'compte_principal', '2025-07-09 19:03:01', '2025-07-09 19:03:01'),
(6, 1000.00, 'CPT224042', 9, 'compte_principal', '2025-07-09 19:11:04', '2025-07-09 19:11:04'),
(7, 1000.00, 'CPT564247', 10, 'compte_principal', '2025-07-09 19:11:39', '2025-07-09 19:11:39'),
(8, 0.00, 'CPT334385', 14, 'compte_secondaire', '2025-07-09 20:24:55', '2025-07-09 20:24:55'),
(9, 0.00, 'CPT919901', 15, 'compte_secondaire', '2025-07-09 20:25:07', '2025-07-09 20:25:07'),
(10, 0.00, 'CPT203656', 16, 'compte_secondaire', '2025-07-09 20:25:15', '2025-07-09 20:25:15'),
(11, 0.00, 'CPT759646', 17, 'compte_secondaire', '2025-07-09 20:25:37', '2025-07-09 20:25:37'),
(12, 0.00, 'CPT741042', 18, 'compte_secondaire', '2025-07-09 20:25:44', '2025-07-09 20:25:44'),
(13, 0.00, 'CPT802846', 19, 'compte_secondaire', '2025-07-09 20:25:52', '2025-07-09 20:25:52'),
(14, 0.00, 'CPT354208', 20, 'compte_secondaire', '2025-07-09 20:28:17', '2025-07-09 20:28:17'),
(15, 0.00, 'CPT303757', 26, 'compte_secondaire', '2025-07-09 20:42:00', '2025-07-09 20:42:00'),
(18, 0.00, 'CPT297989', 29, 'compte_secondaire', '2025-07-09 20:51:45', '2025-07-09 20:51:45'),
(19, 0.00, 'CPT110983', 30, 'compte_secondaire', '2025-07-09 20:54:13', '2025-07-09 20:54:13'),
(20, 0.00, 'CPT206679', 31, 'compte_secondaire', '2025-07-09 21:51:12', '2025-07-09 21:51:12'),
(21, 0.00, 'CPT435062', 32, 'compte_secondaire', '2025-07-10 10:28:12', '2025-07-10 10:28:12'),
(22, 0.00, 'CPT648237', 33, 'compte_secondaire', '2025-07-10 15:39:07', '2025-07-10 15:39:07'),
(24, 0.00, 'CPT886394', 35, 'compte_secondaire', '2025-07-11 09:09:17', '2025-07-11 09:09:17'),
(26, 0.00, 'CPT170690', 37, 'compte_secondaire', '2025-07-11 09:34:45', '2025-07-11 09:34:45'),
(33, 0.00, 'CPT292013', 44, 'compte_secondaire', '2025-07-11 13:03:28', '2025-07-11 13:03:28'),
(34, 0.00, 'CPT013357', 45, 'compte_secondaire', '2025-07-11 13:05:58', '2025-07-11 13:05:58');

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `id` int NOT NULL,
  `client` tinyint(1) DEFAULT '0',
  `service_commercial` tinyint(1) DEFAULT '0',
  `utilisateur_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id`, `client`, `service_commercial`, `utilisateur_id`, `created_at`) VALUES
(1, 0, 1, 1, '2025-07-09 14:37:22'),
(2, 1, 0, 2, '2025-07-09 14:37:22'),
(3, 1, 0, 3, '2025-07-09 18:27:36'),
(4, 1, 0, 4, '2025-07-09 18:52:04'),
(5, 1, 0, 5, '2025-07-09 18:53:13'),
(6, 1, 0, 6, '2025-07-09 18:58:30'),
(7, 1, 0, 7, '2025-07-09 19:00:44'),
(8, 1, 0, 8, '2025-07-09 19:01:44'),
(10, 1, 0, 9, '2025-07-09 19:11:04'),
(11, 1, 0, 10, '2025-07-09 19:11:22'),
(12, 1, 0, 11, '2025-07-09 19:42:04'),
(13, 1, 0, 12, '2025-07-09 19:42:18'),
(14, 1, 0, 13, '2025-07-09 19:42:27'),
(15, 1, 0, 14, '2025-07-09 20:24:55'),
(16, 1, 0, 15, '2025-07-09 20:25:07'),
(17, 1, 0, 16, '2025-07-09 20:25:15'),
(18, 1, 0, 17, '2025-07-09 20:25:37'),
(19, 1, 0, 18, '2025-07-09 20:25:44'),
(20, 1, 0, 19, '2025-07-09 20:25:52'),
(21, 1, 0, 20, '2025-07-09 20:28:17'),
(22, 1, 0, 21, '2025-07-09 20:32:29'),
(24, 1, 0, 23, '2025-07-09 20:36:44'),
(25, 1, 0, 24, '2025-07-09 20:37:35'),
(26, 1, 0, 25, '2025-07-09 20:38:11'),
(27, 1, 0, 26, '2025-07-09 20:42:00'),
(30, 1, 0, 29, '2025-07-09 20:51:45'),
(31, 1, 0, 30, '2025-07-09 20:54:13'),
(32, 1, 0, 31, '2025-07-09 21:51:12'),
(33, 1, 0, 32, '2025-07-10 10:28:12'),
(34, 1, 0, 33, '2025-07-10 15:39:07'),
(36, 1, 0, 35, '2025-07-11 09:09:17'),
(38, 1, 0, 37, '2025-07-11 09:34:45'),
(45, 1, 0, 44, '2025-07-11 13:03:28'),
(46, 1, 0, 45, '2025-07-11 13:05:58');

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int NOT NULL,
  `date` datetime NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `compte_id` int NOT NULL,
  `type` enum('paiement','depot','retrait') NOT NULL,
  `statut` enum('en_attente','validee','annulee') DEFAULT 'en_attente',
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`id`, `date`, `montant`, `compte_id`, `type`, `statut`, `description`, `created_at`) VALUES
(1, '2025-07-09 14:37:22', 10000.00, 1, 'depot', 'validee', NULL, '2025-07-09 14:37:22'),
(2, '2025-07-09 14:37:22', 5000.00, 2, 'depot', 'validee', NULL, '2025-07-09 14:37:22'),
(3, '2025-07-09 14:37:22', 2000.00, 2, 'retrait', 'validee', NULL, '2025-07-09 14:37:22'),
(4, '2025-07-09 18:27:36', 75000.00, 4, 'depot', 'validee', 'Dépôt initial lors de la création du compte', '2025-07-09 18:27:36'),
(5, '2025-07-10 12:00:00', 5000.00, 4, 'depot', 'validee', 'Dépôt mensuel', '2025-07-10 13:14:09'),
(6, '2025-07-10 13:00:00', 2000.00, 4, 'retrait', 'validee', 'Retrait pour achats', '2025-07-10 13:14:09'),
(7, '2025-07-10 14:00:00', 3000.00, 4, 'paiement', 'validee', 'Paiement facture SENELEC', '2025-07-10 13:14:09'),
(8, '2025-07-10 15:00:00', 1000.00, 4, 'retrait', 'validee', 'Retrait distributeur', '2025-07-10 13:14:09'),
(9, '2025-07-10 16:00:00', 7000.00, 4, 'depot', 'validee', 'Versement salaire', '2025-07-10 13:14:09'),
(10, '2025-07-10 17:00:00', 1500.00, 4, 'paiement', 'validee', 'Paiement internet', '2025-07-10 13:14:09'),
(11, '2025-07-10 18:00:00', 2500.00, 4, 'retrait', 'validee', 'Retrait au guichet', '2025-07-10 13:14:09'),
(12, '2025-07-10 19:00:00', 3500.00, 4, 'depot', 'validee', 'Dépôt complémentaire', '2025-07-10 13:14:09'),
(13, '2025-07-10 20:00:00', 1000.00, 4, 'paiement', 'validee', 'Paiement restaurant', '2025-07-10 13:14:09'),
(14, '2025-07-10 21:00:00', 2000.00, 4, 'retrait', 'validee', 'Retrait pour courses', '2025-07-10 13:14:09'),
(15, '2025-07-10 22:00:00', 4000.00, 4, 'depot', 'validee', 'Remboursement ami', '2025-07-10 13:14:09');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `numeroTelephone` varchar(20) DEFAULT NULL,
  `numeroCNI` varchar(20) DEFAULT NULL,
  `adresse` text,
  `photorecto` varchar(255) DEFAULT NULL,
  `photoverso` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `login`, `mot_de_passe`, `prenom`, `numeroTelephone`, `numeroCNI`, `adresse`, `photorecto`, `photoverso`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super', '+221123456789', NULL, NULL, NULL, NULL, '2025-07-09 14:37:22', '2025-07-09 20:04:09'),
(2, 'Client1', 'client1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', '+221987654321', NULL, NULL, NULL, NULL, '2025-07-09 14:37:22', '2025-07-09 19:53:52'),
(3, 'DIALLO', 'diallo@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mamadou', '+221771234567', '1234567890123', 'Dakar, Senegal', 'photos/recto_diallo.jpg', 'photos/verso_diallo.jpg', '2025-07-09 18:27:36', '2025-07-09 19:53:52'),
(4, 'Test', 'testuser', '$2y$10$iDf5u.XH/kYHibCLbZI1oObxetLnj/6OB2BTpb9zqRoRMKHgkN09C', 'User', '0123456789', '', '', '', '', '2025-07-09 18:52:04', '2025-07-09 19:53:52'),
(5, 'Test', 'testuser2', '$2y$10$iLj6C2eLRuKG/exwmZx4CerEKY71eABca2DvgFSSkBuLfrote.GJO', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 18:53:13', '2025-07-09 19:53:52'),
(6, 'Test', 'testuser3', '$2y$10$Y4tIq/GDd0o7/gDtBgoc6.Biw9iLUYTF.AjpFV/ZGwX0y4A3ssyoC', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 18:58:30', '2025-07-09 19:53:52'),
(7, 'Test', 'testuser4', '$2y$10$5VbuoYVun631buIQuH2AqOLhZ2QsHsmDG.LFGc9Z1oS9tAKfuiuRS', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 19:00:44', '2025-07-09 19:53:52'),
(8, 'Test', 'testuser5', '$2y$10$IVlyJ34WjIowa8VvRjco8eJIpavHKxkDCgdVbe7peCEVR5.IEL.UG', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 19:01:44', '2025-07-09 19:53:52'),
(9, 'Test', 'testuser6', '$2y$10$OwKBIhCa/mA8Msx0vj6UJekuG2YaX7hUDIU7esqr68nS8Ig0cmUiW', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 19:11:04', '2025-07-09 19:53:52'),
(10, 'Test', 'testuser7', '$2y$10$/rINAWACin2yNpH.3LZxduwuAvK0kdgVQ1h.S4CqXEVVjk/6tsvZC', 'User', '0123456789', '1234567890123', '123 Rue de Test', 'recto.jpg', 'verso.jpg', '2025-07-09 19:11:22', '2025-07-09 19:53:52'),
(11, 'Test', 'testuser_1752090123', '$2y$10$V9GLXkmg89KnhRIqxTEI0uprw9icpost37pFllccWjK7pW//iA4ka', 'User', '123456789', 'CNI1752090124', '123 Test Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 19:42:04', '2025-07-09 19:53:52'),
(12, 'Test', 'testuser_1752090138', '$2y$10$gduLLv4J5YojVgCDdD3C6uzffQl62NWjQkWW5Ip33LTbGsX41h2/i', 'User', '123456789', 'CNI1752090138', '123 Test Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 19:42:18', '2025-07-09 19:53:52'),
(13, 'Test', 'testuser_1752090147', '$2y$10$GbPLQGzMSYHUThcKd3p5be.b2MU6BnA3nP5lkHMCOwHL0zejSATLW', 'User', '123456789', 'CNI1752090147', '123 Test Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 19:42:27', '2025-07-09 19:53:52'),
(14, 'TestSimple', 'testsimple1752092695@example.com', '$2y$10$mE1M6bhPVasPM0Ja.9JEg.IkLtCd3tTcf3z719JS22imN...ahPje', 'User', '123456789', 'SIMPLE1752092695', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:24:55', '2025-07-09 20:24:55'),
(15, 'TestSimple', 'testsimple1752092706@example.com', '$2y$10$M3q.BvVeBXKIZIO8daHmseIaNLtHtm4VIiT/jtAXoRe06v6EhSY5u', 'User', '123456789', 'SIMPLE1752092706', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:25:07', '2025-07-09 20:25:07'),
(16, 'TestSimple', 'testsimple1752092714@example.com', '$2y$10$ngIzWmQaRDsQoyp41GHs3uo6LiqvjhyyBBqvAOo9nDd8SPN32jBra', 'User', '123456789', 'SIMPLE1752092714', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:25:15', '2025-07-09 20:25:15'),
(17, 'TestSimple', 'testsimple1752092737@example.com', '$2y$10$shtXovj.ZknplaX6bLz/fuoOu5kcOfg2G/i01LUaZ1hcItzso5mW2', 'User', '123456789', 'SIMPLE1752092737', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:25:37', '2025-07-09 20:25:37'),
(18, 'TestSimple', 'testsimple1752092744@example.com', '$2y$10$9uEwQgX2dZizkAxZGW.neucbMkDVByjrGEIqNM/630PM4YXNKVPWq', 'User', '123456789', 'SIMPLE1752092744', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:25:44', '2025-07-09 20:25:44'),
(19, 'TestSimple', 'testsimple1752092752@example.com', '$2y$10$5VGPFQs7UgQL2I40yyOLS.I5QmjvG4ue1xVvIBZuFqHW9brCr297i', 'User', '123456789', 'SIMPLE1752092752', '123 Simple Street', 'recto_test.jpg', 'verso_test.jpg', '2025-07-09 20:25:52', '2025-07-09 20:25:52'),
(20, 'bachir', 'bachir@gmail.com', '$2y$10$Q3rEiZnQKm7NA0b3kuIg2.1X8a6.wrjLD5g6J0AMZdfXdDWirKlsq', 'diop', '111111111', '111111111', '111111111', 'recto_1752092897_686ed0e1a299f.png', 'verso_1752092897_686ed0e1a2a55.png', '2025-07-09 20:28:17', '2025-07-09 20:28:17'),
(21, 'TestConnexion', 'testconnexion@example.com', '$2y$10$3PhyRK5dVbtZ2k2H7KOYkuy47sLPrhUX98YeK1Y.tCn3eDok3S0ze', 'User', '123456789', 'CONN1752093149', '123 Connexion Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 20:32:29', '2025-07-09 20:32:29'),
(23, 'TestConnexion2', 'testconnexion2@example.com', '$2y$10$fZnY1Om1WT6kEwHZHk6IA.LQVi2DjnqO/mVa/l4vcpLKM5363.PsO', 'User', '123456789', 'CONN21752093404', '123 Connexion Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 20:36:44', '2025-07-09 20:36:44'),
(24, 'TestFinal', 'testfinal@example.com', '$2y$10$t3Aub4PNlN0g9g3s4GEgheULhUES5o1wd6svTdV4ouxL1V9pEleSG', 'User', '123456789', 'FINAL1752093454', '123 Final Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 20:37:35', '2025-07-09 20:37:35'),
(25, 'TestRedirect', 'testredirect@example.com', '$2y$10$6B2IT6gLBL0/pdCetZz7K.173d2tKY4eKj2r7GBF8ZuaPY6GjwVWa', 'User', '123456789', 'REDIRECT1752093491', '123 Redirect Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 20:38:11', '2025-07-09 20:38:11'),
(26, 'bamba', 'bamba@gmail.com', '$2y$10$kx2I5RYQe76HSrAmNV2PxOw1FE1ZaRoyW5BOJploHsM9sKgXCiIL.', 'ndiaye', '222222222', '222222222', '222222222', 'recto_1752093720_686ed4184c83e.png', 'verso_1752093720_686ed4184c8de.png', '2025-07-09 20:42:00', '2025-07-09 20:42:00'),
(29, 'TestRedirection2', 'testredirection2@example.com', '$2y$10$xHqHyOO0D0a2ba2ESBpo0.7wVK6qVFVHpPeEXXf/8fe9VEiFC7xaK', 'User', '123456789', 'REDIR21752094305', '123 Redirection Street', 'test_recto.jpg', 'test_verso.jpg', '2025-07-09 20:51:45', '2025-07-09 20:51:45'),
(30, 'hadim', 'hadim@gmail.com', '$2y$10$2AYktM7pbNpnUR6EXJlkJew9yJDPvnEtQ9pXGAqSp8e3VxB.5MFCe', 'ndiaye', '222222222', '2222222222', '222222222', 'recto_1752094452_686ed6f4f1fc6.png', 'verso_1752094452_686ed6f4f207b.png', '2025-07-09 20:54:13', '2025-07-09 20:54:13'),
(31, 'Diallo', 'aldimia@gmail.com', '$2y$10$k6V/iBCnMzzaPOGdXSkPoefBEspebIDIE8pOwwremKsD0dBeCY3oa', 'Aldimia', '778021529', '1755200001417', 'Pikine', 'recto_1752097872_686ee450732c2.png', 'verso_1752097872_686ee45073727.png', '2025-07-09 21:51:12', '2025-07-09 21:51:12'),
(32, 'modou', 'modou@gmail.com', '$2y$10$0K87P0CA2BD2puus7No6vOAs1mOtzVeK07GUzhkW5OYz9rSghZ1UW', 'diop', '777834409', '22222222223', '777', 'recto_1752143292_686f95bc814a4.png', 'verso_1752143292_686f95bc8160c.png', '2025-07-10 10:28:12', '2025-07-10 10:28:12'),
(33, 'papa', 'papa', '$2y$10$cqcQuJ6mDW2AMh3WEl3T8OsVyhomVTii52KKOc.bmxJAX1pW1CXT2', 'diop', '777878787', '1757200100662', '22266', 'recto_1752161947_686fde9b9391c.png', 'verso_1752161947_686fde9b939dd.png', '2025-07-10 15:39:07', '2025-07-10 15:39:07'),
(35, 'diallo', 'alou', '$2y$10$ob/pfewIoCw5BNlIklxmdeO7tCSIFh7.iiNF30agT2UHbZEJpbtx2', 'alassane', '781562041', '1757200100667', 'alou', 'recto_1752224957_6870d4bd6fdf1.png', 'verso_1752224957_6870d4bd6feb8.png', '2025-07-11 09:09:17', '2025-07-11 09:09:17'),
(37, 'diop', 'paul', '$2y$10$.BJmzloPJCZh/9dMTuELhu1u5PpJhl.Dy7JUjDizOhvg.jLC8U5Ou', 'paul', '779921333', '1757200100663', 'paul', 'recto_1752226485_6870dab59e2cc.png', 'verso_1752226485_6870dab59e371.png', '2025-07-11 09:34:45', '2025-07-11 09:34:45'),
(44, 'TestSMS', 'rapid_1752239008', '$2y$10$AoQo.Ci3AOSzwttJS.5ZIeHhWSxkiewb/n1l1Kns2abG7xbvRSLlG', 'Rapide', '773410672', '123456789012', 'Test', 'debug_recto.jpg', 'debug_verso.jpg', '2025-07-11 13:03:28', '2025-07-11 13:03:28'),
(45, 'diop', 'paul2', '$2y$10$1o30vf9/5shaJae/ho.rB.UJdsJg5BiuBgTqUVxQILu1mEWGrMsPy', 'paul', '773410672', '1757200100777', 'paul', 'debug_recto.jpg', 'debug_verso.jpg', '2025-07-11 13:05:58', '2025-07-11 13:05:58');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `idx_compte_numero` (`numero`),
  ADD KEY `idx_compte_utilisateur` (`utilisateur_id`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction_compte` (`compte_id`),
  ADD KEY `idx_transaction_date` (`date`),
  ADD KEY `idx_transaction_type` (`type`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `idx_utilisateur_login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `compte` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;