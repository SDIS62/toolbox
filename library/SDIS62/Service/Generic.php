<?php

class SDIS62_Service_Generic
{
    /**
     * URL de base de l'API
     *
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $cookieJar;

    /**
     * @var Zend_Http_Client
     */
    protected $httpClient = null;

    /**
     * Options passées au constructeur
     *
     * @var array
     */
    protected $options = array();

    /**
     * Constructeur
     *
     * @param string $uri URL de base de l'API
     * @param  null|array|Zend_Config $options
     * @param  null|Zend_Http_Client $httpClient
     */
    public function __construct($uri, $options = null, Zend_Http_Client $httpClient = null)
    {
        $this->setUri($uri);
        
        if ($options instanceof Zend_Config)
        {
            $options = $options->toArray();
        }
        
        if (!is_array($options))
        {
            $options = array();
        }

        $this->options = $options;

        $httpClientOptions = array();
        
        if(isset($options['httpClientOptions']))
        {
            $httpClientOptions = $options['httpClientOptions'];
        }
        elseif(isset($options['http_client_options']))
        {
            $httpClientOptions = $options['http_client_options'];
        }

        if(isset($options['httpClient']) && null === $httpClient)
        {
            $httpClient = $options['httpClient'];
        }
        elseif(isset($options['http_client']) && null === $httpClient)
        {
            $httpClient = $options['http_client'];
        }
        
        if($httpClient instanceof Zend_Http_Client)
        {
            $this->httpClient = $httpClient;
        }
        else
        {
            $this->setHttpClient(new Zend_Http_Client(null, $httpClientOptions));
        }
    }
    
    /**
     * Récupération de l'Uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Définition de l'Uri
     *
     * @param string $uri
     * @throws Zend_Service_Exception si l'uri est invalide
     */
    public function setUri($uri)
    {
        if(!Zend_Uri::check($uri))
        {
            throw new Zend_Service_Exception('URL invalide');
        }

        $this->uri = $uri;
    }

    /**
     * Définition du client HTTP
     *
     * @param Zend_Http_Client $client
     * @return self
     */
    public function setHttpClient(Zend_Http_Client $client)
    {
        $this->httpClient = $client;
        $this->httpClient->setHeaders(array('Accept-Charset' => 'utf-8'));
        return $this;
    }

    /**
     * Récupération du client HTTP
     *
     * Lazy loads si il n'existe pas
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient)
        {
            $this->setHttpClient(new Zend_Http_Client());
        }
        
        return $this->httpClient;
    }

    /**
     * Request
     *
     * @param string $path
     * @param array $query Tableau des paramètres GET
     * @param string $method GET ou POST
     * @throws Zend_Exception Si la méthode n'est pas bonne
     * @throws Zend_Http_Client_Exception Si la requête HTTP a failli
     * @throws Exception\DomainException Si le JSON n'est pas correct
     * @return Zend_Service_Twitter_Response
     */
    public function request($path, $query = array(), $method = 'GET')
    {
        switch(strtolower($method))
        {
            case 'get':
                $response = $this->get($path, $query);
                break;
            case 'post':
                $response = $this->post($path, $query);
                break;
            default:
                throw new Zend_Exception('La méthode n\'est pas connue.', 500);
        }
        
        return new SDIS62_Service_Generic_Response($response);
    }

    /**
     * Appel un service REST
     *
     * @param  string $path La destination a mettre juste après l'uri de l'API
     * @param  Zend_Http_Client $client
     * @throws Zend_Http_Client_Exception
     * @return void
     */
    protected function prepare($path, Zend_Http_Client $client)
    {
        $client->setUri($this->uri . '/' . $path);
        $client->resetParameters();
    }

    /**
     * Réalise une requête GET
     *
     * @param string $path
     * @param array  $query
     * @throws Zend_Http_Client_Exception
     * @return Zend_Http_Response
     */
    protected function get($path, array $query = array())
    {
        $client = $this->getHttpClient();
        $this->prepare($path, $client);
        $client->setParameterGet($query);
        $response = $client->request(Zend_Http_Client::GET);
        return $response;
    }

    /**
     * Réalise une requête POST
     *
     * @param string $path
     * @param mixed $data
     * @throws Zend_Http_Client_Exception
     * @return Zend_Http_Response
     */
    protected function post($path, $data = null)
    {
        $client = $this->getHttpClient();
        $this->prepare($path, $client);
        $response = $this->performPost(Zend_Http_Client::POST, $data, $client);
        return $response;
    }

    /**
     * Réalise un POST ou un PUT
     *
     * Effectue une requête POST ou PUT. Toutes les données fournies sont définis dans le client HTTP.
     * Les données de type string sont poussées en tant que données brutes POST;
     * Les tableaux ou objets sont poussés en tant que paramètres POST.
     *
     * @param mixed $method
     * @param mixed $data
     * @return Zend_Http_Response
     */
    protected function performPost($method, $data, Zend_Http_Client $client)
    {
        if (is_string($data))
        {
            $client->setRawData($data);
        }
        elseif (is_array($data) || is_object($data))
        {
            $client->setParameterPost((array) $data);
        }
        
        return $client->request($method);
    }
}