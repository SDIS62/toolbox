<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_User
 */
 
 /**
 * Class for User REST Service instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Service_User
 */
class SDIS62_Service_User extends Zend_Service_Abstract
{

    /**
     * Return the authentificated account informations (oauth protected ressource)
     *
     * @return object
     */
    public function getAccountInformations()
    {
        // Set the URL
        $this->getHttpClient()->setUri("http://users.local/api/users/show");
        
        // Set the request
		$request = $this->getHttpClient()->request(Zend_Http_Client::GET);
		$response = Zend_Json::decode($request->getBody(), Zend_Json::TYPE_OBJECT);
		
        // return the results
		return $response->results;
    }

}