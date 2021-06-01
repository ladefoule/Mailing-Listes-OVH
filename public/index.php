<?php 
require '../config.php';

if($singleSession)
    setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time()+3600, '/', '.'.$domain);

session_start();

// use ApiOvh;

require __DIR__ . '/../vendor/autoload.php';

$contenu = ''; // Layout content

$referer = $_SERVER['HTTP_REFERER'] ?? '/';
$messageError = $messageError . " <a class='ml-3 icon-left-outline' href='$referer'>retour</a>";

$name = $email = $error = ''; $emails = [];
$account = $_SESSION['account'] ?? '';
$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'index';

$parameters = explode('/', $action);
$nbParams = count($parameters);

$action = $parameters[$nbParams-1];

$controller = 'MailingListController'; // par défaut
if($nbParams >= 2){
    $name = $parameters[0]; // nom de la mailing list
    if($nbParams == 2 && in_array($action, ['subscriber', 'moderator'])){
        $controller = ucfirst($action) . 'Controller';
        $action = 'index';
    }
    if($nbParams >= 3){
        $controller = ucfirst($parameters[1]) . 'Controller';

        if($nbParams == 4)
            $email = $parameters[2];
    }
}

// $action correpondra à la méthode à appeler dans le controller. ex : createGet
$action = strtolower($action) . ucfirst(strtolower($requestMethod));

// Si la route n'existe pas
if(! method_exists($controller, $action)){
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
    'lang' => $lang,
    'action' => $action,
    'imap_server' => $imapServer,
    'class_error' => $classError,
    'message_error' => $messageError,
];

// Pour éviter d'agir sur une liste sur laquelle l'utilisateur n'est pas modérateur
if($name && ! $api->isModerator($name, $account.'@'.$domain)){
    $class = $global['class_error'];
    $message = $global['message_error'];
    ob_start();
    include('../views/notification.php');
    $contenu = ob_get_clean();
}else{
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
