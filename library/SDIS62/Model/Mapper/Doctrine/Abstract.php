<?php

abstract class SDIS62_Model_Mapper_Doctrine_Abstract
{
	public static $em;
	
	public static function createEntityManager($type_db, $type_orm, $connOpt, $connDoc)
	{
		SDIS62_Model_DAO_Abstract::$type_db = $type_db;
		SDIS62_Model_DAO_Abstract::$type_orm = $type_orm;
		self::$em = Doctrine\ORM\EntityManager::create($connOpt, $connDoc);
	}
	
	public static function makeEm(SDIS62_Model_DAO_Abstract $dao, $class_entity)
	{
		$factory = self::$em->getMetadataFactory();
		$metadata = new Doctrine\ORM\Mapping\ClassMetadata($class_entity);
		$factory->setMetadataFor($class_entity, $metadata);
		
		$metadata = self::$em->getClassMetadata($dao::$infosMap['classe']);
		$metadata->setTableName($dao::$infosMap['table']);
		$metadata->setIdentifier($dao::$infosMap['identifier']);
		foreach($dao::$infosMap['colonnes'] as $col)
		{
			if(!isset($dao::$infosMap['mappingType']))
				$metadata->addInheritedFieldMapping($col);
			else
			{
				$fc = 'map'.$dao::$infosMap['mappingType'];
				$metadata->$fc($col);
			}
			$metadata->reflFields[$col['fieldName']] = new ReflectionProperty(new $class_entity, $col['fieldName']);
		}
		$metadata->setIdGenerator(new Doctrine\ORM\Id\AssignedGenerator);
	}
}
