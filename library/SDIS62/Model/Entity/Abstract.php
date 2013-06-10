<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Entity_Abstract
*/

 /**
* Abstract class for entity instances.
*
* @category SDIS62
* @package SDIS62_Model_Entity_Abstract
*/
abstract class SDIS62_Model_Entity_Abstract implements SDIS62_Model_Entity_Interface
{
	/**
	* Set the primary key for the current entity
	*
	* @param int $primary
	* @return SDIS62_Model_Entity_Abstract Provides fluent interface
	*/ 
	public function setPrimary($primary)
	{
		if($this->primary === null)
		{
			$this->primary = $primary;
		}
		else
		{
			throw new Exception(500, "La clé primaire de l'entité existe déjà !");
		}
		return $this;
	}
	
	/**
	* Get the primary key for the current entity
	*
	* @return int
	*/ 
	public function getPrimary()
	{
		return $this->primary;
	}
	
	/**
     * Privide __toString magic method
     *
     * @return string
     */     
    public function __toString()
    {
        return serialize($this);
    }
	
	/**
	* Hydrate an array who contain informations to add at entity
	*
	* @params Array $array
	* @return SDIS62_Model_Entity_Abstract Provides fluent interface
	*/
	public abstract function hydrate($array);
	
	/**
	* Extract an array from entity who contain informations about the entity
	*
	* @return Array
	*/
	public abstract function extract();
}
