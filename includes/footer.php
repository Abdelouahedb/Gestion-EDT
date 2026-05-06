<?php ?>

<footer class="landing-footer">
    <div class="footer-grid">
        <div class="footer-col">
            <strong>GestionEDT</strong>
            <p>Application web de gestion des emplois du temps scolaires</p>
        </div>

        <div class="footer-col">
            <strong>Contact</strong>
            <p>ENSIASD — Taroudant</p>
            <p>contact@gestion-edt.ma</p>
        </div>

        <div class="footer-col">
            <strong>Liens</strong>
            <p><a href="<?= (basename($_SERVER['PHP_SELF']) === 'index.php') ? '#politique' : '../index.php#politique' ?>">Politique de confidentialité</a></p>
            <p><a href="<?= (basename($_SERVER['PHP_SELF']) === 'index.php') ? '#politique' : '../index.php#politique' ?>">Mentions légales</a></p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2026 GestionEDT — Mini Projet Développement Web</p>
    </div>
</footer>
