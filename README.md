================================================================
  GestionEDT — Application Web de Gestion des Emplois du Temps
  Mini Projet Développement Web — MGSI
  ENSIASD Taroudant — 2025/2026
================================================================

Réalisé par : Nassraoui Nada
                Boudrari Abdelouahed
Encadré par  : M. LAASSEM
Filière      : Management et Gouvernance des Systèmes d'Information


----------------------------------------------------------------
  1. PRÉSENTATION
----------------------------------------------------------------

Application web permettant de gérer les emplois du temps
scolaires : filières, semestres, enseignants, salles, modules,
créneaux horaires et plannings.

L'application gère trois rôles :
  - Administrateur : gestion complète + statistiques
  - Enseignant    : consultation de son planning personnel
  - Étudiant      : consultation du planning de sa filière


----------------------------------------------------------------
  2. TECHNOLOGIES UTILISÉES
----------------------------------------------------------------

  - HTML5 / CSS3
  - JavaScript (Chart.js pour les graphiques)
  - PHP 8 (procédural)
  - MySQL avec PDO (requêtes préparées)


----------------------------------------------------------------
  3. INSTALLATION LOCALE (XAMPP / WAMP / LAMP)
----------------------------------------------------------------

  1. Copier le dossier du projet dans le répertoire web :
       - XAMPP : C:\xampp\htdocs\
       - WAMP  : C:\wamp64\www\
       - LAMP  : /var/www/html/

  2. Démarrer Apache et MySQL.

  3. Ouvrir phpMyAdmin (http://localhost/phpmyadmin).

  4. Créer une base de données nommée :
       gestion_emploi_mgsi

  5. Importer le fichier projet.sql dans cette base.
     (Onglet "Importer" -> Choisir projet.sql -> Exécuter)

  6. Vérifier la configuration de connexion dans config.php :

       $localhost = "localhost";
       $username  = "root";
       $password  = "";
       $dbname    = "gestion_emploi_mgsi";

  7. Ouvrir l'application dans le navigateur :
       http://localhost/NOM_DU_DOSSIER/


----------------------------------------------------------------
  4. COMPTES DE TEST
----------------------------------------------------------------

  *** ADMINISTRATEUR (compte par défaut requis) ***
       Login    : ENSIASD
       Password : ENSIASD2026

  *** ENSEIGNANT ***
       Login    : prof_ahmed
       Password : 123456

       Login    : prof_sara
       Password : 123456

  *** ÉTUDIANT ***
       Login    : etudiant_mgsi_s1
       Password : 123456

       Login    : etudiant_gi_s1
       Password : 123456


----------------------------------------------------------------
  5. STRUCTURE DU PROJET
----------------------------------------------------------------

  /                       Racine du projet
    index.php             Page d'accueil (Landing Page)
    config.php            Connexion à la base de données (PDO)
    projet.sql            Export complet de la base
    README.txt            Ce fichier

  /css/                   Feuilles de style
    style.css

  /js/                    Scripts JavaScript
    script.js

  /images/                Images et ressources visuelles

  /includes/              Fichiers réutilisables
    navbar.php            Barre de navigation interne
    footer.php            Pied de page

  /pages/                 Pages de l'application
    login.php             Connexion + Inscription
    logout.php            Déconnexion
    dashboard.php         Tableau de bord (admin/enseignant/étudiant)
    filieres.php          Gestion des filières
    semestres.php         Gestion des semestres
    enseignants.php       Gestion des enseignants
    salles.php            Gestion des salles
    modules.php           Gestion des modules
    creneaux.php          Gestion des créneaux horaires
    emploi.php            Création des emplois du temps
    planning_etudiant.php Consultation planning étudiant
    planning_enseignant.php Consultation planning enseignant

  /doc/                   Documentation
    Fiche.pdf             Fiche d'évaluation
    captures.pdf          Rapport des captures d'écran
    /captures/            Captures de toutes les pages
    /diagrammes/          MCD et MLD


----------------------------------------------------------------
  6. FONCTIONNALITÉS PRINCIPALES
----------------------------------------------------------------

  - Authentification avec gestion de rôles (admin/enseignant/étudiant)
  - Inscription d'étudiants avec indicateur de force du mot de passe
  - CRUD complet : filières, semestres, enseignants, salles,
    modules, créneaux
  - Création des séances avec détection automatique des conflits
    (salle, enseignant ou classe déjà occupé au même créneau)
  - Tableau de bord administrateur avec graphiques Chart.js
    (séances par jour, répartition par type Cours/TD/TP)
  - Consultation des plannings filtrée par rôle
  - Impression et export PDF des plannings
  - Politique de confidentialité et mentions légales
  - Interface responsive (mobile, tablette, ordinateur)


----------------------------------------------------------------
  7. SÉCURITÉ
----------------------------------------------------------------

  - Mots de passe hachés avec password_hash() (bcrypt)
  - Vérification avec password_verify()
  - Requêtes SQL préparées (PDO) contre l'injection SQL
  - Échappement des sorties HTML avec htmlspecialchars()
  - Sessions PHP pour la gestion de l'authentification
  - Validation côté serveur de tous les formulaires


----------------------------------------------------------------
  8. HÉBERGEMENT EN LIGNE
----------------------------------------------------------------

  Démo en ligne : http://gestion-edt.page.gd
  Hébergeur     : InfinityFree (PHP + MySQL)


================================================================
  Fin du fichier README.txt
================================================================
