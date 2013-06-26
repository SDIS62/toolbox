<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Bridge_Start
 */
 
 /**
 * Bridge for Start REST Service instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Service_Bridge_Start
 */
class SDIS62_Service_Bridge_Start extends Zend_Service_Abstract
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
            $url = "https://apps.sdis62.fr/bridges/start/api";
        }
        else if(APPLICATION_ENV === "development" || APPLICATION_ENV === "testing")
        {
            $url= "http://apps.sdis62.local.bridges/start/api";
        }
        else
        {
            $url = "https://apps.sdis62.fr:4000/bridges/start/api";
        }
        
        // Setup the REST client
        $this->rest_client = new Zend_Rest_Client($url);
        
        return parent::__construct();
    }

    /**
     * Récupération de la liste des interventions
     *
     * @param  $nbPage
     * @param  $nbInter
     * @param  $ville
     * @return object
     */
    public function getListeInterventions($nbPage = 1, $nbInter = 20, $ville = null)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getListeInterventions($nbPage, $nbInter, $ville)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Récupération d'une intervention
     *
     * @param  $numInter
     * @return object
     */
    public function getInter($numInter)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getInter($numInter)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Récupération de la main courante d'une intervention
     *
     * @param  $numInter
     * @return object
     */
    public function getInterMainCourante($numInter)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getInterMainCourante($numInter)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Récupération de la chronologie d'une intervention
     *
     * @param  $numInter
     * @return object
     */
    public function getInterChronologie($numInter)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getChronologie($numInter)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Récupération des centres engagés pour une intervention
     *
     * @param  $numInter
     * @return object
     */
    public function getInterCentresEngages($numInter)
    {
        // Set the request
		$response = Zend_Json::decode(
            $this->rest_client->getCentreEngage($numInter)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
}