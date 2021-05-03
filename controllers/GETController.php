<?php

class GETController
{
    /**
     * Method create
     *
     * @param array $array
     *
     * @return array
     */
    public static function create(array $array)
    {
        // Variables utilisées dans la view form.php
        $domain = $array['domain'];
        $account = $array['account'];
        $action = $array['action'];
        $to = $from = $content = '';
        $copy = true;
        $formMethod = 'POST';
        $buttons = $array['buttons'];
        
        if(isset($_SESSION['form'])){
            $copy = $_SESSION['form']['copy'];
            $from = $_SESSION['form']['from'];
            $to = $_SESSION['form']['to'];
            $content = $_SESSION['form']['content'];
        }
        
        include('../views/form.php');
        return $array;
    }
    
    /**
     * Method show
     *
     * @param array $array
     *
     * @return array
     */
    public static function show(array $array)
    {
        $domain = $array['domain'];
        $account = $array['account'];
        $api = $array['api'];
        $action = $array['action'];
        $formMethod = 'GET';
        $buttons = $array['buttons'];

        $responder = $api->get($array);

        if($responder) {
            // Variables utilisées dans la view form.php
            $copy = $responder['copy'];
            $content = htmlentities($responder['content']);

            include('../views/form.php');
        } else {
            $class = $array['class_error'];
            $message = $array['message_error'];
            include('../views/notification.php');

            include('../views/logged.php');
        }

        return $array;
    }
    
    /**
     * Method delete
     *
     * @param array $array
     *
     * @return array
     */
    public static function delete(array $array)
    {
        $api = $array['api'];
        $result = $api->delete($array);
        if($result) {  
            $class = 'success';
            $message = "Répondeur supprimé avec succès !";
        }else{            
            $class = $array['class_error'];
            $message = $array['message_error'];
        }
        include('../views/notification.php');
        
        // Variables utilisées dans la view logged.php
        $action = $array['action'];
        $buttons = $array['buttons'];
        $account = $array['account'];
        $domain = $array['domain'];
        $responder = $api->get($array);
        include('../views/logged.php');

        return $array;
    }
    
    /**
     * Method logout
     *
     * @param array $array
     *
     * @return array
     */
    public static function logout(array $array)
    {
        session_destroy();
        
        $message = "Vous êtes déconnecté.";
        $class = "success";
        include('../views/notification.php');
        
        // Variables utilisées dans la view login.php
        $account = '';
        $domain = $array['domain'];
        include('../views/login.php');
        
        $array['account'] = '';
        return $array;
    }
    
    /**
     * Method index
     *
     * @param array $array
     *
     * @return array
     */
    public static function index(array $array)
    {
        $api = $array['api'];
        $email = $array['email'];
        $account = $array['account'];
        $lists = []; // Contiendra toutes les listes dans lesquelles l'user est modérateur

        $mailingLists = $api->all($array);
        foreach ($mailingLists as $mailingList) {
            $array['name'] = $mailingList;
            $moderators = $api->allModerators($array);

            if(in_array($email, $moderators))
                $lists[] = $mailingList;
        }
            

        // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
        // unset($_SESSION['form']); 

        // Variables utilisées dans la view logged.php
        $action = $array['action'];
        $buttons = $array['buttons'];
        $name = $array['name'];
        $domain = $array['domain'];
        
        if($account){
            // $mailingLists = $api->all($array);
            // var_dump($lists);exit();
            include('../views/logged.php');
        }else
            include('../views/login.php');

        return $array;
    }
}
