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
     * @var Zend_Http_Client
     */
    protected $httpClient = null;
    
    /**
     * @var Zend_Rest_Client
     */
    protected $restClient = null;

    /**
     * @var array
     */
    protected $cookieJar;    

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
        $this->getRestClient()->setHttpClient($this->getHttpClient());
        $this->getRestClient()->setUri($this->getUrl() . '/api');
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
     * Set REST client
     *
     * @param Zend_Rest_Client $client
     * @return self
     */
    public function setRestClient(Zend_Rest_Client $client)
    {
        $this->restClient = $client;
        return $this;
    }

    /**
     * Get the REST client
     *
     * Lazy loads one if none present
     *
     * @return Zend_Http_Client
     */
    public function getRestClient()
    {
        if (null === $this->restClient) {
            $this->setRestClient(new Zend_Rest_Client());
        }
        return $this->restClient;
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
		return Zend_Json::decode($this->getRestClient()->account()->get(), Zend_Json::TYPE_OBJECT);
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
		return Zend_Json::decode($this->getRestClient()->applications()->get(), Zend_Json::TYPE_OBJECT);
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
		return Zend_Json::decode($this->getRestClient()->navigation()->get(), Zend_Json::TYPE_ARRAY);
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
		return Zend_Json::decode($this->getRestClient()->phone_device()->get(), Zend_Json::TYPE_OBJECT);
    }
}