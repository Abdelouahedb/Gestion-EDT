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

// Données pour les graphiques (admin uniquement)
$chart_jours = ['Lundi' => 0, 'Mardi' => 0, 'Mercredi' => 0, 'Jeudi' => 0, 'Vendredi' => 0, 'Samedi' => 0];
$chart_types = ['Cours' => 0, 'TD' => 0, 'TP' => 0];

if ($role === 'admin') {
    $stmt = $PDO->query("SELECT jour, COUNT(*) AS total FROM emplois GROUP BY jour");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($chart_jours[$row['jour']])) {
            $chart_jours[$row['jour']] = (int) $row['total'];
        }
    }

    $stmt = $PDO->query("SELECT type_cours, COUNT(*) AS total FROM emplois GROUP BY type_cours");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($chart_types[$row['type_cours']])) {
            $chart_types[$row['type_cours']] = (int) $row['total'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <div class="dashboard-charts">
            <div class="chart-box">
                <h2>Séances par jour</h2>
                <canvas id="chart-jours"></canvas>
            </div>

            <div class="chart-box">
                <h2>Répartition par type</h2>
                <canvas id="chart-types"></canvas>
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

<?php require_once "../includes/footer.php"; ?>

<?php if ($role === 'admin'): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const couleurPrimaire = '#1f4e79';
const couleurFond = 'rgba(31, 78, 121, 0.15)';

new Chart(document.getElementById('chart-jours'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($chart_jours)) ?>,
        datasets: [{
            label: 'Nombre de séances',
            data: <?= json_encode(array_values($chart_jours)) ?>,
            backgroundColor: couleurFond,
            borderColor: couleurPrimaire,
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

new Chart(document.getElementById('chart-types'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($chart_types)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($chart_types)) ?>,
            backgroundColor: ['#1f4e79', '#0d6efd', '#6c8ebf']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
<?php endif; ?>

</body>
</html>