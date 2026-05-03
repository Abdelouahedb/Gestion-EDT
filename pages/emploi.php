<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = "";
$error = "";

// Messages after redirect
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// ADD EMPLOI
if (isset($_POST['ajouter'])) {
    $filiere_id = $_POST['filiere_id'];
    $semestre_id = $_POST['semestre_id'];
    $module_id = $_POST['module_id'];
    $enseignant_id = $_POST['enseignant_id'];
    $salle_id = $_POST['salle_id'];
    $creneau_id = $_POST['creneau_id'];
    $jour = $_POST['jour'];
    $type_cours = $_POST['type_cours'];

    // Vérifier conflit : même salle OU même enseignant OU même classe dans le même jour/créneau
    $check = $PDO->prepare("
        SELECT * FROM emplois
        WHERE jour = :jour
        AND creneau_id = :creneau_id
        AND (
            salle_id = :salle_id
            OR enseignant_id = :enseignant_id
            OR (filiere_id = :filiere_id AND semestre_id = :semestre_id)
        )
    ");

    $check->execute([
        'jour' => $jour,
        'creneau_id' => $creneau_id,
        'salle_id' => $salle_id,
        'enseignant_id' => $enseignant_id,
        'filiere_id' => $filiere_id,
        'semestre_id' => $semestre_id
    ]);

    if ($check->rowCount() > 0) {
        $error = "Conflit détecté : salle, enseignant ou classe déjà occupé dans ce créneau.";
    } else {
        try {
            $stmt = $PDO->prepare("
                INSERT INTO emplois 
                (filiere_id, semestre_id, module_id, enseignant_id, salle_id, creneau_id, jour, type_cours)
                VALUES 
                (:filiere_id, :semestre_id, :module_id, :enseignant_id, :salle_id, :creneau_id, :jour, :type_cours)
            ");

            $stmt->execute([
                'filiere_id' => $filiere_id,
                'semestre_id' => $semestre_id,
                'module_id' => $module_id,
                'enseignant_id' => $enseignant_id,
                'salle_id' => $salle_id,
                'creneau_id' => $creneau_id,
                'jour' => $jour,
                'type_cours' => $type_cours
            ]);

            $message = "Séance ajoutée avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de la séance.";
        }
    }
}

// DELETE EMPLOI
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM emplois WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Séance supprimée avec succès.";
        header("Location: emploi.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: emploi.php");
        exit();
    }
}

// GET DATA FOR SELECTS
$filieres = $PDO->query("SELECT * FROM filieres ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$semestres = $PDO->query("SELECT * FROM semestres ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$modules = $PDO->query("SELECT * FROM modules ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$enseignants = $PDO->query("SELECT * FROM enseignants ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$salles = $PDO->query("SELECT * FROM salles ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$creneaux = $PDO->query("SELECT * FROM creneaux ORDER BY heure_debut ASC")->fetchAll(PDO::FETCH_ASSOC);

// FILTER BY FILIERE
$selected_filiere = $_GET['filiere'] ?? "";

// GET ALL EMPLOIS WITH FILTER
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
";

if (!empty($selected_filiere)) {
    $sql .= " WHERE e.filiere_id = :filiere_id";
}

$sql .= "
    ORDER BY 
        f.nom ASC,
        FIELD(e.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
        c.heure_debut ASC
";

$stmt = $PDO->prepare($sql);

if (!empty($selected_filiere)) {
    $stmt->execute([
        'filiere_id' => $selected_filiere
    ]);
} else {
    $stmt->execute();
}

$emplois = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Emplois du Temps</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Gestion des Emplois du Temps</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">
        <h2>Ajouter une séance</h2>

        <form method="POST">

            <label>Filière</label>
            <select name="filiere_id" required>
                <option value="">-- Choisir une filière --</option>
                <?php foreach ($filieres as $filiere): ?>
                    <option value="<?= $filiere['id'] ?>">
                        <?= htmlspecialchars($filiere['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Semestre</label>
            <select name="semestre_id" required>
                <option value="">-- Choisir un semestre --</option>
                <?php foreach ($semestres as $semestre): ?>
                    <option value="<?= $semestre['id'] ?>">
                        <?= htmlspecialchars($semestre['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Module</label>
            <select name="module_id" required>
                <option value="">-- Choisir un module --</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?= $module['id'] ?>">
                        <?= htmlspecialchars($module['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Enseignant</label>
            <select name="enseignant_id" required>
                <option value="">-- Choisir un enseignant --</option>
                <?php foreach ($enseignants as $enseignant): ?>
                    <option value="<?= $enseignant['id'] ?>">
                        <?= htmlspecialchars($enseignant['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Salle</label>
            <select name="salle_id" required>
                <option value="">-- Choisir une salle --</option>
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle['id'] ?>">
                        <?= htmlspecialchars($salle['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Créneau</label>
            <select name="creneau_id" required>
                <option value="">-- Choisir un créneau --</option>
                <?php foreach ($creneaux as $creneau): ?>
                    <option value="<?= $creneau['id'] ?>">
                        <?= substr($creneau['heure_debut'], 0, 5) ?>
                        -
                        <?= substr($creneau['heure_fin'], 0, 5) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Jour</label>
            <select name="jour" required>
                <option value="">-- Choisir un jour --</option>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
            </select>

            <label>Type de cours</label>
            <select name="type_cours" required>
                <option value="Cours">Cours</option>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
            </select>

            <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
        </form>
    </div>

    <h1>Liste des Séances</h1>

    <form method="GET" class="filter-box">
        <label>Afficher par filière</label>

        <select name="filiere" onchange="this.form.submit()">
            <option value="">Toutes les filières</option>

            <?php foreach ($filieres as $filiere): ?>
                <option value="<?= $filiere['id'] ?>"
                    <?= ($selected_filiere == $filiere['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($filiere['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

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
                <th>Action</th>
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

                        <td><?= htmlspecialchars($emploi['filiere'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['semestre'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['module'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['type_cours'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['enseignant'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($emploi['salle'] ?? '-') ?></td>

                        <td>
                            <a class="btn-delete"
                               href="emploi.php?delete=<?= $emploi['id'] ?>"
                               onclick="return confirm('Voulez-vous vraiment supprimer cette séance ?');">
                               Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9" style="text-align:center;">
                        Aucune séance trouvée.
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>