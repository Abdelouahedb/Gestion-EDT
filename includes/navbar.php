<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

function isActive($page, $current_page) {
    return $page === $current_page ? 'active' : '';
}

function isGroupActive($pages, $current_page) {
    return in_array($current_page, $pages) ? 'active' : '';
}
?>

<nav class="main-navbar no-print">

    <div class="nav-logo">
        <a href="dashboard.php">Gestion EDT</a>
    </div>

    <div class="nav-menu">

        <a href="dashboard.php" class="nav-item <?= isActive('dashboard.php', $current_page) ?>">
            Accueil
        </a>

        <?php if ($role === 'admin'): ?>

            <div class="nav-dropdown">
                <button class="nav-item dropdown-btn <?= isGroupActive(
                    ['filieres.php', 'semestres.php', 'enseignants.php', 'salles.php', 'modules.php', 'creneaux.php'],
                    $current_page
                ) ?>">
                    Administration ▾
                </button>

                <div class="dropdown-content">
                    <a href="filieres.php">Filières</a>
                    <a href="semestres.php">Semestres</a>
                    <a href="enseignants.php">Enseignants</a>
                    <a href="salles.php">Salles</a>
                    <a href="modules.php">Modules</a>
                    <a href="creneaux.php">Créneaux</a>
                </div>
            </div>

            <a href="emploi.php" class="nav-item <?= isActive('emploi.php', $current_page) ?>">
                Emploi
            </a>

            <div class="nav-dropdown">
                <button class="nav-item dropdown-btn <?= isGroupActive(
                    ['planning_etudiant.php', 'planning_enseignant.php'],
                    $current_page
                ) ?>">
                    Plannings ▾
                </button>

                <div class="dropdown-content">
                    <a href="planning_etudiant.php">Planning Étudiant</a>
                    <a href="planning_enseignant.php">Planning Enseignant</a>
                </div>
            </div>

        <?php endif; ?>

        <?php if ($role === 'enseignant'): ?>
            <a href="planning_enseignant.php" class="nav-item <?= isActive('planning_enseignant.php', $current_page) ?>">
                Mon Planning
            </a>
        <?php endif; ?>

        <?php if ($role === 'etudiant'): ?>
            <a href="planning_etudiant.php" class="nav-item <?= isActive('planning_etudiant.php', $current_page) ?>">
                Mon Planning
            </a>
        <?php endif; ?>

    </div>

    <div class="nav-right">
        <span class="nav-role"><?= htmlspecialchars($role) ?></span>
        <a href="logout.php" class="logout-btn">Déconnexion</a>
    </div>

</nav>