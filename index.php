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
    if ($account === null) {
        $print[] = 'Erreur lors de la requete api';
    } else {
        $print[] = 'Résultat de la requete:';
        $print[] = $account;
    }
}

$title = 'Accueil';
require 'elements' . DIRECTORY_SEPARATOR . 'header.php';

foreach ($prints as $print) {
    echo '<div class="print"> ' . $print . '</div><br/>';
}
?>

<form action="" method="post">
    <input type="text" name="gh-account" placeholder="Rechercher un compte github">
    <button type="submit">rechercher</button>
</form>

<?php
require 'elements' . DIRECTORY_SEPARATOR.  'footer.php';
?>