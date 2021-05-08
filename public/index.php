<?php 
session_start();

// use ApiOvh;

require __DIR__ . '/../vendor/autoload.php';
require '../config.php';

$contenu = ''; // Layout content

$referer = $_SERVER['HTTP_REFERER'] ?? '/';
$messageError = $messageError . " <a class='ml-3 icon-left-outline' href='$referer'>retour</a>";

$name = $email = $error = ''; $emails = [];
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
        break;
}

$routes = [
    'GET' => ['index', 'logout', 'create', 'show', 'update', 'options', 'delete', 'moderator', 'subscriber', 'moderatorCreate', 'subscriberCreate', 'moderatorDelete', 'subscriberDelete'],
    'POST' => ['index', 'create', 'update', 'options', 'moderatorCreate', 'subscriberCreate'],
];

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
    'domain' => $domain,
]);

// Les données utilisées dans les différentes méthodes des models et controllers
$global = [
    'domain' => $domain,
    'account' => $account,
    'api' => $api,
    'action' => $action,
    'imap_server' => $imapServer,
    'class_error' => $classError,
    'message_error' => $messageError,
];

$controller = $method.'Controller';

// Pour éviter d'agir sur une liste sur laquelle l'utilisateur n'est pas modérateur
if($name && ! $api->isModerator($name, $account.'@'.$domain)){
    $class = $global['class_error'];
    $message = $global['message_error'];
    ob_start();
    include('../views/notification.php');
    $contenu = ob_get_clean();
}else{
    // echo $action;exit();
    ob_start();
    $global = $controller::$action([
        'global' => $global,
        'name' => $name, // Contient le nom de la liste sélectionnée
        'email' => $email, // L'adresse email de l'abonné ou du modérateur sélectionné
    ]);
    $contenu = ob_get_clean();
}

// $mailingLists = $api->get($global);
$account = $global['account'];
require '../views/layout.php';
