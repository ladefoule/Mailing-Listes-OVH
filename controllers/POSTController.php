<?php

class POSTController
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
        $api = $array['api'];
        $result = $api->post($array);

        if($result) {
            $class = 'success';
            $message = "Répondeur créé avec succès !";
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
     * index
     *
     * @param  mixed $array
     * @return void
     */
    public static function index(array $array)
    {
        $api = $array['api'];
        $domain = $array['domain'];
        $account = $array['account'];
        $buttons = $array['buttons'];
        $imapServer = $array['imap_server'];
        $email = htmlspecialchars($_POST['account']) .'@'. $domain;
        $password = htmlspecialchars($_POST['password']);

        if (! canLoginEmailAccount($imapServer, $email, $password)){        
            $class = 'danger';
            $message = "Impossible de vous connecter, veuillez rééssayer.";
            include('../views/notification.php');

            $account = '';
            include('../views/login.php');
        }else{
            // Variables utilisées dans la view logged.php
            $account = htmlspecialchars($_POST['account']);
            
            $_SESSION['account'] = $account; // On active la SESSION
            $array['account'] = $account; // On met à jour la variable $array
            
            $responder = $api->get($array);
            include('../views/logged.php');
        }

        return $array;
    }
}
