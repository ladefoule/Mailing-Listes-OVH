<?php 
// session_start();
use Ovh\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MailingList
{
    private $api;

    public function __construct($array)
    {
        $client = new Client([
            'timeout' => 1,
            'headers' => [
                'User-Agent' => 'api_client'
            ]
        ]);
    
        // Initiation de la connexion à l'API OVH
        $api = new Api($array['application_key'],
            $array['application_secret'],
            $array['endpoint'],
            $array['consumer_key'],
            $client
        );
        
        $this->api = $api;
    }

    /**
     * Récupération de toutes les mailingLists
     */
    public function all($array)
    {
        $domain = $array['domain'];

        try {
            return $this->api->get("/email/domain/$domain/mailingList/");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Récupération des infos de la mailing list
    public function get($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];

        if(! $name)
            return false;

        try {
            return $this->api->get("/email/domain/$domain/mailingList/$name");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Création d'une mailing list
    public function post($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];
        $options = $array['options'];
        $ownerEmail = $array['ownerEmail'];
        $replyTo = $array['replyTo'];

        try {
            $this->api->post("/email/domain/$domain/mailingList/", array(
                'language' => 'fr', // Language of mailing list (type: domain.DomainMlLanguageEnum)
                'name' => $name, // Mailing list name (type: string)
                'options' => $options, // Options of mailing list (type: domain.DomainMlOptionsStruct)
                'ownerEmail' => $ownerEmail, // Owner Email (type: string)
                'replyTo' => $replyTo, // Email to reply of mailing list (type: string)
            ));

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            // unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    /**
     * Changement des options d'une mailing list
     */
    public function changeOptions($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];
        $options = $array['options'];

        try {
            $this->api->post("/email/domain/$domain/mailingList/$name/changeOptions", array(
                'options' => $options
            ));

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            // unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    /**
     * Mise à jour des infos d'une mailing list
     */
    public function put($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];
        $options = $array['options'];
        $ownerEmail = $array['ownerEmail'];
        $replyTo = $array['replyTo'];

        try {
            $this->api->post("/email/domain/$domain/mailingList/$name", array(
                'language' => 'fr', // Language of mailing list (type: domain.DomainMlLanguageEnum)
                'name' => $name, // Mailing list name (type: string)
                'options' => $options, // Options of mailing list (type: domain.DomainMlOptionsStruct)
                'ownerEmail' => $ownerEmail, // Owner Email (type: string)
                'replyTo' => $replyTo, // Email to reply of mailing list (type: string)
            ));

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            // unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Suppression d'une mailing list existante
    public function delete($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];

        try {  
            $this->api->delete("/email/domain/$domain/mailingList/$name");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            // $response = $e->getResponse();
            // $responseBodyAsString = $response->getBody()->getContents();
            // echo $responseBodyAsString;
            
            return false;
        }
    }

    /* --------------------------- */
    /*      GESTION DES ABONNES    */
    /* --------------------------- */

    public function deleteSuscriber($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];
        $email = $array['email'];

        try {  
            $this->api->delete("/email/domain/$domain/mailingList/$name/suscriber/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            
            return false;
        }
    }

    /* ------------------------------- */
    /*      GESTION DES MODERATEURS    */
    /* ------------------------------- */

    public function allModerators($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];

        if(! $name)
            return false;

        try {
            return $this->api->get("/email/domain/$domain/mailingList/$name/moderator");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function deleteModerator($array)
    {
        $domain = $array['domain'];
        $name = $array['name'];
        $email = $array['email'];

        try {  
            $this->api->delete("/email/domain/$domain/mailingList/$name/moderator/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            
            return false;
        }
    }
}