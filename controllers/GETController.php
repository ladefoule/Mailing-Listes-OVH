<?php

class GETController
{
    /**
     * Method index
     *
     * @param array $global
     *
     * @return array
     */
    public static function index(array $params)
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
            include('../views/logged.php');
        }else
            include('../views/login.php');

        return $global;
    }

    /**
     * Method create
     *
     * @param array $global
     *
     * @return array
     */
    public static function create(array $params)
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
     * Method show
     *
     * @param array $params
     *
     * @return array
     */
    public static function show(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        $domain = $global['domain'];

        $mailingList = $api->show($name);
        $mailingList['nbModerators'] = count($api->moderator($name));

        include('../views/show-mailing-list.php');

        return $global;
    }
    
    /**
     * Method update
     *
     * @param array $global
     *
     * @return array
     */
    public static function update(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $domain = $global['domain'];
        $api = $global['api'];
        $action = $global['action'];

        $mailingList = $api->show($name);
        
        if($mailingList) {
            $replyTo = $mailingList['replyTo'];
            $ownerEmail = $mailingList['ownerEmail'];

            include('../views/form/mailing-list.php');
        } else {
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');

            include('../views/logged.php');
        }

        return $global;
    }

    /**
     * Method options
     *
     * @param array $params
     *
     * @return array
     */
    public static function options(array $params)
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

            include('../views/logged.php');
        }

        return $global;
    }
    
    /**
     * Method delete
     *
     * @param array $params
     *
     * @return array
     */
    public static function delete(array $params)
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
        
        // Variables utilisées dans la view logged.php
        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);

        include('../views/logged.php');

        return $global;
    }
    
    /**
     * Method logout
     *
     * @param array $params
     *
     * @return array
     */
    public static function logout(array $params)
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

    /* --------------------------- */
    /*      GESTION DES ABONNES    */
    /* --------------------------- */

    /**
     * Liste des abonnés de la liste $name
     *
     * @param array $global
     *
     * @return array
     */
    public static function subscriber(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];
        
        $api = $global['api'];
        $domain = $global['domain'];

        $emails = $api->subscriber($name);
        
        if($emails === false){
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');
        }else
            include('../views/subscriber.php');

        return $global;
    }

    /**
     * Ajout d'un abonné dans la liste $name
     *
     * @param array $params
     *
     * @return array
     */
    public static function subscriberCreate(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $action = $global['action'];
        $domain = $global['domain'];

        $email = '';

        if(isset($_SESSION['subscriber'])){
            $email = $_SESSION['subscriber']['email'] ?? '';
        }
        
        include('../views/form/subscriber.php');
        return $global;
    }

    /**
     * Suppression de l'abonné $email de la liste $name
     *
     * @param array $global
     *
     * @return array
     */
    public static function subscriberDelete(array $params)
    {
        $global = $params['global'];
        $email = $params['email'];
        $name = $params['name'];
        
        $api = $global['api'];
        $domain = $global['domain'];
        $account = $global['account'];
        
        $result = $api->subscriberDelete($name, $email);

        if($result) {
            $class = 'success';
            $message = "$email a été supprimé de la liste avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        $emails = $api->subscriber($name);  
        include('../views/subscriber.php');

        return $global;
    }

    /* ------------------------------- */
    /*      GESTION DES MODERATEURS    */
    /* ------------------------------- */

    /**
     * Liste des modérateurs de la liste $name
     *
     * @param array $params
     *
     * @return array
     */
    public static function moderator(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];
        
        $api = $global['api'];
        $domain = $global['domain'];

        $emails = $api->moderator($name);
        
        if($emails === false){
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');
        }else
            include('../views/moderator.php');
        
        return $global;
    }

    /**
     * Ajout d'un modérateur à la liste $name
     *
     * @param array $params
     *
     * @return array
     */
    public static function moderatorCreate(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $action = $global['action'];
        $domain = $global['domain'];

        $email = '';

        if(isset($_SESSION['moderator'])){
            $email = $_SESSION['moderator']['email'] ?? '';
        }
        
        include('../views/form/moderator.php');
        return $global;
    }

    /**
     * Suppression d'un modérateur dans la liste $name
     *
     * @param array $params
     *
     * @return array
     */
    public static function moderatorDelete(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];
        $email = $params['email'];
        
        $api = $global['api'];
        $domain = $global['domain'];
        
        $result = $api->moderatorDelete($name, $email);

        if($result) {
            $class = 'success';
            $message = "$email a été supprimé de la liste avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        $emails = $api->moderator($name);  
        include('../views/moderator.php');
        return $global;
    }
}
