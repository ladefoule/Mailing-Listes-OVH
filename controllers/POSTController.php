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
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);
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

        $options['moderatorMessage'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'moderatorMessage') ? true : false;
        $options['usersPostOnly'] = (isset($_POST['moderation']) && $_POST['moderation'] == 'usersPostOnly') ? true : false;
        $options['subscribeByModerator'] = isset($_POST['subscribeByModerator']) ? true : false;
        
        $api = $global['api'];
        $result = $api->changeOptions($name, $options);

        if($result) {
            $class = 'success';
            $message = "Options mises à jour avec succès !";
        }else{                        
            $class = $global['class_error'];
            $message = $global['message_error'];
        }
        include('../views/notification.php');

        $account = $global['account'];
        $domain = $global['domain'];
        $mailingLists = $api->indexAccount($account);
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
            $account = htmlspecialchars(strtolower($_POST['account']));
            
            $_SESSION['account'] = $account; // On active la SESSION
            $global['account'] = $account; // On met à jour la variable $global
            
            $mailingLists = $api->indexAccount($account);

            $class = 'info';
            $message = "Les différentes actions concernant les mailing lists (création d'une liste ou ajout d'un abonné par exemple) peuvent mettre plusieurs secondes avant d'être traitées par OVH. Si vous recevez un message de succès de votre requête, inutile donc de la renouveler même si le résultat n'est pas de suite visible.";
            include('../views/notification.php');

            include('../views/logged.php');
        }

        return $global;
    }

    /* --------------------------- */
    /*      GESTION DES ABONNES    */
    /* --------------------------- */

    /**
     * Method subscriberCreate
     *
     * @param array $global
     *
     * @return array
     */
    public static function subscriberCreate(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        $email = htmlspecialchars($_POST['email']);
        $result = $api->subscriberCreate($name, $email);

        if($result) {
            $class = 'success';
            $message = "Nouvel abonné ajouté avec succès !";
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

    /* ------------------------------- */
    /*      GESTION DES MODERATEURS    */
    /* ------------------------------- */

    /**
     * Method moderatorCreate
     *
     * @param array $global
     *
     * @return array
     */
    public static function moderatorCreate(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];

        $api = $global['api'];
        $email = htmlspecialchars($_POST['email']);
        $result = $api->moderatorCreate($name, $email);

        if($result) {
            $class = 'success';
            $message = "Le modérateur a été ajouté avec succès !";
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
}
