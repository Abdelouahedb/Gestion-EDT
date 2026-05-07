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

// ADD SALLE
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $capacite = trim($_POST['capacite']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("INSERT INTO salles (nom, capacite) VALUES (:nom, :capacite)");
            $stmt->execute([
                'nom' => $nom,
                'capacite' => $capacite
            ]);

            $message = "Salle ajoutée avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur : cette salle existe peut-être déjà.";
        }
    } else {
        $error = "Le nom de la salle est obligatoire.";
    }
}

// DELETE SALLE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM salles WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Salle supprimée avec succès.";
        header("Location: salles.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: salles.php");
        exit();
    }
}

// GET SALLE FOR EDIT
$salle_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM salles WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $salle_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE SALLE
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $capacite = trim($_POST['capacite']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("UPDATE salles SET nom = :nom, capacite = :capacite WHERE id = :id");
            $stmt->execute([
                'nom' => $nom,
                'capacite' => $capacite,
                'id' => $id
            ]);

            $_SESSION['message'] = "Salle modifiée avec succès.";
            header("Location: salles.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Le nom de la salle est obligatoire.";
    }
}

// GET ALL SALLES
$stmt = $PDO->query("SELECT * FROM salles ORDER BY id DESC");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Salles</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Gestion des Salles</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">
        <?php if ($salle_edit): ?>
            <h2>Modifier une salle</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $salle_edit['id'] ?>">

                <label>Nom de la salle</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($salle_edit['nom']) ?>" required>

                <label>Capacité</label>
                <input type="number" name="capacite" value="<?= htmlspecialchars($salle_edit['capacite']) ?>" min="1">
                <div class="form-actions">
                <button type="submit" name="modifier" class="btn-form btn-save">Modifier</button>
                <a href="salles.php" class="btn-form btn-cancel">Annuler</a>
                </div>
            </form>

        <?php else: ?>
            <h2>Ajouter une salle</h2>

            <form method="POST">
                <label>Nom de la salle</label>
                <input type="text" name="nom" placeholder="Ex: Salle 101" required>

                <label>Capacité</label>
                <input type="number" name="capacite" placeholder="Ex: 35" min="1">

                <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <h1>Liste des Salles</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Capacité</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($salles as $salle): ?>
                <tr>
                    <td><?= $salle['id'] ?></td>
                    <td><?= htmlspecialchars($salle['nom']) ?></td>
                    <td><?= htmlspecialchars($salle['capacite']) ?></td>
                    <td>
                        <a class="btn-edit" href="salles.php?edit=<?= $salle['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="salles.php?delete=<?= $salle['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer cette salle ?');">
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