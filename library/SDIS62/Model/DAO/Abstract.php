<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_DAO_Abstract
*/

 /**
* Abstract class for DAO instances.
*
* @category SDIS62
* @package SDIS62_Model_DAO_Abstract
*/
abstract class SDIS62_Model_DAO_Abstract
{
	/**
	* Mapper object
	*
	* @var Object
	*/
	protected static $mapper = null;
	
	/**
	* Type of database
	*
	* @var string
	*/
	public static $type_db = null;
	
	/**
	* Type of orm
	*
	* @var string
	*/
	public static $type_orm = null;
	
	/**
	* DAO object
	*
	* @var SDIS62_Model_DAO_Abstract
	*/
	protected static $dao = array();
	
	/**
	* Constructor of class to make a DAO with a specified type of entity
	*
	* @params string $type
	*/
	protected function __construct($type)
	{
		if(self::$type_orm === 'Doctrine')
		{
			$class_entity = 'Application_Model_Entity_'.$type;
			SDIS62_Model_Mapper_Doctrine_Abstract::makeEm($this::$infosMap, $class_entity);
		}
		if(self::$mapper === null)
		{
			self::$mapper = 'SDIS62_Model_Mapper_'.self::$type_orm.'_'.self::$type_db.'_Abstract';
		}
		$this->resetTable();
	}
	
	/**
	* Get the DAO object for a specified proxy object
	*
	* @return SDIS62_Model_DAO_Abstract
	*/
	public static function getInstance($type)
	{
		if(!isset(self::$dao[$type]))
		{
			$class_dao = 'Application_Model_DAO_'.$type;
			self::$dao[$type] = new $class_dao($type);
		}
		return self::$dao[$type];
	}
}
