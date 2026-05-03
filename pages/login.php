<?php require_once '../config.php'; 
$error = "";
if (isset($_POST['connecter'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $PDO->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['filiere_id'] = $user['filiere_id'];
        $_SESSION['semestre_id'] = $user['semestre_id'];
        $_SESSION['enseignant_id'] = $user['enseignant_id'];

        header("Location: dashboard.php");

        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <meta name="description" content="Page de connexion pour la gestion des emplois du temps scolaires">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<header class="landing-navbar">
    <div class="landing-logo">
        Gestion<span>EDT</span>
    </div>

    <nav class="landing-links">
        <a href="../index.php" class="login-btn">Accueil</a>
    </nav>
</header>


    <div class="login-box">
        <h2>Connexion</h2>

        <?php if ($error != ""): ?>
            <p style="color:red; margin-bottom: 15px; text-align: center;"><?= $error ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="connecter">Se connecter</button>
            <p style="font-size:x-small; margin-top: 15px;">Compte Test (admin) - Login: ENSIASD Password: ENSIASD2026 <br>
                login: prof_ahmed password:123456 <br>
                login: etudiant_mgsi_s1 password:123456
            </p>

        </form>
    </div>


</body>