<?php 
// session_start();
use Ovh\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiOvh
{
    private $api;
    private $domain;

    public function __construct($global)
    {
        $client = new Client([
            'timeout' => 1,
            'headers' => [
                'User-Agent' => 'api_client'
            ]
        ]);
    
        // Initiation de la connexion à l'API OVH
        $api = new Api($global['application_key'],
            $global['application_secret'],
            $global['endpoint'],
            $global['consumer_key'],
            $client
        );
        
        $this->api = $api;
        $this->domain = $global['domain'];
    }

    /**
     * Récupération de toutes les mailingLists
     */
    public function index($global)
    {
        $domain = $global['domain'];

        try {
            return $this->api->get("/email/domain/$domain/mailingList/");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Récupération des infos de la mailing list
    public function show($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Création d'une mailing list
    public function create($request)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/", $request);

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            $_SESSION['form']['name'] = $request['name'];
            $_SESSION['form']['ownerEmail'] = $request['ownerEmail'];
            $_SESSION['form']['replyTo'] = $request['replyTo'];
            return false;
        }
    }

    /**
     * Changement des options d'une mailing list
     */
    // public function changeOptions($global)
    // {
    //     $domain = $global['domain'];
    //     $name = $global['name'];
    //     $options = $global['options'];

    //     try {
    //         $this->api->post("/email/domain/$domain/mailingList/$name/changeOptions", array(
    //             'options' => $options
    //         ));

    //         // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
    //         // unset($_SESSION['form']); 
    //         return true;
    //     } catch (RequestException $e) {
    //         error_log($e->getResponse()->getBody()->getContents());
    //         return false;
    //     }
    // }

    /**
     * Mise à jour des infos d'une mailing list
     */
    public function update($name, $request)
    {
        try {
            $this->api->put("/email/domain/$this->domain/mailingList/$name", $request);

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    // Suppression d'une mailing list existante
    public function delete($name)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name");
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

    public function suscriberDelete($global)
    {
        $domain = $global['domain'];
        $name = $global['name'];
        $email = $global['email'];

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

    public function moderator($global)
    {
        $domain = $global['domain'];
        $name = $global['name'];

        if(! $name)
            return false;

        try {
            return $this->api->get("/email/domain/$domain/mailingList/$name/moderator");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function moderatorDelete($global)
    {
        $domain = $global['domain'];
        $name = $global['name'];
        $email = $global['email'];

        try {  
            $this->api->delete("/email/domain/$domain/mailingList/$name/moderator/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            
            return false;
        }
    }
}