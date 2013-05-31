<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Entity_Interface
*/

 /**
* Interface for entity instances.
*
* @category SDIS62
* @package SDIS62_Model_Entity_Interface
*/
interface SDIS62_Model_Entity_Interface
{
	/**
	* Set the primary key for the current object
	*
	* @param int $primary
	*/ 
	public function setPrimary($primary);

	/**
	* Get the primary key for the current object
	*
	* @return int
	*/ 
	public function getPrimary();
}
