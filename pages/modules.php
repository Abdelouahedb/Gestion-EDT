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

// ADD MODULE
if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $code_module = trim($_POST['code_module']);
    $description = trim($_POST['description']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("
                INSERT INTO modules (nom, code_module, description)
                VALUES (:nom, :code_module, :description)
            ");

            $stmt->execute([
                'nom' => $nom,
                'code_module' => $code_module,
                'description' => $description
            ]);

            $message = "Module ajouté avec succès.";

        } catch (PDOException $e) {
            $error = "Erreur : ce code module existe peut-être déjà.";
        }
    } else {
        $error = "Le nom du module est obligatoire.";
    }
}

// DELETE MODULE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM modules WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Module supprimé avec succès.";
        header("Location: modules.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: modules.php");
        exit();
    }
}

// GET MODULE FOR EDIT
$module_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM modules WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $module_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE MODULE
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $code_module = trim($_POST['code_module']);
    $description = trim($_POST['description']);

    if (!empty($nom)) {
        try {
            $stmt = $PDO->prepare("
                UPDATE modules
                SET nom = :nom, code_module = :code_module, description = :description
                WHERE id = :id
            ");

            $stmt->execute([
                'nom' => $nom,
                'code_module' => $code_module,
                'description' => $description,
                'id' => $id
            ]);

            $_SESSION['message'] = "Module modifié avec succès.";
            header("Location: modules.php");
            exit();

        } catch (PDOException $e) {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Le nom du module est obligatoire.";
    }
}

// GET ALL MODULES
$stmt = $PDO->query("SELECT * FROM modules ORDER BY id DESC");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Modules</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Gestion des Modules</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">

        <?php if ($module_edit): ?>
            <h2>Modifier un module</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $module_edit['id'] ?>">

                <label>Nom du module</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($module_edit['nom']) ?>" required>

                <label>Code module</label>
                <input type="text" name="code_module" value="<?= htmlspecialchars($module_edit['code_module'] ?? '') ?>">

                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($module_edit['description'] ?? '') ?></textarea>
            <div class="form-actions">
                <button type="submit" name="modifier" class="btn-form btn-save">Modifier</button>
                <a href="modules.php" class="btn-form btn-cancel">Annuler</a>
            </div>
            </form>

        <?php else: ?>
            <h2>Ajouter un module</h2>

            <form method="POST">
                <label>Nom du module</label>
                <input type="text" name="nom" placeholder="Ex: Développement Web" required>

                <label>Code module</label>
                <input type="text" name="code_module" placeholder="Ex: WEB101">

                <label>Description</label>
                <textarea name="description" placeholder="Description du module"></textarea>

                <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
            </form>
        <?php endif; ?>

    </div>

    <h1>Liste des Modules</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Code</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($modules as $module): ?>
                <tr>
                    <td><?= $module['id'] ?></td>
                    <td><?= htmlspecialchars($module['nom']) ?></td>
                    <td><?= htmlspecialchars($module['code_module'] ?? '') ?></td>
                    <td><?= htmlspecialchars($module['description'] ?? '') ?></td>
                    <td>
                        <a class="btn-edit" href="modules.php?edit=<?= $module['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="modules.php?delete=<?= $module['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce module ?');">
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