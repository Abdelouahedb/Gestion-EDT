# Gestion des Emplois du Temps Scolaires

Mini application web réalisée dans le cadre du module de Développement Web.

L’objectif du projet est de gérer les emplois du temps scolaires selon les filières, les semestres, les enseignants, les salles et les créneaux horaires.

## Technologies utilisées

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- PDO
- phpMyAdmin
- XAMPP
- InfinityFree pour l’hébergement

## Fonctionnalités principales

- Page d’accueil responsive
- Authentification des utilisateurs
- Tableau de bord selon le rôle
- Gestion des filières
- Gestion des semestres
- Gestion des enseignants
- Gestion des salles
- Gestion des modules
- Gestion des créneaux horaires
- Création des emplois du temps
- Détection des conflits d’horaires
- Planning personnalisé pour les étudiants
- Planning personnalisé pour les enseignants
- Impression et export PDF via le navigateur

## Rôles utilisateurs

### Administrateur

L’administrateur peut gérer toutes les données de l’application :

- Filières
- Semestres
- Enseignants
- Salles
- Modules
- Créneaux
- Emplois du temps
- Consultation des plannings

### Enseignant

L’enseignant peut se connecter et consulter son emploi du temps personnel.

### Étudiant

L’étudiant peut se connecter et consulter le planning de sa filière et de son semestre.

## Comptes de test

### Administrateur

```txt
Login : ENSIASD
Mot de passe : ENSIASD2026
```

### Enseignant

```txt
Login : prof.web
Mot de passe : 123456
```

### Étudiant

```txt
Login : etudiant.mgsi
Mot de passe : 123456
```

## Installation locale

### 1. Copier le projet dans XAMPP

Copier le dossier du projet dans le dossier `htdocs` de XAMPP.

Exemple :

```txt
C:/xampp/htdocs/EMPLOIS_MANAGE
```

### 2. Lancer les services

Ouvrir XAMPP puis lancer :

```txt
Apache
MySQL
```

### 3. Créer la base de données

Ouvrir phpMyAdmin :

```txt
http://localhost/phpmyadmin
```

Créer une base de données :

```sql
gestion_emploi_mgsi
```

### 4. Importer la base de données

Importer le fichier SQL :

```txt
projet.sql
```

### 5. Configurer la connexion

Dans le fichier `config.php`, utiliser la configuration locale suivante :

```php
$host = "localhost";
$dbname = "gestion_emploi_mgsi";
$username = "root";
$password = "";
```

### 6. Lancer l’application

Ouvrir le projet dans le navigateur :

```txt
http://localhost/EMPLOIS_MANAGE/
```

## Structure du projet

```txt
EMPLOIS_MANAGE/
│
├── index.php
├── config.php
├── projet.sql
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│
├── includes/
│   ├── navbar.php
│   ├── header.php
│   └── footer.php
│
├── pages/
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── filieres.php
│   ├── semestres.php
│   ├── enseignants.php
│   ├── salles.php
│   ├── modules.php
│   ├── creneaux.php
│   ├── emploi.php
│   ├── planning_etudiant.php
│   └── planning_enseignant.php
│
└── doc/
    ├── captures/
    └── diagrammes/
```

## Déploiement

Le projet peut être déployé sur un hébergement supportant PHP et MySQL.

Dans ce projet, l’hébergement utilisé est InfinityFree.

### Étapes de déploiement

1. Créer un compte InfinityFree.
2. Créer un hébergement gratuit.
3. Uploader les fichiers du projet dans le dossier `htdocs`.
4. Créer une base de données MySQL.
5. Importer le fichier SQL avec phpMyAdmin.
6. Modifier le fichier `config.php` avec les informations de la base de données distante.
7. Tester l’application en ligne.

Exemple de configuration pour InfinityFree :

```php
$host = "sqlXXX.infinityfree.com";
$dbname = "if0_xxxxxxxx_gestionedt";
$username = "if0_xxxxxxxx";
$password = "votre_mot_de_passe";
```

## Sécurité

Les mots de passe sont stockés sous forme hashée dans la base de données.

La vérification se fait avec :

```php
password_verify()
```

Les requêtes SQL utilisent PDO et des requêtes préparées afin de réduire les risques d’injection SQL.

## Impression et export PDF

L’export PDF est réalisé avec la fonction JavaScript :

```js
window.print()
```

L’utilisateur peut ensuite choisir :

```txt
Imprimer
ou
Enregistrer en PDF
```

Certaines parties comme la navbar, les boutons et les filtres sont cachées pendant l’impression grâce à :

```css
@media print
```

## Objectif pédagogique

Ce projet permet de pratiquer :

- La création d’une application web dynamique
- La gestion d’une base de données relationnelle
- Les opérations CRUD
- L’authentification avec sessions PHP
- Les jointures SQL
- La détection de conflits
- L’organisation professionnelle d’un projet web
- Le déploiement d’un site PHP/MySQL

## Auteur

Mini Projet Développement Web  
Filière : MGSI  
École : ENSIASD  
Année universitaire : 2025/2026
