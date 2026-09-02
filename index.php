<?php
$prints = [];
if (isset($_POST['gh-account'])) {
    $prints[] = 'Le formulaire a bien ete recu';
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