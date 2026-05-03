<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Optional: students should not access teacher planning
if ($_SESSION['role'] === 'etudiant') {
    header("Location: planning_etudiant.php");
    exit();
}

$message = "";
$error = "";

// Get teachers for admin filter
$enseignants = $PDO->query("SELECT * FROM enseignants ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

// If connected user is teacher, show only his own planning
if ($_SESSION['role'] === 'enseignant') {
    $selected_enseignant = $_SESSION['enseignant_id'];

    if (empty($selected_enseignant)) {
        $error = "Aucun enseignant associé à ce compte.";
    }
} else {
    // Admin can choose teacher
    $selected_enseignant = $_GET['enseignant'] ?? "";
}

// Query planning
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

if (!empty($selected_enseignant)) {
    $sql .= " AND e.enseignant_id = :enseignant_id";
    $params['enseignant_id'] = $selected_enseignant;
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
    <title>Planning Enseignant</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="enseignant-planning-page">

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Planning Enseignant</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <form method="GET" class="filter-box no-print">
            <label>Enseignant</label>

            <select name="enseignant">
                <option value="">Tous les enseignants</option>

                <?php foreach ($enseignants as $enseignant): ?>
                    <option value="<?= $enseignant['id'] ?>"
                        <?= ($selected_enseignant == $enseignant['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($enseignant['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="enseignant-btn">Afficher</button>
        </form>
    <?php endif; ?>

    <button onclick="window.print()" class="enseignant-btn print-btn no-print">Imprimer</button>

    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>Créneau</th>
                <th>Enseignant</th>
                <th>Filière</th>
                <th>Semestre</th>
                <th>Module</th>
                <th>Type</th>
                <th>Salle</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($emplois) > 0): ?>

                <?php foreach ($emplois as $emploi): ?>
                    <tr>
                        <td><?= htmlspecialchars($emploi['jour']) ?></td>

                        <td>
                            <?php if (!empty($emploi['heure_debut']) && !empty($emploi['heure_fin'])): ?>
                                <?= htmlspecialchars(substr($emploi['heure_debut'], 0, 5)) ?>
                                -
                                <?= htmlspecialchars(substr($emploi['heure_fin'], 0, 5)) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($emploi['enseignant'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['filiere'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['semestre'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['module'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['type_cours'] ?? '-') ?></td>
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