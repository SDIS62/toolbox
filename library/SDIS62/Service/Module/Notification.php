<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Module_Notification
 */
 
 /**
 * Module for Notification service instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Bridge_Start
 */
class SDIS62_Service_Module_Notification extends Zend_Service_Abstract
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
            $url = "https://apps.sdis62.fr/modules/notification/api";
        }
        else if(APPLICATION_ENV === "development" || APPLICATION_ENV === "testing")
        {
            $url= "http://apps.sdis62.local.modules.notification/api";
        }
        else
        {
            $url = "https://apps.sdis62.fr:4000/modules/notification/api";
        }
        
        // Setup the REST client
        $this->rest_client = new Zend_Rest_Client($url);
    }

    /**
     * Envoyer une notification
     *
     * @param  $nbPage
     * @param  $nbInter
     * @param  $ville
     * @return object
     */
    public function send(array $recipientsid, $subject, $message, array $types = array("mail"))
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->send($recipientsid, $subject, $message, $types)->get(),
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
}