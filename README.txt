================================================================
                          GestionEDT
       Application Web de Gestion des Emplois du Temps
================================================================

  Mini Projet — Développement Web
  Filière : Management et Gouvernance des Systèmes d'Information
  ENSIASD Taroudant — Année universitaire 2025/2026

  Réalisé par :  Nassraoui Nada
                 Boudrari Abdelouahed

  Encadré par :  M. LAASSEM


================================================================
  TABLE DES MATIÈRES
================================================================

  1. Présentation du projet
  2. Technologies utilisées
  3. Prérequis
  4. Installation locale
  5. Comptes de test
  6. Structure du projet
  7. Fonctionnalités principales
  8. Sécurité
  9. Hébergement en ligne
  10. Code source
  11. Notes importantes


================================================================
  1. PRÉSENTATION DU PROJET
================================================================

GestionEDT est une application web qui permet de gérer les
emplois du temps scolaires de manière simple et efficace. Elle
centralise la gestion des filières, semestres, enseignants,
salles, modules et créneaux horaires, et permet la création de
plannings sans conflit grâce à un système de détection
automatique.

L'application gère trois rôles distincts :

  - Administrateur  : gestion complète + statistiques visuelles
  - Enseignant      : consultation de son planning personnel
  - Étudiant        : consultation du planning de sa filière


================================================================
  2. TECHNOLOGIES UTILISÉES
================================================================

  Frontend
  --------
    HTML5
    CSS3 (custom, responsive design)
    JavaScript (vanilla)
    Chart.js 4.4.0 (visualisation des données)

  Backend
  -------
    PHP 8 (procédural)
    PDO (PHP Data Objects)

  Base de données
  ---------------
    MySQL / MariaDB


================================================================
  3. PRÉREQUIS
================================================================

  - Serveur local (XAMPP, WAMP, MAMP ou LAMP)
  - PHP 7.4 ou supérieur (PHP 8 recommandé)
  - MySQL 5.7 ou supérieur (ou MariaDB 10.x)
  - Navigateur moderne (Chrome, Firefox, Edge, Safari)
  - Connexion Internet (pour Chart.js via CDN)


================================================================
  4. INSTALLATION LOCALE
================================================================

  Étape 1 — Placer le projet dans le serveur web
  ----------------------------------------------
  Copier le dossier du projet dans le répertoire web :

    XAMPP    ->  C:\xampp\htdocs\
    WAMP     ->  C:\wamp64\www\
    MAMP     ->  /Applications/MAMP/htdocs/
    LAMP     ->  /var/www/html/

  Étape 2 — Démarrer les services
  --------------------------------
  Lancer Apache et MySQL depuis le panneau de contrôle.

  Étape 3 — Importer la base de données
  --------------------------------------
  Ouvrir phpMyAdmin :  http://localhost/phpmyadmin

  Cliquer sur "Importer", choisir le fichier projet.sql, puis
  cliquer sur "Exécuter".

  Le script crée automatiquement la base de données
  "if0_41819712_gestionedt" et insère toutes les données de test.

  Étape 4 — Vérifier la configuration (config.php)
  -------------------------------------------------
  Ouvrir le fichier config.php à la racine du projet et vérifier
  que les paramètres correspondent à votre serveur :

      $localhost = "localhost";
      $username  = "root";
      $password  = "";
      $dbname    = "if0_41819712_gestionedt";

  Étape 5 — Lancer l'application
  -------------------------------
  Ouvrir le navigateur à l'adresse :

      http://localhost/NOM_DU_DOSSIER/

  La page d'accueil s'affiche. Cliquer sur "Connexion" pour
  accéder à l'espace utilisateur.


================================================================
  5. COMPTES DE TEST
================================================================

  ┌─────────────────────────────────────────────────────────┐
  │  ADMINISTRATEUR  (compte par défaut requis)             │
  ├─────────────────────────────────────────────────────────┤
  │     Login     :  ENSIASD                                │
  │     Password  :  ENSIASD2026                            │
  └─────────────────────────────────────────────────────────┘

  ┌─────────────────────────────────────────────────────────┐
  │  ENSEIGNANTS                                            │
  ├─────────────────────────────────────────────────────────┤
  │     Login     :  prof_boudhane                          │
  │     Password  :  123456                                 │
  │                                                         │
  │     Login     :  prof_brahim                            │
  │     Password  :  123456                                 │
  └─────────────────────────────────────────────────────────┘

  ┌─────────────────────────────────────────────────────────┐
  │  ÉTUDIANTS                                              │
  ├─────────────────────────────────────────────────────────┤
  │     Login     :  etudiant_mgsi_s1                       │
  │     Password  :  123456                                 │
  │                                                         │
  │     Login     :  etudiant_il_s1                         │
  │     Password  :  123456                                 │
  └─────────────────────────────────────────────────────────┘


================================================================
  6. STRUCTURE DU PROJET
