<?php

class SDIS62_Controller_Action_OauthConsumer extends Zend_Controller_Action
{
    /**
     * Zend_Oauth_Consumer
     *
     * @var Zend_Oauth_Consumer|null
     */
	private $consumer;
    
    /**
     * Le chemin vers le fichier de configuration
     * Par défaut, le fichier de configuation doit se trouver dans application/configs/secret.ini
     *
     * @var string|null
     */
	protected $config_path;

    /**
     * Initialisation du controleur
     */
    public function init()
    {
        // Récupération du fichier de config
        $config = new Zend_Config_Ini(
            $this->config_path == null ? APPLICATION_PATH . DS . "configs" . DS . "secret.ini" : $config_path,
            APPLICATION_ENV
        );

        // Initialisation du consumer
		$this->setConsumer(new Zend_Oauth_Consumer(array(
            'callbackUrl' => $config->oauth->callback,
            'siteUrl' => $config->oauth->siteurl,
			'consumerKey' => $config->oauth->consumerkey,
			'consumerSecret' => $config->oauth->consumersecret
        )));
    }
    
    /**
     * Récupération du consumer oauth
     *
     * @return Zend_Oauth_Consumer|null
     */
    protected function getConsumer()
    {
        return $this->consumer;
    }
    
    /**
     * Définition du consumer oauth
     *
     * @param Zend_Oauth_Consumer $consumer
     */
    private function setConsumer(Zend_Oauth_Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * Dispatch pour récupérer un request_token
     */
    public function indexAction()
    {
        // Récupération du request_token
        $_SESSION['request_token'] = serialize($this->getConsumer()->getRequestToken());

        // Redirection vers la page d'authentification
        $this->getConsumer()->redirect();
    }

    /**
     * Dispatch pour récupérer un access_token
     */
    public function callbackAction()
    {
        $request_token = unserialize($_SESSION['request_token']);
        $_SESSION['request_token'] = null;
        
        // On test si le request token correspond avec celui reçu à la première étape
        if($this->getParam("oauth_token") != $request_token->oauth_token)
        {
            throw new Exception("Les request_token ne correspondent pas ! (" . $this->getParam("oauth_token") . 
                " != " . $request_token->oauth_token . ")", 500);
        }
        
        // Récupération de l'access_token
        $access_token = $this->getConsumer()->getAccessToken($_GET, $request_token);
        
        // Stockage de l'access_token
        $_SESSION['access_token'] = serialize($access_token);
    }
}