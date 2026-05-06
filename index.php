<?php
require_once "config.php";

$message = "";
$error = "";

if (isset($_POST['envoyer'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $sujet = trim($_POST['sujet']);
    $message_contact = trim($_POST['message']);

    if (!empty($nom) && !empty($email) && !empty($message_contact)) {
        try {
            $stmt = $PDO->prepare("
                INSERT INTO contacts (nom, email, sujet, message)
                VALUES (:nom, :email, :sujet, :message)
            ");

            $stmt->execute([
                'nom' => $nom,
                'email' => $email,
                'sujet' => $sujet,
                'message' => $message_contact
            ]);

            $message = "Votre message a été envoyé avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'envoi du message.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Gestion des Emplois du Temps | ENSIASD</title>

    <meta name="description" content="Application web de gestion des emplois du temps scolaires pour les filières, enseignants, salles, créneaux et plannings.">
    <meta name="keywords" content="emploi du temps, gestion scolaire, enseignants, salles, créneaux, ENSIASD, MGSI">
    <meta name="author" content="Mini Projet Développement Web">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="landing-page">

<header class="landing-navbar">
    <div class="landing-logo">
        Gestion<span>EDT</span>
    </div>

    <nav class="landing-links">
        <a href="#accueil">Accueil</a>
        <a href="#apropos">À propos</a>
        <a href="#fonctionnalites">Fonctionnalités</a>
        <a href="#contact">Contact</a>
        <a href="#politique">Politique</a>
        <a href="pages/login.php" class="login-btn">Connexion</a>
    </nav>
</header>

<section class="landing-hero" id="accueil">
    <div class="hero-content">
        <span class="hero-badge">Mini Projet Développement Web</span>

        <h1>Application web de gestion des emplois du temps scolaires</h1>

        <p>
            Cette application permet de gérer les filières, les semestres, les enseignants,
            les salles, les créneaux horaires ainsi que les plannings des étudiants et des enseignants.
        </p>

        <div class="hero-buttons">
            <a href="pages/login.php" class="primary-btn">Se connecter</a>
            <a href="#apropos" class="secondary-btn">Découvrir</a>
        </div>
    </div>

    <div class="hero-card">
        <h3>Aperçu du planning</h3>

        <div class="mini-row">
            <strong>Lundi</strong>
            <span>08:30 - 10:30</span>
            <p>Développement Web</p>
        </div>

        <div class="mini-row">
            <strong>Mardi</strong>
            <span>14:00 - 16:00</span>
            <p>Bases de Données</p>
        </div>

        <div class="mini-row">
            <strong>Mercredi</strong>
            <span>10:30 - 12:30</span>
            <p>Management des SI</p>
        </div>
    </div>
</section>

<section class="landing-section" id="apropos">
    <div class="section-title">
        <span>À propos</span>
        <h2>Objectif de l’application</h2>
        <p>
            L’objectif de ce projet est de proposer une mini application web permettant
            d’organiser les emplois du temps scolaires d’une manière simple, claire et efficace.
            Elle facilite la gestion des ressources pédagogiques et la consultation des plannings.
        </p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <strong>3</strong>
            <span>Types d’utilisateurs</span>
        </div>

        <div class="stat-box">
            <strong>6</strong>
            <span>Jours de planification</span>
        </div>

        <div class="stat-box">
            <strong>PDF</strong>
            <span>Impression des plannings</span>
        </div>

        <div class="stat-box">
            <strong>Web</strong>
            <span>Application responsive</span>
        </div>
    </div>
</section>

<section class="landing-section services-section" id="fonctionnalites">
    <div class="section-title">
        <span>Fonctionnalités</span>
        <h2>Fonctionnalités principales</h2>
        <p>
            L’application regroupe les fonctionnalités nécessaires pour créer,
            organiser et consulter les emplois du temps.
        </p>
    </div>

    <div class="services-grid">
        <div class="service-card">
            <h3>Création des emplois du temps</h3>
            <p>
                L’administrateur peut créer des séances en choisissant la filière,
                le semestre, le module, l’enseignant, la salle, le jour et le créneau.
            </p>
        </div>

        <div class="service-card">
            <h3>Gestion des filières et semestres</h3>
            <p>
                L’application permet d’ajouter, modifier, supprimer et consulter
                les filières ainsi que les semestres.
            </p>
        </div>

        <div class="service-card">
            <h3>Gestion des enseignants</h3>
            <p>
                Les enseignants peuvent être enregistrés avec leurs informations
                principales afin d’être affectés aux séances.
            </p>
        </div>

        <div class="service-card">
            <h3>Gestion des salles et créneaux</h3>
            <p>
                L’administrateur peut gérer les salles disponibles et définir les
                différents créneaux horaires utilisés dans les plannings.
            </p>
        </div>

        <div class="service-card">
            <h3>Détection des conflits</h3>
            <p>
                Le système vérifie les conflits afin d’éviter qu’une salle,
                un enseignant ou une classe soit occupé deux fois au même moment.
            </p>
        </div>

        <div class="service-card">
            <h3>Consultation des plannings</h3>
            <p>
                Les étudiants consultent leur planning selon leur filière et semestre,
                tandis que les enseignants consultent leur planning personnel.
            </p>
        </div>
    </div>
</section>

<section class="landing-section contact-section" id="contact">
    <div class="contact-content">
        <div class="contact-text">
            <span class="contact-badge">Contact</span>
            <h2>Formulaire de contact</h2>
            <p>
                Ce formulaire permet aux utilisateurs d’envoyer un message
                concernant un problème de connexion, une erreur dans un planning
                ou une demande d’information.
            </p>
        </div>

        <form method="POST" class="contact-form">
            <?php if (!empty($message)): ?>
                <p class="success"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <input type="text" name="nom" placeholder="Votre nom" required>
            <input type="email" name="email" placeholder="Votre email" required>
            <input type="text" name="sujet" placeholder="Sujet">
            <textarea name="message" placeholder="Votre message" required></textarea>

            <button type="submit" name="envoyer">Envoyer</button>
        </form>
    </div>
</section>

<section class="landing-section politique-section" id="politique">
    <div class="section-title">
        <span>Mentions</span>
        <h2>Politique de confidentialité &amp; mentions légales</h2>
        <p>
            Cette section précise les conditions d’utilisation de l’application
            ainsi que la manière dont les données des utilisateurs sont traitées.
        </p>
    </div>

    <div class="politique-grid">
        <div class="politique-card">
            <h3>Politique de confidentialité</h3>
            <p>
                Les informations saisies (nom, email, identifiant) sont utilisées uniquement
                pour la gestion des comptes et des emplois du temps. Aucune donnée n’est
                transmise à des tiers. Les mots de passe sont stockés sous forme hachée.
                L’utilisateur peut demander la suppression de son compte à tout moment.
            </p>
        </div>

        <div class="politique-card">
            <h3>Conditions d’utilisation</h3>
            <p>
                L’accès à l’application est réservé aux utilisateurs disposant d’un compte
                valide (administrateur, enseignant ou étudiant). L’utilisateur s’engage à
                ne pas partager ses identifiants et à utiliser l’application uniquement
                dans un cadre pédagogique.
            </p>
        </div>

        <div class="politique-card">
            <h3>Mentions légales</h3>
            <p>
                Application réalisée dans le cadre d’un mini-projet de développement web —
                Master MGSI, ENSIASD Taroudant, année universitaire 2025/2026.
                Le contenu présenté à des fins de démonstration peut être modifié sans préavis.
            </p>
        </div>
    </div>
</section>

<?php require_once "./includes/footer.php"; ?>

<script src="js/script.js"></script>

</body>
</html>