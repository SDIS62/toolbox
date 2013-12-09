<?php

class SDIS62_Service_Generic_Response
{
    /**
     * @var Zend_Http_Response
     */
    protected $httpResponse;

    /**
     * @var array|stdClass
     */
    protected $jsonBody;

    /**
     * @var string
     */
    protected $rawBody;

    /**
     * Constructeur
     *
     * Affecte la réponse HTTP à une propriété, ainsi que le body.
     * Il tente ensuite de déchiffrer le corps en tant que JSON.
     *
     * @param  Zend_Http_Response $httpResponse
     * @throws SDIS62_Service_Generic_Exception
     */
    public function __construct(Zend_Http_Response $httpResponse)
    {
        $this->httpResponse = $httpResponse;
        $this->rawBody = $httpResponse->getBody();
        
        try
        {
            $jsonBody = Zend_Json::decode($this->rawBody, Zend_Json::TYPE_OBJECT);
            $this->jsonBody = $jsonBody;
        }
        catch(Zend_Json_Exception $e)
        {
            throw new SDIS62_Service_Generic_Exception('impossible de décoder la réponse: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Récupération des propriétés de la réponse JSON
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (null === $this->getResponse())
        {
            return null;
        }
        
        if(!isset($this->getResponse()->{$name}))
        {
            return null;
        }
        
        return $this->getResponse()->{$name};
    }

    /**
     * La requête s'est bien déroulée ?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->jsonBody->status == 'success';
    }

    /**
     * Y'a t'il eu une erreur ?
     *
     * @return bool
     */
    public function isError()
    {
        return $this->jsonBody->status == 'failed';
    }

    /**
     * Récupération des erreurs
     *
     * Si la réponse s'est bien déroulée, alors un tableau vide est renvoyé
     *
     * @return array
     * @throws Exception\DomainException
     */
    public function getErrors()
    {
        if (!$this->isError()) 
        {
            return array();
        }
        
        if (null === $this->jsonBody || !isset($this->jsonBody->response))
        {
            throw new Zend_Service_Twitter_Exception('Soit aucune réponse JSON n\'a été reçue, soit le reçu JSON est malformé, dans tous les cas on ne peut afficher un message d\'erreur');
        }
        
        return $this->getResponse();
    }

    /**
     * Récupération des données brut de la réponse
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawBody;
    }

    /**
     * Retourne uniquement le resultat json décodée du body
     *
     * @return array|stdClass
     */
    public function getResponse()
    {
        return $this->jsonBody->response;
    }
}
