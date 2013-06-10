<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Proxy_Abstract
*/

 /**
* Abstract class for proxy instances.
*
* @category SDIS62
* @package SDIS62_Model_Proxy_Abstract
*/
abstract class SDIS62_Model_Proxy_Abstract implements SDIS62_Model_Entity_Interface
{
	/**
	* Entity object
	*
	* @var SDIS62_Model_Entity_Abstract
	*/
	private $entity = null;
	
	/**
	* Get the entity object for the current proxy
	*
	* @return SDIS62_Model_Entity_Abstract
	*/
	public function getEntity()
	{
		if($this->entity === null)
		{
			$class = 'Application_Model_Entity_'.$this->type_objet;
			$this->entity = new $class;
		}
		return $this->entity;
	}
	
	/**
	* Set the entity object for the current proxy
	*
	* @params SDIS62_Model_Entity_Abstract $entity
	* @return SDIS62_Model_Proxy_Abstract Provides fluent interface
	*/
	public function setEntity(SDIS62_Model_Entity_Abstract $entity)
	{
		$this->entity = $entity;
		return $this;
	}
	
	/**
	* Set the primary key for the current proxy
	*
	* @param int $primary
	* @return SDIS62_Model_Proxy_Abstract Provides fluent interface
	*/ 
	public function setPrimary($primary)
	{	
		$this->getEntity()->setPrimary($primary);
		return $this;
	}
	
	/**
	* Get the primary key for the current proxy
	*
	* @return int
	*/ 
	public function getPrimary()
	{	
		return $this->getEntity()->getPrimary();
	}
}
