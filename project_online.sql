-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.infinityfree.com
-- Generation Time: May 04, 2026 at 07:51 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41819712_gestionedt`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sujet` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `nom`, `email`, `sujet`, `message`, `created_at`) VALUES
(1, 'Nada Nassraoui', 'nada@example.com', 'Question planning', 'Je veux consulter mon emploi du temps.', '2026-05-03 15:11:00'),
(2, 'Abdelouahed Boudrari', 'abdelouahed@example.com', 'Problème connexion', 'Je ne peux pas accéder au tableau de bord.', '2026-05-03 15:11:00'),
(3, 'Etudiant Test', 'test@example.com', 'Demande information', 'Quand sera publié le nouveau planning ?', '2026-05-03 15:11:00'),
(4, 'Professeur Test', 'prof@example.com', 'Modification séance', 'Je veux modifier une séance dans mon planning.', '2026-05-03 15:11:00'),
(5, 'Test', 'Test@gmail.com', 'Test', 'Test', '2026-05-04 12:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `creneaux`
--

CREATE TABLE `creneaux` (
  `id` int(11) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creneaux`
--

INSERT INTO `creneaux` (`id`, `heure_debut`, `heure_fin`) VALUES
(1, '08:30:00', '10:30:00'),
(2, '10:30:00', '12:30:00'),
(3, '14:00:00', '16:00:00'),
(4, '16:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `emplois`
--

CREATE TABLE `emplois` (
  `id` int(11) NOT NULL,
  `filiere_id` int(11) DEFAULT NULL,
  `semestre_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `enseignant_id` int(11) DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `creneau_id` int(11) DEFAULT NULL,
  `jour` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') NOT NULL,
  `type_cours` enum('Cours','TD','TP') DEFAULT 'Cours',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emplois`
--

INSERT INTO `emplois` (`id`, `filiere_id`, `semestre_id`, `module_id`, `enseignant_id`, `salle_id`, `creneau_id`, `jour`, `type_cours`, `created_at`) VALUES
(1, 2, 1, 1, 1, 11, 1, 'Vendredi', 'Cours', '2026-05-03 15:46:21'),
(2, 4, 2, 3, 5, 5, 2, 'Mardi', 'TP', '2026-05-03 15:48:28'),
(3, 1, 1, 2, 2, 9, 4, 'Jeudi', 'TD', '2026-05-03 15:50:12'),
(4, 2, 1, 5, 3, 16, 3, 'Lundi', 'Cours', '2026-05-03 15:51:47');

-- --------------------------------------------------------

--
-- Table structure for table `enseignants`
--

CREATE TABLE `enseignants` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enseignants`
--

INSERT INTO `enseignants` (`id`, `nom`, `email`, `telephone`) VALUES
(1, 'Pr. Brahim Laasem', 'b.laasem@ensiasd.ma', '0600000001'),
(2, 'Pr.Mohsine Boudhane', 'm.boudhane@ensiasd.ma', '0600000002'),
(3, 'Pr. Mohammed Kasri', 'm.kasri@ensiasd.ma', '0600000003'),
(4, 'Pr. SAID Ait Temghart', 's.aittemghart@ensiasd.ma', '0600000004'),
(5, 'Pr. Hind Ait Mait', 'h.aitmait@ensiasd.ma', '0600000005');

-- --------------------------------------------------------

--
-- Table structure for table `filieres`
--

CREATE TABLE `filieres` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filieres`
--

INSERT INTO `filieres` (`id`, `nom`, `description`) VALUES
(1, 'MGSI', 'Management et Gouvernance des Systèmes d’Information'),
(2, 'IL', 'Ingénierie Logiciel'),
(3, 'SITCN', 'Sécurité IT et Confiance Numérique'),
(4, 'SDBDIA', 'Sciences des Données, Big Data & IA');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `code_module` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `nom`, `code_module`, `description`) VALUES
(1, 'Développement Web', 'WEB101', 'HTML, CSS, JavaScript, PHP et MySQL'),
(2, 'Bases de Données', 'BDD101', 'Modélisation, SQL et MySQL'),
(3, 'Programmation Orientée Objet', 'POO101', 'Concepts de la POO avec Java'),
(4, 'Recherche operationnelle', 'MSI101', 'Recherche operationnelle Ro'),
(5, 'Structure de données', 'RES101', 'Notions de base des Structure de données');

-- --------------------------------------------------------

--
-- Table structure for table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `capacite` int(11) DEFAULT 30,
  `type_salle` enum('Salle normale','Salle TP','Amphi') DEFAULT 'Salle normale'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salles`
--

INSERT INTO `salles` (`id`, `nom`, `capacite`, `type_salle`) VALUES
(1, 'Salle 1', 35, 'Salle TP'),
(2, 'Salle 2', 35, 'Salle TP'),
(3, 'Salle 3', 35, 'Salle TP'),
(4, 'Salle 4', 35, 'Salle TP'),
(5, 'Salle 5', 35, 'Salle TP'),
(6, 'Salle 6', 30, 'Salle TP'),
(7, 'Salle 7', 60, 'Salle normale'),
(8, 'Salle 8', 60, 'Salle normale'),
(9, 'Salle 9', 60, 'Salle normale'),
(10, 'Salle 10', 60, 'Salle normale'),
(11, 'Salle 11', 60, 'Salle normale'),
(12, 'Salle 12', 60, 'Salle normale'),
(13, 'Salle 13', 60, 'Salle normale'),
(14, 'Salle 14', 60, 'Salle normale'),
(15, 'Amphi 1', 120, 'Amphi'),
(16, 'Amphi 2', 120, 'Amphi'),
(17, 'Amphi 3', 120, 'Amphi');

-- --------------------------------------------------------

--
-- Table structure for table `semestres`
--

CREATE TABLE `semestres` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semestres`
--

INSERT INTO `semestres` (`id`, `nom`) VALUES
(1, 'S1'),
(2, 'S2'),
(3, 'S3'),
(4, 'S4');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','enseignant','etudiant') NOT NULL,
  `filiere_id` int(11) DEFAULT NULL,
  `semestre_id` int(11) DEFAULT NULL,
  `enseignant_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `login`, `email`, `password`, `role`, `filiere_id`, `semestre_id`, `enseignant_id`, `created_at`) VALUES
(1, 'Administrateur ENSIASD', 'ENSIASD', 'admin@ensiasd.ma', '$2y$12$H8z0ZOs4RHOQ6Zbgvj21R..G6EoVMHJOC8dwV5ySK1MO8/y8fZUfq', 'admin', NULL, NULL, NULL, '2026-05-03 15:11:00'),
(2, 'Pr. Brahim Laasem', 'prof_brahim', 'b.laasem@ensiasd.ma', '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6', 'enseignant', NULL, NULL, 1, '2026-05-03 15:11:00'),
(3, 'Pr. Mohsine Boudhane', 'prof_boudhane', 'm.boudhane@ensiasd.ma', '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6', 'enseignant', NULL, NULL, 2, '2026-05-03 15:11:00'),
(4, 'Etudiant MGSI S1', 'etudiant_mgsi_s1', 'etudiant1@ensiasd.ma', '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6', 'etudiant', 1, 1, NULL, '2026-05-03 15:11:00'),
(5, 'Etudiant IL S1', 'etudiant_il_s1', 'etudiant2@ensiasd.ma', '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6', 'etudiant', 2, 1, NULL, '2026-05-03 15:11:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `creneaux`
--
ALTER TABLE `creneaux`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emplois`
--
ALTER TABLE `emplois`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_salle_creneau_jour` (`jour`,`creneau_id`,`salle_id`),
  ADD UNIQUE KEY `unique_enseignant_creneau_jour` (`jour`,`creneau_id`,`enseignant_id`),
  ADD UNIQUE KEY `unique_classe_creneau_jour` (`jour`,`creneau_id`,`filiere_id`,`semestre_id`),
  ADD KEY `filiere_id` (`filiere_id`),
  ADD KEY `semestre_id` (`semestre_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `enseignant_id` (`enseignant_id`),
  ADD KEY `salle_id` (`salle_id`),
  ADD KEY `creneau_id` (`creneau_id`);

--
-- Indexes for table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `filieres`
--
ALTER TABLE `filieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_module` (`code_module`);

--
-- Indexes for table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `semestres`
--
ALTER TABLE `semestres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `filiere_id` (`filiere_id`),
  ADD KEY `semestre_id` (`semestre_id`),
  ADD KEY `enseignant_id` (`enseignant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `creneaux`
--
ALTER TABLE `creneaux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `emplois`
--
ALTER TABLE `emplois`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `filieres`
--
ALTER TABLE `filieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `semestres`
--
ALTER TABLE `semestres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emplois`
--
ALTER TABLE `emplois`
  ADD CONSTRAINT `emplois_ibfk_1` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `emplois_ibfk_2` FOREIGN KEY (`semestre_id`) REFERENCES `semestres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `emplois_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `emplois_ibfk_4` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `emplois_ibfk_5` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `emplois_ibfk_6` FOREIGN KEY (`creneau_id`) REFERENCES `creneaux` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`semestre_id`) REFERENCES `semestres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
