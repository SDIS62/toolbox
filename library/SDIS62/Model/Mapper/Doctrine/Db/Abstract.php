<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Db_Abstract
*/

 /**
* Abstract class for mapper class.
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Db_Abstract
*/
abstract class SDIS62_Model_Mapper_Doctrine_Db_Abstract extends SDIS62_Model_Mapper_Doctrine_Abstract implements SDIS62_Model_Mapper_Interface
{
	/**
	* Insert informations in database and get the primary key (2)
	*
	* @params string $type
	* @params Array $array
	* @params Array $infos
	* @return int
	*/
	public static function insert($type, $array, $infos)
	{
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		
		/*
		foreach($array as $n => $v)
		{
			if(is_array($v))
			{
				$array[$n] = new \Doctrine\Common\Collections\ArrayCollection($v);
			}
		}
		*/
		
		$entity->hydrate($array);
		self::$em->persist($entity);
		self::$em->flush();
		return $entity->getPrimary();
	}
	
	/**
	* Update informations in database
	*
	* @params string $type
	* @params Array $array
	* @params Array $infos
	* @return int
	*/
	public static function update($type, $array, $infos)
	{
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		
		/*
		for($i=0; $i<count($array); $i++)
		{
			if(is_array($array[$i]))
			{
				$array[$i] = new \Doctrine\Common\Collections\ArrayCollection($array[$i]);
			}
		}
		*/
		
		$entity->hydrate($array);
		$update = self::$em->createQueryBuilder()->update($class, "e");
		foreach($infos['colonnes'] as $a)
		{
			if(isset($array[$a['fieldName']]) && $array[$a['fieldName']] != null)
			{
				$update = $update->set('e.'.$a['fieldName'], "'".$array[$a['fieldName']]."'");
			}
		}
		$update = $update->where('e.'.$primary_rows[0].' = '.$entity->getPrimary())->getQuery();
		$update->execute();
		return $entity->getPrimary();
	}
	
	/**
	* Find in database an entity with a specified primary key and extract it
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	* @return Array
	*/
	public static function find($type, $id, $infos)
	{
		if($id === null)
		{
			return array();
		}
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$array = self::$em->getRepository($class)->find($id);
		if(empty($array))
		{
			return array($primary_rows[0] => $id);
		}
		
		/*
		$array = $array[0];
		for($i=0; $i<count($array[0]); $i++)
		{
			if($array[$i] instanceof \Doctrine\Common\Collections\ArrayCollection)
			{
				$array[$i] = $array[$i]->toArray();
			}
		}
		*/
		
		return $array[0]->extract();
	}
	
	/**
	* Show if there are an entity with a specified primary key in database
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	* @return bool
	*/
	public static function exist($type, $id, $infos)
	{
		if($id === null)
		{
			return false;
		}
		$array = self::find($type, $id, $infos);
		return (count($array) > 1);
	}
	
	/**
	* Delete informations from database with a specified primary key
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	*/
	public static function delete($type, $id, $infos)
	{
		if($id === null)
		{
			return;
		}
		$class = 'Application_Model_Entity_'.$type;
		$entity = self::$em->getRepository($class)->find($id);
		self::$em->remove($entity);
		self::$em->flush();
	}
	
	/**
	* Find in database an entity with a specified foreign key and extract it
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @params Array $infos
	* @return Array
	*/
	public static function findByCriteria($type, $condition, $infos)
	{
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$array = self::$em->getRepository($class)->findBy($condition);
		if(empty($array))
		{
			return array($primary_rows[0] => $id);
		}
		return $array[0]->extract();
	}
	
	/**
	* Find in database several entities with a specified foreign key and extract them
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @params Array $infos
	* @return Array
	*/
	public static function findAllByCriteria($type, $condition, $infos)
	{
		$class = 'Application_Model_Entity_'.$type;
		$array = self::$em->getRepository($class)->findBy($condition);
		if(empty($array))
		{
			return array(array());
		}
		$taille = $count($array);
		for($i=0; $i<count($array); $i++)
		{
			$array[$i] = $array[$i]->extract();
		}
		return $array;
	}
}
