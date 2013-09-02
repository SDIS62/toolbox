<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Module_Feed
 */
 
 /**
 * Module for Feed service instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Module_Feed
 */
class SDIS62_Service_Module_Feed extends Zend_Service_Abstract
{
    /**
     * REST Client
     *
     * @var Zend_Rest_Client
     */    
    private $rest_client;

    /**
     * Setup the REST client
     *
     */
    public function __construct()
    {
        // Determine l'URL à utiliser
        if(APPLICATION_ENV === "production")
        {
            $url = "https://apps.sdis62.fr/modules/feed/api";
        }
        else if(APPLICATION_ENV === "development" || APPLICATION_ENV === "testing")
        {
            $url= "http://apps.sdis62.local/modules/feed/api";
        }
        else
        {
            $url = "https://apps.sdis62.fr:4000/modules/feed/api";
        }
        
        // Setup the REST client
        $this->rest_client = new Zend_Rest_Client($url);
    }
    
    public function sendMessage($message, $feed_id = "default", $type_id = "default")
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->sendMessage($message, $feed_id, $type_id)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    public function removeMessage($id_message)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->removeMessage($id_message)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    public function getMessagesByFeed($feed_id, $since = null, $nb_elements = 20, $page = 1)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getMessagesByFeed($feed_id, $since, $nb_elements, $page)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    public function createFeed($feed_name)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->createFeed($feed_name)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    public function removeFeed($feed_id)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->removeFeed($feed_id)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
}