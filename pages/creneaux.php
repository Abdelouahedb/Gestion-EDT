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

// ADD CRENEAU
if (isset($_POST['ajouter'])) {
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    if (!empty($heure_debut) && !empty($heure_fin)) {

        if ($heure_debut >= $heure_fin) {
            $error = "L'heure de début doit être inférieure à l'heure de fin.";
        } else {
            try {
                $stmt = $PDO->prepare("
                    INSERT INTO creneaux (heure_debut, heure_fin)
                    VALUES (:heure_debut, :heure_fin)
                ");

                $stmt->execute([
                    'heure_debut' => $heure_debut,
                    'heure_fin' => $heure_fin
                ]);

                $message = "Créneau ajouté avec succès.";

            } catch (PDOException $e) {
                $error = "Erreur lors de l'ajout du créneau.";
            }
        }

    } else {
        $error = "Les deux heures sont obligatoires.";
    }
}

// DELETE CRENEAU
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $stmt = $PDO->prepare("DELETE FROM creneaux WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $_SESSION['message'] = "Créneau supprimé avec succès.";
        header("Location: creneaux.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression.";
        header("Location: creneaux.php");
        exit();
    }
}

// GET CRENEAU FOR EDIT
$creneau_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $PDO->prepare("SELECT * FROM creneaux WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $creneau_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE CRENEAU
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    if (!empty($heure_debut) && !empty($heure_fin)) {

        if ($heure_debut >= $heure_fin) {
            $error = "L'heure de début doit être inférieure à l'heure de fin.";
        } else {
            try {
                $stmt = $PDO->prepare("
                    UPDATE creneaux
                    SET heure_debut = :heure_debut, heure_fin = :heure_fin
                    WHERE id = :id
                ");

                $stmt->execute([
                    'heure_debut' => $heure_debut,
                    'heure_fin' => $heure_fin,
                    'id' => $id
                ]);

                $_SESSION['message'] = "Créneau modifié avec succès.";
                header("Location: creneaux.php");
                exit();

            } catch (PDOException $e) {
                $error = "Erreur lors de la modification.";
            }
        }

    } else {
        $error = "Les deux heures sont obligatoires.";
    }
}

// GET ALL CRENEAUX
$stmt = $PDO->query("SELECT * FROM creneaux ORDER BY heure_debut ASC");
$creneaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Créneaux</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php require_once "../includes/navbar.php"; ?>

<div class="container">

    <h1>Gestion des Créneaux</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">

        <?php if ($creneau_edit): ?>
            <h2>Modifier un créneau</h2>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $creneau_edit['id'] ?>">

                <label>Heure début</label>
                <input type="time" name="heure_debut" value="<?= htmlspecialchars($creneau_edit['heure_debut']) ?>" required>

                <label>Heure fin</label>
                <input type="time" name="heure_fin" value="<?= htmlspecialchars($creneau_edit['heure_fin']) ?>" required>
            <div class="form-actions">
                <button type="submit" name="modifier" class="btn-form btn-save">Modifier</button>
                <a href="creneaux.php" class="btn-form btn-cancel">Annuler</a>
            </div>
            </form>

        <?php else: ?>
            <h2>Ajouter un créneau</h2>

            <form method="POST">
                <label>Heure début</label>
                <input type="time" name="heure_debut" required>

                <label>Heure fin</label>
                <input type="time" name="heure_fin" required>

                <button type="submit" name="ajouter" class="btn-form btn-save">Ajouter</button>
            </form>
        <?php endif; ?>

    </div>

    <h1>Liste des Créneaux</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($creneaux as $creneau): ?>
                <tr>
                    <td><?= $creneau['id'] ?></td>
                    <td><?= htmlspecialchars(substr($creneau['heure_debut'], 0, 5)) ?></td>
                    <td><?= htmlspecialchars(substr($creneau['heure_fin'], 0, 5)) ?></td>
                    <td>
                        <a class="btn-edit" href="creneaux.php?edit=<?= $creneau['id'] ?>">Modifier</a>

                        <a class="btn-delete"
                           href="creneaux.php?delete=<?= $creneau['id'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce créneau ?');">
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