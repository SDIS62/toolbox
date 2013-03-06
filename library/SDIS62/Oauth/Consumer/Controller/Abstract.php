<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Oauth
 */
 
 /**
 * Class for Oauth Consumer Action Controller instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Oauth
 */
abstract class SDIS62_Oauth_Consumer_Controller_Abstract extends Zend_Controller_Action
{
    /**
     * Default Zend_Oauth_Consumer object.
     *
     * @var Zend_Oauth_Consumer
     */
	private $consumer = null;
    
    /**
     * The configuration file.
     *
     * @var string
     */
	protected $config_file = null;
    
    /**
     * The configuration parameters key.
     *
     * @var string
     */
	protected $configuration_parameters_key = null;
    
    /**
     * Array which represents the redirection URL after obtain an access token.
     *
     * @var array
     */
	protected $redirection_url = array("index", "index");

    /**
     * Initialize object
     *
     * @return void
     */
    public function init()
    {
        if($this->config_file == null)
        {
            $this->config_file = APPLICATION_PATH . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "secret.ini";
        }
    
        // check if config_file exists
        if(!file_exists($this->config_file))
        {
            throw new Zend_Exception("Configuration file does not exists (" . $this->config_file . ")");
        }
        
        // retrieve the configuration from config_file
        $secret_config = new Zend_Config_Ini($this->config_file, APPLICATION_ENV);
        
        // Check if config is an array
        $configuration_parameters_key = $this->configuration_parameters_key;
        if(!is_array($this->secret_config->$configuration_parameters_key->toArray()))
        {
            throw new Zend_Exception("Configuration parameters is not an array. '" . $configuration_parameters_key . "' key used.");
        }
        
        // Initialize consumer object with config array
		$this->consumer = new Zend_Oauth_Consumer($this->secret_config->$configuration_parameters_key->toArray());
    }

    /**
     * Dispatch to retrieve a request_token
     *
     * @return void
     */
    public function indexAction()
    {
        // get my session
		$session = new Zend_Session_Namespace();
        
        // if we have not request_token
        if(!$session->ACCESS_TOKEN)
        {
            // Get request_token
            $token = $this->consumer->getRequestToken();

            // we store the request token
            $session->REQUEST_TOKEN = $token;

            // redirect the user to user module
            $this->consumer->redirect();
            return;
        }
        else
        {
            $this->_helper->redirector->gotoUrl($this->redirection_url);
        }
    }

    /**
     * Dispatch to retrieve an access_token
     *
     * @return void
     */
    public function callbackAction()
    {
		// get my session
		$session = new Zend_Session_Namespace();
        
        // if we have not access_token
        if(!$session->ACCESS_TOKEN)
        {
            // Test if the request token matches with the request token received in step 1
            if($this->getParam("oauth_token") == $session->REQUEST_TOKEN->oauth_token)
            {
                // Get access token
                $token = $this->consumer->getAccessToken(
                    $_GET,
                    $session->REQUEST_TOKEN
                );
                
                // Serialize and stock access token in session
                $session->ACCESS_TOKEN = serialize($token);
     
                // Now that we have an Access Token, we can discard the Request Token
                $session->REQUEST_TOKEN = null;
                
                // redirection
                $this->_helper->redirector->gotoUrl($this->redirection_url);
            }
            else
            {
                throw new Zend_Oauth_Exception("The token doesn't match the request token.");
            }
        }
    }
}