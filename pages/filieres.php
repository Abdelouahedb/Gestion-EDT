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

// ADD FILIERE
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("INSERT INTO filieres (nom, description) VALUES (:nom, :description)");
            $stmt->execute([
                'nom' => $nom,
                'description' => $description
            ]);

            $message = "Filière ajoutée avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur : cette filière existe peut-être déjà.";
        }
    } else {
        $error = "Le nom de la filière est obligatoire.";
    }
}

// DELETE FILIERE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM filieres WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header("Location: filieres.php");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression.";
    }
}

// GET FILIERE FOR EDIT
$filiere_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM filieres WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $filiere_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE FILIERE
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("UPDATE filieres SET nom = :nom, description = :description WHERE id = :id");
            $stmt->execute([
                'nom' => $nom,
                'description' => $description,
                'id' => $id
            ]);

            header("Location: filieres.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Le nom de la filière est obligatoire.";
    }
}

// GET ALL FILIERES
$stmt = $PDO->query("SELECT * FROM filieres ORDER BY id DESC");
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Filières</title>
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

    <h1>Gestion des Filières</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">
        <?php if ($filiere_edit): ?>
            <h2>Modifier une filière</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $filiere_edit['id'] ?>">

                <label>Nom de la filière</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($filiere_edit['nom']) ?>" required>

                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($filiere_edit['description']) ?></textarea>

                <button type="submit" name="modifier">Modifier</button>
                <a href="filieres.php" class="btn-cancel">Annuler</a>
            </form>

        <?php else: ?>
            <h2>Ajouter une filière</h2>

            <form method="POST">
                <label>Nom de la filière</label>
                <input type="text" name="nom" placeholder="Ex: MGSI" required>

                <label>Description</label>
                <textarea name="description" placeholder="Description de la filière"></textarea>

                <button type="submit" name="ajouter">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Liste des Filières</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($filieres as $filiere): ?>
                <tr>
                    <td><?= $filiere['id'] ?></td>
                    <td><?= htmlspecialchars($filiere['nom']) ?></td>
                    <td><?= htmlspecialchars($filiere['description']) ?></td>
                    <td>
                        <a class="btn-edit" href="filieres.php?edit=<?= $filiere['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="filieres.php?delete=<?= $filiere['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer cette filière ?');">
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