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
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}


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
        $_SESSION['message'] = "Filière supprimée avec succès.";

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
$stmt = $PDO->query("SELECT * FROM filieres ORDER BY id ASC");
$filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Filières</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

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
            <h1>Modifier une filière</h1>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $filiere_edit['id'] ?>">

                <label>Nom de la filière</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($filiere_edit['nom']) ?>" required>

                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($filiere_edit['description']) ?></textarea>
            
                <div class="form-actions">
                    <button type="submit" name="modifier">Modifier</button>
                    <a href="filieres.php" class="btn-cancel">Annuler</a>
                </div>
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

    <h1>Liste des Filières</h1>

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


<?php require_once "../includes/footer.php"; ?>

</body>
</html>