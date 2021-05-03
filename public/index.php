<?php 
session_start();

use ApiOvh;
use Carbon\Carbon;

require __DIR__ . '/../vendor/autoload.php';
require '../config.php';

Carbon::setLocale($lang);

$contenu = ''; // Layout content

$routes = [
    'GET' => ['index', 'create', 'show', 'delete', 'logout'],
    'POST' => ['index', 'create'],
];

$referer = $_SERVER['HTTP_REFERER'] ?? '/';
$messageError = $messageError . " <a class='ml-3 icon-left-outline' href='$referer'>retour</a>";

$account = $_SESSION['account'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'index';

// Si la route n'existe pas ou si elle existe mais que l'utilisateur n'est pas connecté
if(! in_array($action, $routes[$method]) || (! $account && $action != 'index')){
    header("Location: /");
    exit;
}

$api = new ApiOvh([
    'application_key' => $applicationKey,
    'application_secret' => $applicationSecret,
    'consumer_key' => $consumerKey,
    'endpoint' => $endpoint,
]);

// Les données utilisées dans les différentes méthodes des models et controllers
$array = [
    'domain' => $domain,
    'account' => $account,
    'api' => $api,
    'buttons' => $buttons,
    'action' => $action,
    'imap_server' => $imapServer,
    'class_error' => $classError,
    'message_error' => $messageError,
];

$controller = $method.'Controller';

ob_start();
$array = $controller::$action($array);
$contenu = ob_get_clean();

$responder = $api->get($array);
$account = $array['account'];
require '../views/layout.php';
