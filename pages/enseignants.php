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

// ADD ENSEIGNANT
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("
                INSERT INTO enseignants (nom, email, telephone) 
                VALUES (:nom, :email, :telephone)
            ");

            $stmt->execute([
                'nom' => $nom,
                'email' => $email,
                'telephone' => $telephone
            ]);

            $message = "Enseignant ajouté avec succès.";

        } catch (PDOException $e) {
            $error = "Erreur : cet email existe peut-être déjà.";
        }
    } else {
        $error = "Le nom de l'enseignant est obligatoire.";
    }
}

// DELETE ENSEIGNANT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM enseignants WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Enseignant supprimé avec succès.";
        header("Location: enseignants.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: enseignants.php");
        exit();
    }
}

// GET ENSEIGNANT FOR EDIT
$enseignant_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM enseignants WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $enseignant_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE ENSEIGNANT
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("
                UPDATE enseignants 
                SET nom = :nom, email = :email, telephone = :telephone 
                WHERE id = :id
            ");

            $stmt->execute([
                'nom' => $nom,
                'email' => $email,
                'telephone' => $telephone,
                'id' => $id
            ]);

            $_SESSION['message'] = "Enseignant modifié avec succès.";
            header("Location: enseignants.php");
            exit();

        } catch (PDOException $e) {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Le nom de l'enseignant est obligatoire.";
    }
}

// GET ALL ENSEIGNANTS
$stmt = $PDO->query("SELECT * FROM enseignants ORDER BY id DESC");
$enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Enseignants</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Gestion des Enseignants</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">

        <?php if ($enseignant_edit): ?>
            <h2>Modifier un enseignant</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $enseignant_edit['id'] ?>">

                <label>Nom de l'enseignant</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($enseignant_edit['nom']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($enseignant_edit['email']) ?>">

                <label>Téléphone</label>
                <input type="text" name="telephone" value="<?= htmlspecialchars($enseignant_edit['telephone']) ?>">
                
                <div class="form-actions">
                    <button type="submit" name="modifier" class="btn-form btn-save">Modifier</button>
                    <a href="enseignants.php" class="btn-form btn-cancel">Annuler</a>
                </div>
            </form>

        <?php else: ?>
            <h2>Ajouter un enseignant</h2>

            <form method="POST">
                <label>Nom de l'enseignant</label>
                <input type="text" name="nom" placeholder="Ex: Pr. Ahmed El Amrani" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="exemple@ensiasd.ma">

                <label>Téléphone</label>
                <input type="text" name="telephone" placeholder="Ex: 0600000000">

                <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
            </form>
        <?php endif; ?>

    </div>

    <h1>Liste des Enseignants</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($enseignants as $enseignant): ?>
                <tr>
                    <td><?= $enseignant['id'] ?></td>
                    <td><?= htmlspecialchars($enseignant['nom']) ?></td>
                    <td><?= htmlspecialchars($enseignant['email']) ?></td>
                    <td><?= htmlspecialchars($enseignant['telephone']) ?></td>
                    <td>
                        <a class="btn-edit" href="enseignants.php?edit=<?= $enseignant['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="enseignants.php?delete=<?= $enseignant['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');">
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