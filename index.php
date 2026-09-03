<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'API.php';
$api = new API();

if (isset($_POST['gh-account'])) {
    try {
        $accounts = $api->search_accounts($_POST['gh-account']); // Sécuriser contre l'injection de code !
    } catch (Exception $e) {
        echo '<p>Une erreur est survenue lors de la recherche.</p>';
    }
}

$title = 'Accueil';
require 'elements' . DIRECTORY_SEPARATOR . 'header.php'; 
?>

<form action="" method="post" class="stylized-form">
    <input type="text" name="gh-account" placeholder="Rechercher un compte github">
    <button type="submit"><img src="assets/search-icon.png" alt="Rechercher"></button>
</form>

<?php if (isset($accounts) && !empty($accounts)): ?>
    <h2>Résultats de la recherche</h2>
    <ul>
        <?php foreach ($accounts['items'] ?? [] as $account): ?>
            <li>
                <a href="details.php?login=<?= urlencode($account['login']) ?>">
                    <?= htmlspecialchars($account['login']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php
require 'elements' . DIRECTORY_SEPARATOR.  'footer.php';
?>