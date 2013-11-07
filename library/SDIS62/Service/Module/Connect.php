<?php

class SDIS62_Service_Module_Connect extends SDIS62_Service_Module_Generic
{
    /**
     * Constructeur
     *
     * @param  string $uri
     * @param  Zend_Oauth_Token_Access $accessToken
     * @param  array $oauthOptions Optionnel
     */
    public function __construct($uri, Zend_Oauth_Token_Access $accessToken, array $oauthOptions = array())
    {
        parent::__construct($uri);
        $httpClient = $accessToken->getHttpClient($oauthOptions);
        $this->getRestClient()->setHttpClient($httpClient);
    }
}