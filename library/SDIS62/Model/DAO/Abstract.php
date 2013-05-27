<?php

abstract class SDIS62_Model_DAO_Abstract
{
	protected static $mapper = null;
	public static $type_db = null;
	public static $type_orm = null;
	
	public function __construct($type = null)
	{
		if(self::$type_orm === 'Doctrine')
		{
			$class_entity = 'Application_Model_Entity_'.$type;
			SDIS62_Model_Mapper_Doctrine_Abstract::makeEm($this, $class_entity);
		}
		if(self::$mapper === null)
			self::$mapper = 'SDIS62_Model_Mapper_'.self::$type_orm.'_'.self::$type_db.'_Abstract';
	}
}
