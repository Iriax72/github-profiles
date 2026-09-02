<?php
// Signaler toutes les erreurs PHP (y compris E_STRICT, E_NOTICE, E_DEPRECATED)
error_reporting(E_ALL);

// Forcer l'affichage des erreurs a l'ecran
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');


require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'API.php';
$api = new API();
$prints = [];
if (isset($_POST['gh-account'])) {
    $prints[] = 'Le formulaire a bien ete recu';
    $accounts = $api->search_accounts($_POST['gh-account']); // Sécuriser contre l'injection de code !
    if ($accounts === null) {
        $prints[] = 'Erreur lors de la requete api';
    } else {
        $prints[] = 'Résultat de la requete:';
        /*foreach ($accounts['items'] ?? [] as $account) {
            $prints[] = htmlspecialchars($account['login'] ?? '', ENT_QUOTES, 'UTF-8');
        }*/
    }
}

$title = 'Accueil';
require 'elements' . DIRECTORY_SEPARATOR . 'header.php';

foreach ($prints as $print) {
    echo '<div class="print"> ' . $print . '</div><br/>';
}
?>

<form action="" method="post" class="stylized-form">
    <input type="text" name="gh-account" placeholder="Rechercher un compte github">
    <button type="submit"><img src="assets/search-icon.png" alt="Rechercher"></button>
</form>

<?php if (!empty($accounts)): ?>
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