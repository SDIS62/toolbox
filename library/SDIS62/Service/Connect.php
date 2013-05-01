<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Connect
 */

 /**
 * Service Application
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Connect
 */
class SDIS62_Service_Connect
{
    /**
     * OAuth Endpoint
     */
    const OAUTH_BASE_URI = 'http://users.local/connect/oauth';
    
    /**
     * Base URI for all API calls
     */
    const API_BASE_URI = 'http://users.local/connect/';
    

    /**
     * @var Zend_Http_Client
     */
    protected $httpClient = null;

    /**
     * @var array
     */
    protected $cookieJar;    
    
    /**
     * Oauth Consumer
     *
     * @var Zend_Oauth_Consumer
     */
    protected $oauthConsumer = null;

    /**
     * Options passed to constructor
     *
     * @var array
     */
    protected $options = array();

    /**
     * Constructor
     *
     * @param  Zend_Oauth_Token_Access $accessToken
     */
    public function __construct(Zend_Oauth_Token_Access $accessToken, array $oauthOptions = array())
    {
        $oauthOptions['siteUrl'] = $this->getUrl() . "/oauth";
        $oauthOptions['token'] = $accessToken;
        $this->setHttpClient($accessToken->getHttpClient($oauthOptions, $this->getUrl() . "/oauth"));
    }
    
    /**
     * Get base URL for request
     *
     * @return string
     */
    public function getUrl()
    {
        if(APPLICATION_ENV === "production")
        {
            return "https://apps.sdis62.fr/connect";
        }
        else if(APPLICATION_ENV === "development" || APPLICATION_ENV === "testing")
        {
            return "http://apps.sdis62.local.connect";
        }
        else
        {
            return "https://apps.sdis62.fr:4000/connect";
        }
    }

    /**
     * Set HTTP client
     *
     * @param Zend_Http_Client $client
     * @return self
     */
    public function setHttpClient(Zend_Http_Client $client)
    {
        $this->httpClient = $client;
        $this->httpClient->setHeaders(array('Accept-Charset' => 'ISO-8859-1,utf-8'));
        return $this;
    }

    /**
     * Get the HTTP client
     *
     * Lazy loads one if none present
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->setHttpClient(new Zend_Http_Client());
        }
        return $this->httpClient;
    }

    /**
     * Retrieve account informations
     *
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws Exception\DomainException if unable to decode JSON payload
     * @return object
     */
    public function getAccount()
    {
        $this->init();
        $response = $this->get('user/account');
        return Zend_Json::decode( $response->getBody(), Zend_Json::TYPE_OBJECT);
    }
    
    /**
     * Get the user's applications
     *
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws Exception\DomainException if unable to decode JSON payload
     * @return object
     */
    public function getApplications()
    {
        $this->init();
        $response = $this->get('user/applications');
        return Zend_Json::decode( $response->getBody(), Zend_Json::TYPE_OBJECT);
    }
    
    /**
     * Get the XML Navigation for the user
     *
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws Exception\DomainException if unable to decode JSON payload
     * @return object
     */
    public function getNavigation()
    {
        $this->init();
        $response = $this->get('user/navigation');
        return Zend_Json::decode( $response->getBody(), Zend_Json::TYPE_OBJECT);
    }
    
    /**
     * Get the user's phone
     *
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws Exception\DomainException if unable to decode JSON payload
     * @return object
     */
    public function getPhoneDevice()
    {
        $this->init();
        $response = $this->get('user/phone_device');
        return Zend_Json::decode( $response->getBody(), Zend_Json::TYPE_OBJECT);
    }
    
   /**
     * Checks for an authorised state
     *
     * @return bool
     */
    public function isAuthorised()
    {
        if ($this->getHttpClient() instanceof Zend_Oauth_Client) {
            return true;
        }
        return false;
    }

    /**
     * Initialize HTTP authentication
     *
     * @return void
     * @throws Exception\DomainException if unauthorised
     */
    protected function init()
    {
        if (!$this->isAuthorised()) {
            throw new Exception('Session is unauthorised.');
        }
        $client = $this->getHttpClient();
        $client->resetParameters();
        if (null === $this->cookieJar) {
            $cookieJar = $client->getCookieJar();
            if (null === $cookieJar) {
                $cookieJar = new Zend_Http_CookieJar();
            }
            $this->cookieJar = $cookieJar;
            $this->cookieJar->reset();
        } else {
            $client->setCookieJar($this->cookieJar);
        }
    }

    /**
     * Call a remote REST web service URI
     *
     * @param  string $path The path to append to the URI
     * @param  Zend_Http_Client $client
     * @throws Zend_Http_Client_Exception
     * @return void
     */
    protected function prepare($path, Zend_Http_Client $client)
    {
        $client->setUri($this->getUrl() . $path);

        /**
         * Do this each time to ensure oauth calls do not inject new params
         */
        $client->resetParameters();
    }

    /**
     * Performs an HTTP GET request to the $path.
     *
     * @param string $path
     * @param array  $query Array of GET parameters
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
     * Performs an HTTP POST request to $path.
     *
     * @param string $path
     * @param mixed $data Raw data to send
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
     * Perform a POST or PUT
     *
     * Performs a POST or PUT request. Any data provided is set in the HTTP
     * client. String data is pushed in as raw POST data; array or object data
     * is pushed in as POST parameters.
     *
     * @param mixed $method
     * @param mixed $data
     * @return Zend_Http_Response
     */
    protected function performPost($method, $data, Zend_Http_Client $client)
    {
        if (is_string($data)) {
            $client->setRawData($data);
        } elseif (is_array($data) || is_object($data)) {
            $client->setParameterPost((array) $data);
        }
        return $client->request($method);
    }
}