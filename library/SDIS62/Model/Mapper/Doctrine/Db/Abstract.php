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
	* Insert informations in database
	*
	* @params string $type
	* @params Array $array
	*/
	public static function insert($type, $array)
	{
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		if($entity->getPrimary() === null)
		{
			return;
		}
		self::$em->persist($entity);
		self::$em->flush();
	}
	
	/**
	* Update informations in database
	*
	* @params string $type
	* @params Array $array
	*/
	public static function update($type, $array)
	{
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		if($entity->getPrimary() === null)
		{
			return;
		}
		$update = self::$em->createQueryBuilder()->update($class, "e");
		foreach($array as $n => $v)
		{
			if($n !== 'primary' && $v !== null)
			{
				$update = $update->set('e.'.$n, "'$v'");
			}
		}
		$update = $update->where('e.primary = '.$array['primary'])->getQuery();
		$update->execute();
	}
	
	/**
	* Find in database an entity with a specified primary key and extract it
	*
	* @params string $type
	* @params int $id
	* @return Array
	*/
	public static function find($type, $id)
	{
		if($id === null)
		{
			return array();
		}
		$class = 'Application_Model_Entity_'.$type;
		$query = "SELECT e FROM ".$class." e WHERE e.primary = ".$id;
		$array = self::$em->createQuery($query)->getResult();
		if(empty($array[0]))
		{
			return array('primary' => $id);
		}
		return $array[0]->extract();
	}
	
	/**
	* Show if there are an entity with a specified primary key in database
	*
	* @params string $type
	* @params int $id
	* @return bool
	*/
	public static function exist($type, $id)
	{
		if($id === null)
		{
			return false;
		}
		$array = self::find($type, $id);
		return (count($array) > 1);
	}
	
	/**
	* Delete informations from database with a specified primary key
	*
	* @params string $type
	* @params int $id
	*/
	public static function delete($type, $id)
	{
		if($id === null)
		{
			return;
		}
		$class = 'Application_Model_Entity_'.$type;
		$query = "DELETE FROM ".$class." e WHERE e.primary = ".$id;
		self::$em->createQuery($query)->execute();
	}
	
	/**
	* Find in database an entity with a specified foreign key and extract it
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @return Array
	*/
	public static function findByCriteria($type, $array, $alias)
	{
		$class = 'Application_Model_Entity_'.$type;
		$query = "SELECT ".$alias[$type]." FROM ".$class." ".$alias[$type]." ";
		if(isset($array['JOIN']))
		{
			foreach($array['JOIN'] as $a)
			{
				$tables = $a['tables'];
				$colonnes = $a['colonnes'];
				$query = $query."JOIN ".$tables[1]." ".$alias[$tables[1]].
				" ON ".$alias[$tables[0]].".".$colonnes[0]." = ".$alias[$tables[1]].".".$colonnes[1]." ";
			}
			foreach($array['WHERE'] as $a)
			{
				if($a['valeur'] === null)
				{
					return array();
				}
				$query = $query."WHERE ".$alias[$a['table']].".".$a['colonne']." = ".$a['valeur'];
			}
		}
		$res = self::$em->createQuery($query)->getResult();
		if(empty($res[0]))
		{
			return array('primary' => $id);
		}
		return $res[0]->extract();
	}
	
	/**
	* Find in database several entities with a specified foreign key and extract them
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @return Array
	*/
	public static function findAllByCriteria($type, $array, $alias)
	{
		$class = 'Application_Model_Entity_'.$type;
		$query = "SELECT ".$alias[$type]." FROM ".$class." ".$alias[$type]." ";
		if(isset($array['JOIN']))
		{
			foreach($array['JOIN'] as $a)
			{
				$tables = $a['tables'];
				$colonnes = $a['colonnes'];
				$query = $query."JOIN ".$tables[1]." ".$alias[$tables[1]].
				" ON ".$alias[$tables[0]].".".$colonnes[0]." = ".$alias[$tables[1]].".".$colonnes[1]." ";
			}
			foreach($array['WHERE'] as $a)
			{
				if($a['valeur'] === null)
				{
					return array();
				}
				$query = $query."WHERE ".$alias[$a['table']].".".$a['colonne']." = ".$a['valeur'];
			}
		}
		$res = self::$em->createQuery($query)->getResult();
		if(empty($res[0]))
		{
			return array(array('primary' => $id));
		}
		$i = 0;
		foreach($res as $r)
		{
			$res[$i] = $r->extract();
			$i++;
		}
		return $res;
	}
}
