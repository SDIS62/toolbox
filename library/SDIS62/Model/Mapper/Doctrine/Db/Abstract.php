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
	* Insert informations in database and get the primary key
	*
	* @params string $type
	* @params Array $array
	* @params Array $infos
	* @return int
	*/
	public static function insert($type, $array, $infos)
	{
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		if($entity->getPrimary() === null)
		{
			$query = "SELECT e.".$primary_rows[0]." FROM ".$class." e";
			$i = 0;
			foreach($infos['colonnes'] as $a)
			{
				if(isset($array[$a['fieldName']]) && $array[$a['fieldName']] != null)
				{
					if($i == 0)
					{
						$query = $query." WHERE e.".$a['columnName']." = '".$array[$a['fieldName']]."'";
					}
					else
					{
						$query = $query." AND e.".$a['columnName']." = '".$array[$a['fieldName']]."'";
					}
					$i++;
				}
			}
			$res = self::$em->createQuery($query)->getResult();
			echo ">>".count($res);
			if(count($res) != 1)
			{
				return null;
			}
			$entity->hydrate($res[0]);
			if($res[0] !== null)
			{
				echo ">>".$entity->getPrimary();
				self::update($type, $array, $infos);
				return $entity->getPrimary();
			}
		}
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
	*/
	public static function update($type, $array, $infos)
	{
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		if($entity->getPrimary() === null)
		{
			$query = "SELECT e.".$primary_rows[0]." FROM ".$class." e";
			$i = 0;
			foreach($infos['colonnes'] as $a)
			{
				if(isset($array[$a['fieldName']]) && $array[$a['fieldName']] != null)
				{
					if($i == 0)
					{
						$query = $query." WHERE e.".$a['columnName']." = '".$array[$a['fieldName']]."'";
					}
					else
					{
						$query = $query." AND e.".$a['columnName']." = '".$array[$a['fieldName']]."'";
					}
					$i++;
				}
			}
			$res = self::$em->createQuery($query)->getResult();
			if(count($res) != 1)
			{
				return;
			}
			$entity->hydrate($res[0]);
		}
		if($entity->getPrimary() !== null)
		{
			$update = self::$em->createQueryBuilder()->update($class, "e");
			foreach($infos['colonnes'] as $a)
			{
				if(isset($array[$a['fieldName']]) && $array[$a['fieldName']] != null)
				{
					$update = $update->set('e.'.$a['columnName'], "'".$array[$a['fieldName']]."'");
				}
			}
			$update = $update->where('e.'.$primary_rows[0].' = '.$entity->getPrimary())->getQuery();
			$update->execute();
		}
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
		$query = "SELECT e FROM ".$class." e WHERE e.".$primary_rows[0]." = ".$id;
		$array = self::$em->createQuery($query)->getResult();
		if(empty($array[0]))
		{
			return array($primary_rows[0] => $id);
		}
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
		$primary_rows = $infos['identifier'];
		$class = 'Application_Model_Entity_'.$type;
		$query = "DELETE FROM ".$class." e WHERE e.".$primary_rows[0]." = ".$id;
		echo $query;
		self::$em->createQuery($query)->execute();
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
	public static function findByCriteria($type, $array, $alias, $infos)
	{
		$primary_rows = $infos['identifier'];
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
			return array($primary_rows[0] => $id);
		}
		return $res[0]->extract();
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
	public static function findAllByCriteria($type, $array, $alias, $infos)
	{
		$primary_rows = $infos['identifier'];
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
			return array(array($primary_rows[0] => $id));
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
