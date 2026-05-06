<?php require_once '../config.php'; 

$error = "";
$success = "";

// CONNEXION
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

// INSCRIPTION
if (isset($_POST['inscrire'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $login_new = trim($_POST['login_new']);
    $password_new = $_POST['password_new'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($nom) || empty($email) || empty($login_new) || empty($password_new)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif (strlen($password_new) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password_new !== $password_confirm) {
        $error = "Les deux mots de passe ne correspondent pas.";
    } else {
        try {
            // Vérifier si login ou email existe déjà
            $check = $PDO->prepare("SELECT id FROM users WHERE login = :login OR email = :email");
            $check->execute(['login' => $login_new, 'email' => $email]);

            if ($check->rowCount() > 0) {
                $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
            } else {
                $hash = password_hash($password_new, PASSWORD_DEFAULT);

                $stmt = $PDO->prepare("
                    INSERT INTO users (nom, login, email, password, role)
                    VALUES (:nom, :login, :email, :password, 'etudiant')
                ");

                $stmt->execute([
                    'nom' => $nom,
                    'login' => $login_new,
                    'email' => $email,
                    'password' => $hash
                ]);

                $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la création du compte.";
        }
    }
}

// On garde l'onglet courant après soumission
$mode = $_GET['mode'] ?? (isset($_POST['inscrire']) ? 'register' : 'login');
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

        <div class="auth-tabs">
            <a href="login.php?mode=login" class="auth-tab <?= $mode === 'login' ? 'active' : '' ?>">Connexion</a>
            <a href="login.php?mode=register" class="auth-tab <?= $mode === 'register' ? 'active' : '' ?>">Inscription</a>
        </div>

        <?php if ($error != ""): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success != ""): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if ($mode === 'login'): ?>

            <h2>Connexion</h2>

            <form method="post">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit" name="connecter">Se connecter</button>
            </form>

        <?php else: ?>

            <h2>Inscription</h2>

            <form method="post" id="register-form">
                <input type="text" name="nom" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="login_new" placeholder="Nom d'utilisateur" required>

                <input type="password" name="password_new" id="password_new" placeholder="Mot de passe" required minlength="6">

                <div class="strength-bar">
                    <span id="strength-fill"></span>
                </div>
                <p class="strength-label" id="strength-label">Force du mot de passe</p>

                <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmer le mot de passe" required>
                <p class="match-label" id="match-label"></p>

                <button type="submit" name="inscrire">Créer un compte</button>
            </form>

            <p style="font-size: small; margin-top: 15px; text-align: center;">
                En vous inscrivant, vous acceptez la
                <a href="../index.php#politique" style="color:#1f4e79; font-weight:bold;">politique de confidentialité</a>.
            </p>

        <?php endif; ?>

    </div>

    <script>
    // Indicateur de force du mot de passe + vérification de correspondance
    const pwd = document.getElementById('password_new');
    const confirm = document.getElementById('password_confirm');
    const fill = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    const matchLabel = document.getElementById('match-label');

    if (pwd) {
        pwd.addEventListener('input', function () {
            const v = pwd.value;
            let score = 0;

            if (v.length >= 6) score++;
            if (v.length >= 10) score++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            const levels = [
                { w: '0%',   c: '#ddd',    t: 'Force du mot de passe' },
                { w: '20%',  c: '#dc3545', t: 'Très faible' },
                { w: '40%',  c: '#dc3545', t: 'Faible' },
                { w: '60%',  c: '#f0ad4e', t: 'Moyen' },
                { w: '80%',  c: '#28a745', t: 'Fort' },
                { w: '100%', c: '#28a745', t: 'Très fort' }
            ];

            const lvl = levels[score];
            fill.style.width = lvl.w;
            fill.style.background = lvl.c;
            label.textContent = lvl.t;
            label.style.color = lvl.c === '#ddd' ? '#666' : lvl.c;
        });
    }

    if (confirm) {
        confirm.addEventListener('input', function () {
            if (confirm.value === '') {
                matchLabel.textContent = '';
            } else if (confirm.value === pwd.value) {
                matchLabel.textContent = '✓ Les mots de passe correspondent';
                matchLabel.style.color = '#28a745';
            } else {
                matchLabel.textContent = '✗ Les mots de passe ne correspondent pas';
                matchLabel.style.color = '#dc3545';
            }
        });
    }
    </script>

</body>
</html>
