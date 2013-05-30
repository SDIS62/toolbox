<?php

abstract class SDIS62_Model_Mapper_Doctrine_Db_Abstract extends SDIS62_Model_Mapper_Doctrine_Abstract implements SDIS62_Model_Mapper_Interface
{
	public static function insert($type, $array)
	{
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		self::$em->persist($entity);
		self::$em->flush();
	}
	
	public static function update($type, $array)
	{
		$class = 'Application_Model_Entity_'.$type;
		$entity = new $class;
		$entity->hydrate($array);
		$update = self::$em->createQueryBuilder()->update($class, "e");
		foreach($array as $n => $v)
		{
			if($n !== 'primary' && $v !== null)
				$update = $update->set('e.'.$n, "'$v'");
		}
		$update = $update->where('e.primary = '.$array['primary'])
		->getQuery();
		$update->execute();
	}
	
	public static function fetch($type, $id)
	{
		$class = 'Application_Model_Entity_'.$type;
		$query = "SELECT e FROM ".$class." e WHERE e.primary = ".$id;
		$array = self::$em->createQuery($query)->getResult();
		if(empty($array[0]))
			return array('primary' => $id);
		return $array[0]->extract();
	}
	
	public static function find($type, $id)
	{
		$array = self::fetch($type, $id);
		return (count($array) > 1);
	}
	
	public static function delete($type, $id)
	{
		$class = 'Application_Model_Entity_'.$type;
		$query = "DELETE FROM ".$class." e WHERE e.primary = ".$id;
		self::$em->createQuery($query)->execute();
	}
}
