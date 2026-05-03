<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$error = "";

// Data for admin filter
$filieres = $PDO->query("SELECT * FROM filieres ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$semestres = $PDO->query("SELECT * FROM semestres ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

// If student: use his filiere and semestre from session
if ($_SESSION['role'] === 'etudiant') {
    $selected_filiere = $_SESSION['filiere_id'];
    $selected_semestre = $_SESSION['semestre_id'];
} else {
    // Admin can choose filiere and semestre
    $selected_filiere = $_GET['filiere'] ?? "";
    $selected_semestre = $_GET['semestre'] ?? "";
}

$sql = "
    SELECT e.*,
           f.nom AS filiere,
           sem.nom AS semestre,
           m.nom AS module,
           ens.nom AS enseignant,
           s.nom AS salle,
           c.heure_debut,
           c.heure_fin
    FROM emplois e
    LEFT JOIN filieres f ON e.filiere_id = f.id
    LEFT JOIN semestres sem ON e.semestre_id = sem.id
    LEFT JOIN modules m ON e.module_id = m.id
    LEFT JOIN enseignants ens ON e.enseignant_id = ens.id
    LEFT JOIN salles s ON e.salle_id = s.id
    LEFT JOIN creneaux c ON e.creneau_id = c.id
    WHERE 1
";

$params = [];

if (!empty($selected_filiere)) {
    $sql .= " AND e.filiere_id = :filiere_id";
    $params['filiere_id'] = $selected_filiere;
}

if (!empty($selected_semestre)) {
    $sql .= " AND e.semestre_id = :semestre_id";
    $params['semestre_id'] = $selected_semestre;
}

$sql .= "
    ORDER BY 
        FIELD(e.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
        c.heure_debut ASC
";

$stmt = $PDO->prepare($sql);
$stmt->execute($params);
$emplois = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Planning Étudiant</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="student-planning-page">

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Planning Étudiant</h1>

    <?php if ($_SESSION['role'] !== 'etudiant'): ?>
        <form method="GET" class="filter-box no-print">
            <label>Filière</label>
            <select name="filiere">
                <option value="">Toutes les filières</option>
                <?php foreach ($filieres as $filiere): ?>
                    <option value="<?= $filiere['id'] ?>"
                        <?= ($selected_filiere == $filiere['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($filiere['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Semestre</label>
            <select name="semestre">
                <option value="">Tous les semestres</option>
                <?php foreach ($semestres as $semestre): ?>
                    <option value="<?= $semestre['id'] ?>"
                        <?= ($selected_semestre == $semestre['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($semestre['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="student-btn">Afficher</button>
        </form>
    <?php endif; ?>

    <button onclick="window.print()" class="student-btn print-btn no-print">Imprimer</button>

    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>Créneau</th>
                <th>Filière</th>
                <th>Semestre</th>
                <th>Module</th>
                <th>Type</th>
                <th>Enseignant</th>
                <th>Salle</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($emplois) > 0): ?>
                <?php foreach ($emplois as $emploi): ?>
                    <tr>
                        <td><?= htmlspecialchars($emploi['jour']) ?></td>

                        <td>
                            <?= htmlspecialchars(substr($emploi['heure_debut'], 0, 5)) ?>
                            -
                            <?= htmlspecialchars(substr($emploi['heure_fin'], 0, 5)) ?>
                        </td>

                        <td><?= htmlspecialchars($emploi['filiere'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['semestre'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['module'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['type_cours'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['enseignant'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['salle'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">
                        Aucun planning trouvé.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>