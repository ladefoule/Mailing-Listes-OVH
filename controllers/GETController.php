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
        $email = $account .'@'. $domain;

        $mailingLists = []; // Contiendra toutes les listes dans lesquelles le $account est modérateur (ou propriétaire)
        $lists = $api->index();
        foreach ($lists as $mailingList) {
            $moderators = $api->moderator($mailingList);

            if(in_array($email, $moderators))
                $mailingLists[] = $mailingList;
        }
        
        // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
        unset($_SESSION['mailing-list']); 
        
        if($account){
            // $mailingLists = $api->all($global);
            // var_dump($lists);exit();
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
        $action = $global['action'];
        
        // Variables utilisées dans la view form.php
        $domain = $global['domain'];
        $account = $global['account'];

        $moderatorMessage = $moderatorMessage = $moderatorMessage = false;
        $name = $ownerEmail = $replyTo = '';

        if(isset($_SESSION['mailing-list'])){
            $moderatorMessage = $_SESSION['mailing-list']['moderatorMessage'] ?? '';
            $subscribeByModerator = $_SESSION['mailing-list']['subscribeByModerator'] ?? '';
            $usersPostOnly = $_SESSION['mailing-list']['usersPostOnly'] ?? '';
            $replyTo = $_SESSION['mailing-list']['replyTo'] ?? '';
            $ownerEmail = $_SESSION['mailing-list']['ownerEmail'] ?? '';
            $name = $_SESSION['mailing-list']['name'] ?? '';
        }
        
        include('../views/form/mailing-list.php');
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
        $account = $global['account'];
        $api = $global['api'];
        $action = $global['action'];

        $mailingList = $api->show($name);
        
        if($mailingList) {
            // Variables utilisées dans la view form.php
            $nbSubscribers = $mailingList['nbSubscribers'];
            $replyTo = $mailingList['replyTo'];
            $usersPostOnly = $mailingList['options']['usersPostOnly'];
            $moderatorMessage = $mailingList['options']['moderatorMessage'];
            $subscribeByModerator = $mailingList['options']['subscribeByModerator'];
            $ownerEmail = $mailingList['ownerEmail'];
            // $content = htmlentities($mailingList['content']);

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
        $account = $global['account'];
        $api = $global['api'];
        $action = $global['action'];

        $mailingList = $api->show($name);
        
        if($mailingList) {
            $usersPostOnly = $mailingList['options']['usersPostOnly'];
            $moderatorMessage = $mailingList['options']['moderatorMessage'];
            $subscribeByModerator = $mailingList['options']['subscribeByModerator'];

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
     * @param array $global
     *
     * @return array
     */
    public static function delete(array $global)
    {
        $api = $global['api'];
        $result = $api->delete($global);
        if($result) {  
            $class = 'success';
            $message = "Répondeur supprimé avec succès !";
        }else{            
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');
        
        // Variables utilisées dans la view logged.php
        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->get($global);

        include('../views/logged.php');

        return $global;
    }
    
    /**
     * Method logout
     *
     * @param array $global
     *
     * @return array
     */
    public static function logout(array $global)
    {
        session_destroy();
        
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
     * Method index
     *
     * @param array $global
     *
     * @return array
     */
    public static function subscriber(array $params)
    {
        $global = $params['global'];
        
        $api = $global['api'];
        $name = $global['name'];
        $domain = $global['domain'];

        $emails = $api->subscriber($name);            
        
        include('../views/subscriber.php');

        return $global;
    }

    /**
     * Method subscriberCreate
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
     * Method index
     *
     * @param array $global
     *
     * @return array
     */
    public static function subscriberDelete(array $params)
    {
        $global = $params['global'];
        $email = $params['email'];
        
        $api = $global['api'];
        $name = $global['name'];
        $domain = $global['domain'];
        
        $result = $api->subscriberDelete($name, $email);

        if($result) {
            $class = 'success';
            $message = "L'email $email a été supprimé de la liste avec succès ! (effectif dans 5 secondes)";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        $emails = $api->subscriber($name);  
        include('../views/subscriber.php');
        return $global;
    }
}
