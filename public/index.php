<?php 
session_start();

use ApiOvh;

require __DIR__ . '/../vendor/autoload.php';
require '../config.php';

$contenu = ''; // Layout content

$referer = $_SERVER['HTTP_REFERER'] ?? '/';
$messageError = $messageError . " <a class='ml-3 icon-left-outline' href='$referer'>retour</a>";

$name = '';
$account = $_SESSION['account'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'index';

$parameters = explode('/', $action);
$nbParams = count($parameters);

switch ($nbParams) {
    case 2:
        $name = $parameters[0];
        $action = $parameters[1];
        break;
    
    case 3:
        $name = $parameters[0];
        $type = $parameters[1];
        $action = $type . 'Create';
        break;
    
    case 4:
        $name = $parameters[0];
        $type = $parameters[1];
        $email = $parameters[2];
        $action = $type . 'Delete';
        break;

    default:
        # code...
        break;
}

// Si la route n'existe pas ou si elle existe mais que l'utilisateur n'est pas connecté
// if(! in_array($action, $routes[$method]) || (! $account && $action != 'index')){
//     header("Location: /");
//     exit;
// }

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
    'email' => $account.'@'.$domain,
    'name' => $name,
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
// $array = $controller::$action($array);
$contenu = ob_get_clean();

// $mailingLists = $api->get($array);
$account = $array['account'];
require '../views/layout.php';
