<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Abstract
*/

 /**
* Abstract class for mapper class.
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Abstract
*/
abstract class SDIS62_Model_Mapper_Doctrine_Abstract
{
	/**
	* Entity manager object
	*
	* @var Doctrine\ORM\EntityManager
	*/
	public static $em;
	
	/**
	* Initialise the entity manager
	*
	* @params Array $connOpt
	* @params Doctrine\ORM\Configuration $connDoc
	*/
	public static function createEntityManager($connOpt, $connDoc)
	{
		SDIS62_Model_DAO_Abstract::$type_db = $connOpt['type_db'];
		SDIS62_Model_DAO_Abstract::$type_orm = $connOpt['type_orm'];
		self::$em = Doctrine\ORM\EntityManager::create($connOpt, $connDoc);
	}
	
	/**
	* Make the entity manager for a type of entity
	*
	* @params Array $infos
	* @params string $class_entity
	*/
	public static function makeEm($infos, $class_entity) // Par la suite, essayer de passer par Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder
	{
		$factory = self::$em->getMetadataFactory();
		$metadata = new Doctrine\ORM\Mapping\ClassMetadata($class_entity);
		$factory->setMetadataFor($class_entity, $metadata);
		
		$metadata = self::$em->getClassMetadata($infos['classe']);
		$metadata->setTableName($infos['table']);
		$metadata->setIdentifier($infos['identifier']);
		foreach($infos['colonnes'] as $col)
		{
			if(!isset($infos['mappingType']))
			{
				$metadata->addInheritedFieldMapping($col);
			}
			else
			{
				$fc = 'map'.$infos['mappingType'];
				$metadata->$fc($col);
			}
			$metadata->reflFields[$col['fieldName']] = new ReflectionProperty(new $class_entity, $col['fieldName']);
		}
		if($infos['id_auto'])
		{
			$metadata->setIdGeneratorType(Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_IDENTITY);
			$metadata->setIdGenerator(new Doctrine\ORM\Id\IdentityGenerator);
		}
		else
		{
			$metadata->setIdGeneratorType(Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_NONE);
			$metadata->setIdGenerator(new Doctrine\ORM\Id\AssignedGenerator);
		}
		if(isset($infos['join']))
		{
			$metadata->addInheritedAssociationMapping($infos['join']);
		}
	}
}
