<?php

class MailingListController
{
    /**
     * Accueil
     * Path : /
     *
     * @param array $global
     * @return array
     */
    public static function indexGet(array $params)
    {
        $global = $params['global'];
        
        $api = $global['api'];
        $domain = $global['domain'];
        $account = $global['account'];

        // On supprime les données des formulaires potentiellement sauvegardées dans la SESSION
        unset($_SESSION['mailing-list']); 
        unset($_SESSION['subscriber']); 
        unset($_SESSION['moderator']); 
        
        if($account){
            $mailingLists = $api->indexAccount($account);
            include('../views/list/mailing-list.php');
        }else
            include('../views/login.php');

        return $global;
    }

    /**
     * Traitement de la requète de connexion
     * Path /
     *
     * @param  mixed $global
     * @return void
     */
    public static function indexPost(array $params)
    {
        $global = $params['global'];

        $api = $global['api'];
        $domain = $global['domain'];
        $account = $global['account'];
        $imapServer = $global['imap_server'];
        $email = htmlspecialchars($_POST['account']) .'@'. $domain;
        $password = htmlspecialchars($_POST['password']);

        if (! canLoginEmailAccount($imapServer, $email, $password)){        
            $class = 'danger';
            $message = "Impossible de vous connecter, veuillez rééssayer.";
            include('../views/notification.php');

            $account = '';
            include('../views/login.php');
        }else{
            // Variables utilisées dans la view list/mailing-list.php
            $account = htmlspecialchars(strtolower($_POST['account']));
            
            $_SESSION['account'] = $account; // On active la SESSION
            $global['account'] = $account; // On met à jour la variable $global
            
            $mailingLists = $api->indexAccount($account);

            $class = 'info';
            $message = "Les différentes actions concernant les mailing lists (création d'une liste ou ajout d'un abonné par exemple) peuvent mettre plusieurs secondes avant d'être traitées par OVH. Si vous recevez un message de succès de votre requête, inutile donc de la renouveler même si le résultat n'est pas de suite visible.";
            include('../views/notification.php');

            include('../views/list/mailing-list.php');
        }

        return $global;
    }

    /**
     * Création d'une mailing list
     * Path : /create
     *
     * @param array $global
     *
     * @return array
     */
    public static function createGet(array $params)
    {
        $global = $params['global'];
        
        // Variables utilisées dans la view form.php
        $action = $global['action'];
        $domain = $global['domain'];
        $account = $global['account'];

        $subscribeByModerator = false;
        $name = $replyTo = $moderation = '';
        $ownerEmail = $account .'@'. $domain;

        if(isset($_SESSION['mailing-list'])){
            $subscribeByModerator = $_SESSION['mailing-list']['subscribeByModerator'] ?? '';
            $replyTo = $_SESSION['mailing-list']['replyTo'] ?? '';
            $ownerEmail = $_SESSION['mailing-list']['ownerEmail'] ?? '';
            $name = $_SESSION['mailing-list']['name'] ?? '';
            $moderation = $_SESSION['mailing-list']['$moderation'] ?? '';
        }
        
        include('../views/form/mailing-list.php');
        return $global;
    }

    /**
     * Traitement de la requète de création d'une mailing list
     * Path : /create
     *
     * @param array $global
     *
     * @return array
     */
    public static function createPost(array $params)
    {
        $global = $params['global'];

        $api = $global['api'];
        $name = htmlspecialchars($_POST['name']);
        $replyTo = htmlspecialchars($_POST['replyTo']);
        $ownerEmail = htmlspecialchars($_POST['ownerEmail']);
        $options['moderatorMessage'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'moderatorMessage') ? true : false;
        $options['usersPostOnly'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'usersPostOnly') ? true : false;
        $options['subscribeByModerator'] = isset($_POST['subscribeByModerator']) ? true : false;

        $request = array(
            'language' => $global['lang'], // Language of mailing list (type: domain.DomainMlLanguageEnum)
            'name' => $name, // Mailing list name (type: string)
            'options' => $options, // Options of mailing list (type: domain.DomainMlOptionsStruct)
            'ownerEmail' => $ownerEmail, // Owner Email (type: string)
            'replyTo' => $replyTo, // Email to reply of mailing list (type: string)
        );

        $result = $api->create($request);

        if($result) {
            $class = 'success';
            $message = "Mailing list créée avec succès !";

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['mailing-list']); 
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];

