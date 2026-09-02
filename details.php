<?php
if (!isset($_GET['login'])) {
    header('Location: index.php');
    exit();
}
$login = $_GET['login'];

require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'API.php';
$api = new API();
$accountDetails = $api->get_account_details($login);
if ($accountDetails === null) {
    $error = 'Une erreur est survenue (Erreur lors de la requete api)';
}

function info(string $name, string $value) : string
{
    return '<p>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ': ' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</p>';
}

$title = 'Détails du compte ' . htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
require __DIR__ . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'header.php';

if (isset($error)) {
    echo '<div class="error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
}
?>

<a href="index.php" id="back-link">&lt;=</a>

<h2><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></h2>
<?= info('Nom', $accountDetails['name'] ?? 'Pas de nom') ?>
<?= info('Email', $accountDetails['email'] ?? 'Pas d\'email') ?>
<?= info('Localisation', $accountDetails['location'] ?? 'Inconnue') ?>
<img src="<?= htmlspecialchars($accountDetails['avatar_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>">
<?= info('Biographie', $accountDetails['bio'] ?? 'Pas de biographie') ?>
<?= info('Nombre de repos', $accountDetails['public_repos'] ?? '0') ?>
<?= info('Nombre de followers', $accountDetails['followers'] ?? '0') ?>
<?= info('Nombre de following', $accountDetails['following'] ?? '0') ?>
<?= info('Date de création', $accountDetails['created_at'] ?? 'Inconnue') ?>
<?= info('Date de dernière activité', $accountDetails['updated_at'] ?? 'Inconnue') ?>
<a href="<?= htmlspecialchars($accountDetails['html_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>" target="_blank">page github de <?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></a>

<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'footer.php';
?>