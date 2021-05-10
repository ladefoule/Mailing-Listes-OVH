<?php

class ModeratorController
{
    /**
     * Liste des modérateurs de la mailing list
     * Path : /{name}/moderator
     *
     * @param array $params
     *
     * @return array
     */
    public static function indexGet(array $params)
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
            include('../views/list/moderator.php');
        
        return $global;
    }

    /**
     * Ajout d'un modérateur à la mailing list
     * Path : /{name}/moderator/create
     *
     * @param array $params
     *
     * @return array
     */
    public static function createGet(array $params)
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
     * Traitement de la requète de création d'un modérateur
     * Path : /{name}/moderator/create
     *
     * @param array $global
     *
     * @return array
     */
    public static function createPost(array $params)
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

        // Variables utilisées dans la view list/mailing-list.php
        $action = $global['action'];
        $account = $global['account'];
        $domain = $global['domain'];

        $mailingLists = $api->index($global);
        include('../views/list/mailing-list.php');
        return $global;
    }

    /**
     * Suppression d'un modérateur d'une mailing list
     * Path : /{name}/moderator/{email}/delete
     *
     * @param array $params
     *
     * @return array
     */
    public static function deleteGet(array $params)
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

        $mailingLists = $api->indexAccount($account);  
        include('../views/list/mailing-list.php');
        return $global;
    }
}
