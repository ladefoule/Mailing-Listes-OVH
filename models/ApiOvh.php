<?php 
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
     * Récupération de toutes les mailingLists du compte OVH
     */
    public function index()
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/");
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Les mailingLists pour lesquelles le $account est modérateur (ou propriétaire)
     */
    public function indexAccount($account)
    {
        $mailingLists = [];
        $lists = $this->index();
        $email = $account .'@'. $this->domain;

        if($lists !== false)
            foreach ($lists as $mailingList) {
                $moderators = $this->moderator($mailingList);

                if(in_array($email, $moderators))
                    $mailingLists[] = $mailingList;
            }

        return $mailingLists;
    }

    /**
     * Récupération des infos de la mailing list
     */
    public function show($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name");
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Création d'une mailing list
     */
    public function create($request)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/", $request);
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
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
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Mise à jour des infos (hors options) d'une mailing list
     */
    public function update($name, $request)
    {
        try {
            $this->api->put("/email/domain/$this->domain/mailingList/$name", $request);
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Suppression d'une mailing list existante
     */
    public function delete($name)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /* --------------------------- */
    /*      GESTION DES ABONNES    */
    /* --------------------------- */

    /**
     * Liste des abonnés
     */
    public function subscriber($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name/subscriber");
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Ajout d'un abonné
     */
    public function subscriberCreate($name, $email)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/$name/subscriber", [
                'email' => $email
            ]);

            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Suppression d'un abonné
     */
    public function subscriberDelete($name, $email)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name/subscriber/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /* ------------------------------- */
    /*      GESTION DES MODERATEURS    */
    /* ------------------------------- */

    /**
     * Liste des modérateurs
     */
    public function moderator($name)
    {
        try {
            return $this->api->get("/email/domain/$this->domain/mailingList/$name/moderator");
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }
    
    /**
     * Vérification si un email est modérateur ou non de la mailing list
     */
    public function isModerator($name, $email)
    {
        $moderators = $this->moderator($name);
        return in_array($email, $moderators);
    }

    /**
     * Ajout d'un modérateur
     */
    public function moderatorCreate($name, $email)
    {
        try {
            $this->api->post("/email/domain/$this->domain/mailingList/$name/moderator", [
                'email' => $email
            ]);

            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }

    /**
     * Suppression d'un modérateur
     */
    public function moderatorDelete($name, $email)
    {
        try {  
            $this->api->delete("/email/domain/$this->domain/mailingList/$name/moderator/$email");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse() ? $e->getResponse()->getBody()->getContents() : '');
            return false;
        }
    }
}