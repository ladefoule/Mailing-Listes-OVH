<?php

class GETController
{
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
        $domain = $global['domain'];
        $account = $global['account'];
        $email = $global['email'];

        $moderatorMessage = $moderatorMessage = $moderatorMessage = false;
        $name = $ownerEmail = $replyTo = '';

        if(isset($_SESSION['form'])){
            $moderatorMessage = $_SESSION['form']['moderatorMessage'] ?? '';
            $subscribeByModerator = $_SESSION['form']['subscribeByModerator'] ?? '';
            $usersPostOnly = $_SESSION['form']['usersPostOnly'] ?? '';
            $replyTo = $_SESSION['form']['replyTo'] ?? '';
            $ownerEmail = $_SESSION['form']['ownerEmail'] ?? '';
            $name = $_SESSION['form']['name'] ?? '';
        }
        
        include('../views/form.php');
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

            include('../views/form.php');
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
        $email = $global['email'];
        $account = $global['account'];
        $mailingLists = []; // Contiendra toutes les listes dans lesquelles le $account est modérateur (ou propriétaire)

        $lists = $api->index();
        foreach ($lists as $mailingList) {
            $global['name'] = $mailingList;
            $moderators = $api->moderator($global);

            if(in_array($email, $moderators))
                $mailingLists[] = $mailingList;
        }
            

        // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
        unset($_SESSION['form']); 

        // Variables utilisées dans la view logged.php
        $name = $global['name'];
        $domain = $global['domain'];
        
        if($account){
            // $mailingLists = $api->all($global);
            // var_dump($lists);exit();
            include('../views/logged.php');
        }else
            include('../views/login.php');

        return $global;
    }
}
