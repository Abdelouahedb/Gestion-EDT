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

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="dashboard-page">

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <div class="dashboard-welcome">
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['nom']); ?></h1>
        <p>Rôle : <strong><?= htmlspecialchars($role); ?></strong></p>
    </div>

    <?php if ($role === 'admin'): ?>

        <div class="dashboard-cards">
            <div class="dashboard-card">
                <span>Filières</span>
                <strong><?= $nb_filieres ?></strong>
            </div>

            <div class="dashboard-card">
                <span>Enseignants</span>
                <strong><?= $nb_enseignants ?></strong>
            </div>

            <div class="dashboard-card">
                <span>Salles</span>
                <strong><?= $nb_salles ?></strong>
            </div>

            <div class="dashboard-card">
                <span>Séances</span>
                <strong><?= $nb_emplois ?></strong>
            </div>
        </div>

        <div class="dashboard-panel">
            <h2>Actions rapides</h2>

            <div class="dashboard-actions">
                <a href="filieres.php">Gérer les filières</a>
                <a href="semestres.php">Gérer les semestres</a>
                <a href="enseignants.php">Gérer les enseignants</a>
                <a href="salles.php">Gérer les salles</a>
                <a href="modules.php">Gérer les modules</a>
                <a href="creneaux.php">Gérer les créneaux</a>
                <a href="emploi.php">Créer une séance</a>
                <a href="planning_etudiant.php">Planning étudiant</a>
                <a href="planning_enseignant.php">Planning enseignant</a>
            </div>
        </div>

    <?php elseif ($role === 'enseignant'): ?>

        <div class="dashboard-panel">
            <h2>Espace Enseignant</h2>
            <p>Vous pouvez consulter votre emploi du temps personnel.</p>

            <div class="dashboard-actions">
                <a href="planning_enseignant.php">Voir mon planning</a>
            </div>
        </div>

    <?php elseif ($role === 'etudiant'): ?>

        <div class="dashboard-panel">
            <h2>Espace Étudiant</h2>
            <p>Vous pouvez consulter le planning de votre filière et semestre.</p>

            <div class="dashboard-actions">
                <a href="planning_etudiant.php">Voir mon planning</a>
            </div>
        </div>

    <?php endif; ?>

</div>

</body>
</html>