<?php 
// session_start();
use Ovh\Api;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiOvh
{
    private $api;

    public function __construct($array)
    {
        $client = new Client([
            // 'http_errors' => false, // Todo : Essayer de trouver une solution pour récupérer le code erreur en utilisant la classe Api d'OVH
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

    public function get($array)
    {
        $domain = $array['domain'];
        $account = $array['account'];

        if(! $account)
            return false;

        // On checke si le compte possède déjà un répondeur ou non
        // Si une erreur est générée alors on suppose que c'est non
        try {
            $result = $this->api->get("/email/domain/$domain/responder/$account/");
            $result['copy'] = $result['copy'];
            $result['content'] = $result['content'];
            $from = new Carbon($result['from']);
            $result['from'] = $from->format('Y-m-d');
            $result['from_locale'] = $from->translatedFormat('d M Y');
            $to = new Carbon($result['to']);
            $result['to'] = $to->format('Y-m-d');
            $result['to_locale'] = $to->translatedFormat('d M Y');
            return $result;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            return false;
        }
    }

    public function post($array)
    {
        $domain = $array['domain'];
        $account = $array['account'];

        $copy = isset($_POST['copy']) ? true : false;
        $content = htmlspecialchars($_POST['content']);
        $from = new Carbon($_POST['from']);
        $to = new Carbon($_POST['to']);

        try {
            $this->api->post("/email/domain/$domain/responder/", array(
                'account' => $account, // Account of domain (type: string)
                'content' => $content, // Content of responder (type: string)
                'copy' => $copy, // If false, emails will be dropped. If true and copyTo field is empty, emails will be delivered to your mailbox. If true and copyTo is set with an address, emails will be delivered to this address (type: boolean)
                'from' => $from, // Date of start responder (type: datetime)
                'to' => $to, // Date of end responder (type: datetime)
            ));

            // On supprime les données du formulaire potentiellement sauvegardées dans la SESSION
            unset($_SESSION['form']); 
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());

            // On sauvegarde les données en SESSION au cas ou l'utilisateur revient sur le formulaire
            $_SESSION['form']['copy'] = $copy;
            $_SESSION['form']['from'] = $from->format('Y-m-d');
            $_SESSION['form']['to'] = $to->format('Y-m-d');
            $_SESSION['form']['content'] = $content;

            return false;
        }
    }

    public function delete($array)
    {
        $domain = $array['domain'];
        $account = $array['account'];

        try {  
            $this->api->delete("/email/domain/$domain/responder/$account/");
            return true;
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody()->getContents());
            // $response = $e->getResponse();
            // $responseBodyAsString = $response->getBody()->getContents();
            // echo $responseBodyAsString;
            
            return false;
        }
    }
}