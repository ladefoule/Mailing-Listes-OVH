<?php 
// session_start();
use Ovh\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiOvh
{
    private $api;
    private $domain;

    public function __construct($params)
    {
        $client = new Client([
            'timeout' => 1,
            'headers' => [
                'User-Agent' => 'api_client'
            ]
        ]);
    
        // Initiation de la connexion à l'API OVH
        $api = new Api($params['application_key'],
            $params['application_secret'],
            $params['endpoint'],
            $params['consumer_key'],
            $client
        );
        
        $this->api = $api;
        $this->domain = $params['domain'];
    }

    /**
     * Récupération de toutes les mailingLists
     */
    public function index()
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/");
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
            unset($_SESSION['mailing-list']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            $_SESSION['mailing-list']['name'] = $request['name'];
            $_SESSION['mailing-list']['ownerEmail'] = $request['ownerEmail'];
            $_SESSION['mailing-list']['replyTo'] = $request['replyTo'];
            return false;
        }
    }

    /**
     * Changement des options d'une mailing list
     */
    public function changeOptions($name, $options)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/$name/changeOptions", array(
                'options' => $options
            ));

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['mailing-list']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    /**
     * Mise à jour des infos d'une mailing list
     */
    public function update($name, $request)
    {
        try {
            $this->api->put("/email/domain/$this->domain/mailingList/$name", $request);

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['mailing-list']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            $_SESSION['mailing-list']['ownerEmail'] = $request['ownerEmail'];
            $_SESSION['mailing-list']['replyTo'] = $request['replyTo'];
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
            return false;
        }
    }

    /* --------------------------- */
    /*      GESTION DES ABONNES    */
    /* --------------------------- */

    public function subscriber($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name/subscriber");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function subscriberCreate($name, $email)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/$name/subscriber", [
                'email' => $email
            ]);

            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function subscriberDelete($name, $email)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name/subscriber/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    /* ------------------------------- */
    /*      GESTION DES MODERATEURS    */
    /* ------------------------------- */

    public function moderator($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name/moderator");
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function moderatorDelete($name, $email)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name/moderator/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }
}