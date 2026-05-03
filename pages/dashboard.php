<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nb_filieres = $PDO->query("SELECT COUNT(*) FROM filieres")->fetchColumn();
$nb_enseignants = $PDO->query("SELECT COUNT(*) FROM enseignants")->fetchColumn();
$nb_salles = $PDO->query("SELECT COUNT(*) FROM salles")->fetchColumn();
$nb_emplois = $PDO->query("SELECT COUNT(*) FROM emplois")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <nav>
        <h2>Dashboard</h2>
        <a href="dashboard.php">Accueil</a>
        <a href="filieres.php">Filières</a>
        <a href="enseignants.php">Enseignants</a>
        <a href="salles.php">Salles</a>
        <a href="modules.php">Modules</a>
        <a href="creneaux.php">Créneaux</a>
        <a href="emploi.php">Emploi</a>
        <a href="logout.php">Déconnexion</a>
    </nav>
    <div class="container">

        <h1>Bienvenue <?= $_SESSION['nom']; ?></h1>
        <h3>Rôle : <?= $_SESSION['role']; ?></h3>

        <div class="cards">
            <div class="card">Filières<br><strong><?= $nb_filieres ?></strong></div>
            <div class="card">Enseignants<br><strong><?= $nb_enseignants ?></strong></div>
            <div class="card">Salles<br><strong><?= $nb_salles ?></strong></div>
            <div class="card">Séances<br><strong><?= $nb_emplois ?></strong></div>
        </div>

    </div>
</body>
</html>