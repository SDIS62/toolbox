<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_Controller_Action_Helper_GetConfigurationValues
 */
 
 /**
 * Class for GetConfigurationValues Action Helper instance.
 *
 * @category   SDIS62
 * @package    SDIS62_Controller_Action_Helper_GetConfigurationValues
 */
class SDIS62_Controller_Action_Helper_GetConfigurationValues extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Get the configuration values based on the key param
     *
     * @param string $config_file Path to the configuration file
     * @param string $configuration_parameters_key optional key to load in configuration file
     * @return array
     */
    public function direct($config_file, $configuration_parameters_key = null)
    {
        // check if config_file exists
        if(!file_exists($config_file))
        {
            throw new Zend_Exception("Configuration file does not exists (" . $config_file . ")");
        }
        
        // retrieve the configuration from config_file
        $config = new Zend_Config_Ini($config_file, APPLICATION_ENV);
        
        // get the key based configuration values
        $key_configuration_values = $configuration_parameters_key == null ? $secret_config : $secret_config->$configuration_parameters_key;
        
        // Check if key_configuration_values is an array
        if(!is_array($key_configuration_values->toArray()))
        {
            throw new Zend_Exception("Configuration parameters is not an array.");
        }
        
        return $key_configuration_values->toArray();
    }
}