================================================================

  EMPLOIS_MANAGE/
  │
  ├── index.php                     Page d'accueil (Landing Page)
  ├── config.php                    Connexion à la base (PDO)
  ├── projet.sql                    Export complet de la base
  ├── README.txt                    Ce fichier
  │
  ├── css/
  │   └── style.css                 Feuille de style principale
  │
  ├── js/
  │   └── script.js                 Scripts JavaScript
  │
  ├── images/                       Ressources visuelles
  │
  ├── includes/
  │   ├── navbar.php                Barre de navigation interne
  │   └── footer.php                Pied de page
  │
  ├── pages/
  │   ├── login.php                 Connexion + Inscription
  │   ├── logout.php                Déconnexion
  │   ├── dashboard.php             Tableau de bord (3 rôles)
  │   ├── filieres.php              Gestion des filières
  │   ├── semestres.php             Gestion des semestres
  │   ├── enseignants.php           Gestion des enseignants
  │   ├── salles.php                Gestion des salles
  │   ├── modules.php               Gestion des modules
  │   ├── creneaux.php              Gestion des créneaux horaires
  │   ├── emploi.php                Création des emplois du temps
  │   ├── planning_etudiant.php     Planning étudiant
  │   └── planning_enseignant.php   Planning enseignant
  │
  └── doc/
      ├── fiche.pdf                 Fiche d'évaluation
      ├── Capture.pdf               Rapport des captures
      ├── captures/                 Captures d'écran
      └── diagrammes/               MCD et MLD


================================================================
  7. FONCTIONNALITÉS PRINCIPALES
================================================================

  Authentification & Comptes
  --------------------------
  - Connexion sécurisée avec gestion de rôles
  - Inscription des nouveaux étudiants
  - Indicateur de force du mot de passe en temps réel
  - Vérification de correspondance des mots de passe
  - Validation côté serveur (email, longueur, unicité)

  Tableau de bord
  ---------------
  - Statistiques en cartes (filières, enseignants, salles, séances)
  - Graphique en barres : séances par jour de la semaine
  - Graphique circulaire : répartition Cours / TD / TP
  - Actions rapides vers toutes les pages de gestion

  Gestion (CRUD complet)
  ----------------------
  - Filières, semestres, enseignants, salles
  - Modules, créneaux horaires
  - Emplois du temps avec association multi-critères

  Détection des conflits
  ----------------------
  - Conflits de salle (même salle, même créneau)
  - Conflits d'enseignant (même prof, même créneau)
  - Conflits de classe (même filière/semestre, même créneau)

  Plannings
  ---------
  - Planning étudiant filtré par filière et semestre
  - Planning enseignant filtré par enseignant
  - Filtrage des séances par filière
  - Impression et export PDF via le navigateur

  Pages publiques
  ---------------
  - Landing page complète et responsive
  - Formulaire de contact
  - Politique de confidentialité et mentions légales
  - Pied de page structuré sur toutes les pages

  Compatibilité
  -------------
  - Interface 100% responsive (mobile, tablette, ordinateur)
  - Tableaux à défilement horizontal sur petits écrans
  - Graphiques adaptatifs


================================================================
  8. SÉCURITÉ
================================================================

  - Mots de passe hachés avec password_hash() (algorithme bcrypt)
  - Vérification avec password_verify()
  - Toutes les requêtes SQL utilisent des requêtes préparées (PDO)
    -> protection contre les injections SQL
  - Échappement HTML avec htmlspecialchars()
    -> protection contre les attaques XSS
  - Sessions PHP pour la gestion de l'authentification
  - Validation côté serveur de tous les formulaires
  - Vérification systématique du rôle avant chaque action sensible


================================================================
  9. HÉBERGEMENT EN LIGNE
================================================================

  Démo en ligne  :  http://gestion-edt.page.gd
  Hébergeur      :  InfinityFree (PHP 8 + MySQL)


================================================================
  10. CODE SOURCE
================================================================

  Le code source complet du projet est disponible à deux endroits :

  1. Dans l'archive remise
  -------------------------
  Tout le code se trouve dans le dossier :

      emplois_manage_mgsi.zip

  Il suffit de décompresser cette archive pour obtenir l'intégralité
  du projet (voir section 4 pour l'installation locale).

  2. Sur GitHub (dépôt public)
  -----------------------------
  Le projet est également disponible en ligne sur GitHub :

      https://github.com/Abdelouahedb/Gestion-EDT

  Pour cloner le dépôt en ligne de commande :

      git clone https://github.com/Abdelouahedb/Gestion-EDT.git

  Le dépôt contient l'historique complet des contributions des
  deux membres du binôme.

================================================================
  11. NOTES IMPORTANTES
================================================================

  - Le projet utilise Chart.js via CDN. Une connexion Internet
    est nécessaire pour afficher les graphiques du tableau de bord.

  - Le compte administrateur "ENSIASD / ENSIASD2026" est inséré
    automatiquement lors de l'import de projet.sql, conformément
    aux instructions du livrable.

  - Les mots de passe des comptes de test sont volontairement
    simples ("123456") pour faciliter l'évaluation. En production,
    ils devraient être renforcés.

  - Aucune installation de bibliothèque PHP supplémentaire n'est
    requise (pas de Composer, pas de framework).


================================================================
                    Fin du fichier README.txt
================================================================