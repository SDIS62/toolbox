<?php

abstract class SDIS62_Model_Mapper_Doctrine_Db_Abstract extends SDIS62_Model_Mapper_Doctrine_Abstract implements SDIS62_Model_Mapper_Interface
{
	public static function update(SDIS62_Model_Proxy_Abstract $proxy)
	{
		self::$em->persist($proxy->getEntity());
		self::$em->flush();
	}
	
	public static function find($type, $id)
	{
		$class_entity = 'Application_Model_Entity_'.$type;
		return self::$em->getRepository($class_entity)->find($id);
	}
	
	public static function delete($type, $id)
	{
		$class = 'Application_Model_Entity_'.$type;
		$query = "DELETE FROM ".$class." e WHERE e.primary = ".$id;
		self::$em->createQuery($query)->execute();
	}
}
