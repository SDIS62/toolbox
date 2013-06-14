<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Interface
*/

 /**
* Interface for mapper class.
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Interface
*/
interface SDIS62_Model_Mapper_Interface
{
	/**
	* Insert informations in database
	*
	* @params string $type
	* @params Array $array
	*/
	public static function insert($type, $array);
	
	/**
	* Update informations in database
	*
	* @params string $type
	* @params Array $array
	*/
	public static function update($type, $array);
	
	/**
	* Show if there are an entity with a specified primary key in database
	*
	* @params string $type
	* @params int $id
	* @return bool
	*/
	public static function exist($type, $id);
	
	/**
	* Find in database an entity with a specified primary key and extract it
	*
	* @params string $type
	* @params int $id
	* @return Array
	*/
	public static function find($type, $id);
	
	/**
	* Delete informations from database with a specified primary key
	*
	* @params string $type
	* @params int $id
	*/
	public static function delete($type, $id);
	
	/**
	* Find in database an entity with a specified foreign key and extract it
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @return Array
	*/
	public static function findByCriteria($type, $array, $alias);
	
	/**
	* Find in database several entities with a specified foreign key and extract them
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @return Array
	*/
	public static function findAllByCriteria($type, $array, $alias);
}
