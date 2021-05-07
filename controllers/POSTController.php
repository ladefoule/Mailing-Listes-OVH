<?php

class POSTController
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

        $api = $global['api'];
        // var_dump($global);exit();
        $name = htmlspecialchars($_POST['name']);
        $replyTo = htmlspecialchars($_POST['replyTo']);
        $ownerEmail = htmlspecialchars($_POST['ownerEmail']);
        $options['moderatorMessage'] = isset($_POST['moderatorMessage']) ? true : false;
        $options['subscribeByModerator'] = isset($_POST['subscribeByModerator']) ? true : false;
        $options['usersPostOnly'] = isset($_POST['usersPostOnly']) ? true : false;

        $request = array(
            'language' => 'fr', // Language of mailing list (type: domain.DomainMlLanguageEnum)
            'name' => $name, // Mailing list name (type: string)
            'options' => $options, // Options of mailing list (type: domain.DomainMlOptionsStruct)
            'ownerEmail' => $ownerEmail, // Owner Email (type: string)
            'replyTo' => $replyTo, // Email to reply of mailing list (type: string)
        );

        $result = $api->create($request);

        if($result) {
            $class = 'success';
            $message = "Répondeur créé avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        // Variables utilisées dans la view logged.php
        $action = $global['action'];
        $account = $global['account'];
        $domain = $global['domain'];

        $mailingLists = $api->index($global);
        include('../views/logged.php');
        return $global;
    }

    /**
     * Mise à jour des infos de la mailingList
     *
     * @param array $global
     *
     * @return array
     */
    public static function update(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        
        $replyTo = htmlspecialchars($_POST['replyTo']);
        $ownerEmail = htmlspecialchars($_POST['ownerEmail']);

        $request = [
            // 'options' => $options,
            'replyTo' => $replyTo,
            'ownerEmail' => $ownerEmail,
        ];

        $result = $api->update($name, $request);

        if($result) {
            $class = 'success';
            $message = "MailingList modifiée avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        // Variables utilisées dans la view logged.php
        $action = $global['action'];
        $account = $global['account'];
        $domain = $global['domain'];

        $mailingLists = $api->index($global);
        include('../views/logged.php');
        return $global;
    }

    /**
     * Mise à jour des options de la mailingList
     *
     * @param array $global
     *
     * @return array
     */
    public static function options(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];

        // var_dump($global);exit();
        $options['moderatorMessage'] = isset($_POST['moderatorMessage']) ? true : false;
        $options['subscribeByModerator'] = isset($_POST['subscribeByModerator']) ? true : false;
        $options['usersPostOnly'] = isset($_POST['usersPostOnly']) ? true : false;

        $result = $api->changeOptions($name, $options);

        if($result) {
            $class = 'success';
            $message = "Options mis à jour avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        // Variables utilisées dans la view logged.php
        $action = $global['action'];
        $account = $global['account'];
        $domain = $global['domain'];

        $mailingLists = $api->index($global);
        include('../views/logged.php');
        return $global;
    }
    
    /**
     * index
     *
     * @param  mixed $global
     * @return void
     */
    public static function index(array $params)
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
            // Variables utilisées dans la view logged.php
            $account = htmlspecialchars($_POST['account']);
            
            $_SESSION['account'] = $account; // On active la SESSION
            $global['account'] = $account; // On met à jour la variable $global
            
            $responder = $api->index($global);
            include('../views/logged.php');
        }

        return $global;
    }
}
