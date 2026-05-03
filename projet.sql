-- Active: 1728569797883@@127.0.0.1@3306@gestion_emploi_mgsi
CREATE DATABASE IF NOT EXISTS gestion_emploi_mgsi;

USE gestion_emploi_mgsi;

-- =========================
-- TABLE: filieres
-- =========================
CREATE TABLE filieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- =========================
-- TABLE: semestres
-- =========================
CREATE TABLE semestres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- =========================
-- TABLE: enseignants
-- =========================
CREATE TABLE enseignants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(20)
);

-- =========================
-- TABLE: salles
-- =========================
CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    capacite INT DEFAULT 30
);

-- =========================
-- TABLE: modules
-- =========================
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    code_module VARCHAR(30) UNIQUE,
    description TEXT
);

-- =========================
-- TABLE: creneaux
-- =========================
CREATE TABLE creneaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL
);

-- =========================
-- TABLE: users
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(100) NOT NULL,
    login VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('admin', 'enseignant', 'etudiant') NOT NULL,

    filiere_id INT NULL,
    semestre_id INT NULL,
    enseignant_id INT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (filiere_id) REFERENCES filieres(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (semestre_id) REFERENCES semestres(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (enseignant_id) REFERENCES enseignants(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================
-- TABLE: emplois
-- =========================

CREATE TABLE emplois (
    id INT AUTO_INCREMENT PRIMARY KEY,

    filiere_id INT NULL,
    semestre_id INT NULL,
    module_id INT NULL,
    enseignant_id INT NULL,
    salle_id INT NULL,
    creneau_id INT NULL,

    jour ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi') NOT NULL,
    type_cours ENUM('Cours', 'TD', 'TP') DEFAULT 'Cours',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (filiere_id) REFERENCES filieres(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (semestre_id) REFERENCES semestres(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (module_id) REFERENCES modules(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (enseignant_id) REFERENCES enseignants(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (salle_id) REFERENCES salles(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (creneau_id) REFERENCES creneaux(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    UNIQUE KEY unique_salle_creneau_jour (jour, creneau_id, salle_id),

    UNIQUE KEY unique_enseignant_creneau_jour (jour, creneau_id, enseignant_id),

    UNIQUE KEY unique_classe_creneau_jour (jour, creneau_id, filiere_id, semestre_id)
);

-- =========================
-- TABLE: contacts
-- =========================
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    sujet VARCHAR(150),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- DATA: filieres
-- =========================
INSERT INTO filieres (nom, description) VALUES
('MGSI', 'Management et Gouvernance des Systèmes d’Information'),
('GI', 'Génie Informatique'),
('IA', 'Intelligence Artificielle'),
('DATA', 'Science des Données');

-- =========================
-- DATA: semestres
-- =========================
INSERT INTO semestres (nom) VALUES
('S1'),
('S2'),
('S3'),
('S4');

-- =========================
-- DATA: enseignants
-- =========================
INSERT INTO enseignants (nom, email, telephone) VALUES
('Pr. Ahmed El Amrani', 'ahmed.elamrani@ensiasd.ma', '0600000001'),
('Pr. Sara Bennani', 'sara.bennani@ensiasd.ma', '0600000002'),
('Pr. Youssef Idrissi', 'youssef.idrissi@ensiasd.ma', '0600000003'),
('Pr. Meryem Alaoui', 'meryem.alaoui@ensiasd.ma', '0600000004'),
('Pr. Omar Lahlou', 'omar.lahlou@ensiasd.ma', '0600000005');

-- =========================
-- DATA: salles
-- =========================
INSERT INTO salles (nom, capacite) VALUES
('Salle 101', 35),
('Salle 102', 35),
('Salle TP Info', 25),
('Amphi A', 100),
('Salle 204', 40);

-- =========================
-- DATA: modules
-- =========================
INSERT INTO modules (nom, code_module, description) VALUES
('Développement Web', 'WEB101', 'HTML, CSS, JavaScript, PHP et MySQL'),
('Bases de Données', 'BDD101', 'Modélisation, SQL et MySQL'),
('Programmation Orientée Objet', 'POO101', 'Concepts de la POO avec Java'),
('Management des SI', 'MSI101', 'Management et gouvernance des systèmes d’information'),
('Réseaux Informatiques', 'RES101', 'Notions de base des réseaux informatiques');

-- =========================
-- DATA: creneaux
-- =========================
INSERT INTO creneaux (heure_debut, heure_fin) VALUES
('08:30:00', '10:30:00'),
('10:30:00', '12:30:00'),
('14:00:00', '16:00:00'),
('16:00:00', '18:00:00');

-- =========================
-- DATA: users
-- Passwords are hashed.
--
-- Admin:
-- Login: ENSIASD
-- Password: ENSIASD2026
--
-- Other accounts:
-- Password: 123456
-- =========================
INSERT INTO users 
(nom, login, email, password, role, filiere_id, semestre_id, enseignant_id) 
VALUES
(
    'Administrateur ENSIASD',
    'ENSIASD',
    'admin@ensiasd.ma',
    '$2y$12$H8z0ZOs4RHOQ6Zbgvj21R..G6EoVMHJOC8dwV5ySK1MO8/y8fZUfq',
    'admin',
    NULL,
    NULL,
    NULL
),
(
    'Pr. Ahmed El Amrani',
    'prof_ahmed',
    'ahmed.user@ensiasd.ma',
    '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6',
    'enseignant',
    NULL,
    NULL,
    1
),
(
    'Pr. Sara Bennani',
    'prof_sara',
    'sara.user@ensiasd.ma',
    '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6',
    'enseignant',
    NULL,
    NULL,
    2
),
(
    'Etudiant MGSI S1',
    'etudiant_mgsi_s1',
    'etudiant1@ensiasd.ma',
    '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6',
    'etudiant',
    1,
    1,
    NULL
),
(
    'Etudiant GI S1',
    'etudiant_gi_s1',
    'etudiant2@ensiasd.ma',
    '$2y$12$HoZ6QA1VQpqU3WYhcqlUD.j4f3G2eCYfK74k6U1hedfSRZGdT7yL6',
    'etudiant',
    2,
    1,
    NULL
);

-- =========================
-- DATA: emplois
-- =========================
INSERT INTO emplois 
(filiere_id, semestre_id, module_id, enseignant_id, salle_id, creneau_id, jour, type_cours) 
VALUES
(1, 1, 1, 1, 1, 1, 'Lundi', 'Cours'),
(1, 1, 2, 2, 2, 2, 'Lundi', 'TD'),
(1, 1, 3, 3, 3, 3, 'Mardi', 'TP'),
(2, 1, 1, 4, 4, 1, 'Mardi', 'Cours'),
(2, 1, 5, 5, 5, 2, 'Mercredi', 'Cours'),
(3, 2, 2, 1, 1, 3, 'Jeudi', 'TD'),
(4, 2, 4, 2, 2, 4, 'Vendredi', 'Cours');

-- =========================
-- DATA: contacts
-- =========================
INSERT INTO contacts (nom, email, sujet, message) VALUES
('Nada Nassraoui', 'nada@example.com', 'Question planning', 'Je veux consulter mon emploi du temps.'),
('Abdelouahed Boudrari', 'abdelouahed@example.com', 'Problème connexion', 'Je ne peux pas accéder au tableau de bord.'),
('Etudiant Test', 'test@example.com', 'Demande information', 'Quand sera publié le nouveau planning ?'),
('Professeur Test', 'prof@example.com', 'Modification séance', 'Je veux modifier une séance dans mon planning.');

SELECT * FROM creneaux;

-- ALTER DATABASE gestion_emploi_mgsi
-- CHARACTER SET utf8mb4
-- COLLATE utf8mb4_general_ci;
-- 
-- ALTER TABLE filieres CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE semestres CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE enseignants CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE salles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE modules CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE creneaux CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE emplois CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE contacts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;