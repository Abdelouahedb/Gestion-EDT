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

// SHOW SESSION MESSAGE AFTER REDIRECT
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// ADD SEMESTRE
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("INSERT INTO semestres (nom) VALUES (:nom)");
            $stmt->execute(['nom' => $nom]);

            $message = "Semestre ajouté avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur : ce semestre existe peut-être déjà.";
        }
    } else {
        $error = "Le nom du semestre est obligatoire.";
    }
}

// DELETE SEMESTRE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM semestres WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Semestre supprimé avec succès.";
        header("Location: semestres.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: semestres.php");
        exit();
    }
}

// GET SEMESTRE FOR EDIT
$semestre_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM semestres WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $semestre_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE SEMESTRE
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("UPDATE semestres SET nom = :nom WHERE id = :id");
            $stmt->execute([
                'nom' => $nom,
                'id' => $id
            ]);

            $_SESSION['message'] = "Semestre modifié avec succès.";
            header("Location: semestres.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Le nom du semestre est obligatoire.";
    }
}

// GET ALL SEMESTRES
$stmt = $PDO->query("SELECT * FROM semestres ORDER BY id DESC");
$semestres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Semestres</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>


<div class="container">

    <h1>Gestion des Semestres</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">
        <?php if ($semestre_edit): ?>
            <h2>Modifier un semestre</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $semestre_edit['id'] ?>">

                <label>Nom du semestre</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($semestre_edit['nom']) ?>" required>
            <div class="form-actions">
                <button type="submit" name="modifier" class="btn-form btn-save">Modifier</button>
                <a href="semestres.php" class="btn-form btn-cancel">Annuler</a>
            </div>
            </form>

        <?php else: ?>
            <h2>Ajouter un semestre</h2>

            <form method="POST">
                <label>Nom du semestre</label>
                <input type="text" name="nom" placeholder="Ex: S1" required>

                <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <h1>Liste des Semestres</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($semestres as $semestre): ?>
                <tr>
                    <td><?= $semestre['id'] ?></td>
                    <td><?= htmlspecialchars($semestre['nom']) ?></td>
                    <td>
                        <a class="btn-edit" href="semestres.php?edit=<?= $semestre['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="semestres.php?delete=<?= $semestre['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce semestre ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>