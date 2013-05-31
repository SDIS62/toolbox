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
	* Hydrate an array who contain informations to add at entity
	*
	* @params Array $array
	* @return SDIS62_Model_Entity_Abstract
	*/
	public abstract function hydrate($array);
	
	/**
	* Extract an array from entity who contain informations about the entity
	*
	* @return Array
	*/
	public abstract function extract();
}