            // On sauvegarde en session le contenu du formulaire
            $_SESSION['mailing-list']['name'] = $request['name'];
            $_SESSION['mailing-list']['ownerEmail'] = $request['ownerEmail'];
            $_SESSION['mailing-list']['replyTo'] = $request['replyTo'];
            $_SESSION['mailing-list']['moderation'] = $request['moderation'];
            $_SESSION['mailing-list']['subscribeByModerator'] = $request['subscribeByModerator'];
        }
        include('../views/notification.php');

        // Variables utilisées dans la view list/mailing-list.php
        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);
        include('../views/list/mailing-list.php');
        return $global;
    }

    /**
     * Détail d'une mailing list
     * Path : /{name}/show
     *
     * @param array $params
     *
     * @return array
     */
    public static function showGet(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        $domain = $global['domain'];

        $mailingList = $api->show($name);

        // Le nbSubscribers retourné par OVH n'est pas toujours bon donc on le recalcule avec le vrai nombre d'abonnés
        $mailingList['nbSubscribers'] = count($api->subscriber($name) ?? []);
        
        if($mailingList) {
            $mailingList['nbModerators'] = count($api->moderator($name) ?? []);

            include('../views/show/mailing-list.php');
        } else {
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');

            include('../views/list/mailing-list.php');
        }
        
        return $global;
    }
    
    /**
     * Modification d'une mailing list
     * Path : /{name}/update
     *
     * @param array $global
     *
     * @return array
     */
    public static function updateGet(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $domain = $global['domain'];
        $api = $global['api'];
        $action = $global['action'];

        $mailingList = $api->show($name);
        
        if($mailingList === false) {
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');

            include('../views/list/mailing-list.php');
        } else {
            $replyTo = $mailingList['replyTo'];
            $ownerEmail = $mailingList['ownerEmail'];

            include('../views/form/mailing-list.php');
        }

        return $global;
    }

     /**
     * Traitement de la requète de modification d'une mailing list
     * Path : /{name}/update
     *
     * @param array $global
     *
     * @return array
     */
    public static function updatePost(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $replyTo = htmlspecialchars($_POST['replyTo']);
        $ownerEmail = htmlspecialchars($_POST['ownerEmail']);
        
        $request = [
            'replyTo' => $replyTo,
            'ownerEmail' => $ownerEmail,
        ];
        
        $api = $global['api'];
        $result = $api->update($name, $request);

        if($result) {
            $class = 'success';
            $message = "MailingList modifiée avec succès !";

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['mailing-list']); 
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];

            $_SESSION['mailing-list']['ownerEmail'] = $request['ownerEmail'];
            $_SESSION['mailing-list']['replyTo'] = $request['replyTo'];
        }
        include('../views/notification.php');

        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);
        include('../views/list/mailing-list.php');
        return $global;
    }

    /**
     * Modification des options d'une mailing-list
     * Path : /{name}/options
     *
     * @param array $params
     *
     * @return array
     */
    public static function optionsGet(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $domain = $global['domain'];
        $api = $global['api'];
        $action = $global['action'];

        $mailingList = $api->show($name);
        
        if($mailingList) {
            $usersPostOnly = $mailingList['options']['usersPostOnly'];
            $moderatorMessage = $mailingList['options']['moderatorMessage'];
            $subscribeByModerator = $mailingList['options']['subscribeByModerator'];

            $moderation = '';
            if($usersPostOnly) $moderation = 'usersPostOnly';
            if($moderatorMessage) $moderation = 'moderatorMessage';

            include('../views/form/mailing-list.php');
        } else {
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');

            include('../views/list/mailing-list.php');
        }

        return $global;
    }

    /**
     * Traitement de la requète de modification des options d'une mailing list
     * Path : /{name}/options
     *
     * @param array $global
     *
     * @return array
     */
    public static function optionsPost(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $options['moderatorMessage'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'moderatorMessage') ? true : false;
        $options['usersPostOnly'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'usersPostOnly') ? true : false;
        $options['subscribeByModerator'] = isset($_POST['subscribeByModerator']) ? true : false;
        
        $api = $global['api'];
        $result = $api->changeOptions($name, $options);

        if($result) {
            $class = 'success';
            $message = "Options mises à jour avec succès !";

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['mailing-list']); 
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];

            // On sauvegarde en session le contenu du formulaire
            $_SESSION['mailing-list']['moderation'] = $_POST['moderation'];
            $_SESSION['mailing-list']['subscribeByModerator'] = $_POST['subscribeByModerator'];
        }
        include('../views/notification.php');

        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);
        include('../views/list/mailing-list.php');
        return $global;
    }

    /**
     * Suppression d'une mailing list
     * Path : /{name}/delete
     *
     * @param array $params
     *
     * @return array
     */
    public static function deleteGet(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        $result = $api->delete($name);
        if($result) {  
            $class = 'success';
            $message = "MailingList supprimée avec succès !";
        }else{            
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');
        
        // Variables utilisées dans la view list/mailing-list.php
        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);

        include('../views/list/mailing-list.php');

        return $global;
    }
    
    /**
     * Déconnexion
     * Path : /logout
     *
     * @param array $params
     *
     * @return array
     */
    public static function logoutGet(array $params)
    {
        session_destroy();
        $global = $params['global'];
        
        $message = "Vous êtes déconnecté.";
        $class = "success";
        include('../views/notification.php');
        
        // Variables utilisées dans la view login.php
        $account = '';
        $domain = $global['domain'];
        include('../views/login.php');
        
        $global['account'] = '';
        return $global;
    }
}
