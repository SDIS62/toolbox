<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Service_CommandCenter
 */
 
 /**
 * Class for CommandCenter REST Service instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Service_CommandCenter
 */
class SDIS62_Service_CommandCenter extends Zend_Service_Abstract
{
    /**
     * Get base URL for REST request
     *
     * @return string
     */
    public function getUrl()
    {
        if(APPLICATION_ENV === "production")
        {
            return "https://apps.sdis62.fr/admin/api";
        }
        else if(APPLICATION_ENV === "development" || APPLICATION_ENV === "testing")
        {
            return "http://apps.sdis62.local.admin/api";
        }
        else
        {
            return "https://apps.sdis62.fr:4000/admin/api";
        }
    }

    /**
     * Get the user account
     *
     * @param  $id_user
     * @return object
     */
    public function getUserAccount($id_user)
    {
        // Set the URL
        $rest_user = new Zend_Rest_Client($this->getUrl() . '/user');
        
        // Set the request
		$response = Zend_Json::decode(
            $rest_user->getAccount($id_user)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Verify the user's credentials
     *
     * @param  $email
     * @param  $password
     * @return object
     */    
    public function verifyUserCredentials($email, $password)
    {
        // Set the URL
        $rest_user = new Zend_Rest_Client($this->getUrl() . '/user');
        
        // Set the request
		$response = Zend_Json::decode(
            $rest_user->verifyCredentials($email, $password)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }

    /**
     * Check if the user is authorized to access the app
     *
     * @param  $id_user
     * @param  $id_application
     * @return object
     */
    public function isUserAuthorized($id_user, $id_application)
    {
        // Set the URL
        $rest_user = new Zend_Rest_Client($this->getUrl() . '/user');
        
        // Set the request
		$response = Zend_Json::decode(
            $rest_user->isAuthorized($id_user, $id_application)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
    
    /**
     * Get the application
     *
     * @param  $id_application
     * @return object
     */
    public function getApplication($id_application)
    {
        // Set the URL
        $rest_user = new Zend_Rest_Client($this->getUrl() . '/application');
        
        // Set the request
		$response = Zend_Json::decode(
            $rest_user->getApplication($id_application)->get(), 
            Zend_Json::TYPE_OBJECT
        );
		
        // return the results
		return $response;
    }
}