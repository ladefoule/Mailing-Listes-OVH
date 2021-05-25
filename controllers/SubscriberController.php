<?php 

class SubscriberController
{
    /**
     * Liste des abonnés de la mailing list
     * Path : /{name}/subscriber
     *
     * @param array $global
     *
     * @return array
     */
    public static function indexGet(array $params)
    {
        $global = $params['global'];
        $name = $params['name'];
        
        $api = $global['api'];
        $domain = $global['domain'];

        $emails = $api->subscriber($name);

        // var_dump($emails);exit();
        
        if($emails === false){
            $class = $global['class_error'];
            $message = $global['message_error'];
            include('../views/notification.php');
        }else
            include('../views/list/subscriber.php');

        return $global;
    }

    /**
     * Ajout d'un abonné à la mailing list
     * Path : /{name}/subscriber/create
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

        $emails = '';

        if(isset($_SESSION['subscriber'])){
            $emails = $_SESSION['subscriber']['emails'] ?? '';
        }
        
        include('../views/form/subscriber.php');
        return $global;
    }

    /**
     * Traitement de la requète d'ajout d'un modérateur
     * Path : /{name}/subscriber/create
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

        $emails = preg_split("/\\r\\n|\\r|\\n/", $_POST['emails'] ?? '');
        // $emails = explode(PHP_EOL, $_POST['emails'] ?? '');
        
        foreach ($emails as $email) {
            $email = htmlspecialchars($email);
            $result = $api->subscriberCreate($name, $email);

            if($result)
                $success[] = $email;
            else
                $error[] = $email;
        }

        $class = 'info';

        $message = '';
        if($success){
            $message .= "Abonnés ajoutés :<br>";
            foreach ($success as $email)
            $message .= $email . "<br>";
        }

        if($error){
            $message .= "<br>Erreurs :<br>";
            foreach ($error as $email)
                $message .= $email . "<br>";
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
     * Suppression d'un abonné d'une mailing list
     * Path : /{name}/subscriber/{email}/delete
     *
     * @param array $global
     *
     * @return array
     */
    public static function deleteGet(array $params)
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
            $message = "$email a été supprimé de la liste ($name@$domain) avec succès !";
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