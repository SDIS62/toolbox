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
	* Insert informations in database and get the primary key
	*
	* @params string $type
	* @params Array $array
	* @params Array $infos
	* @return int
	*/
	public static function insert($type, $array, $infos);
	
	/**
	* Update informations in database
	*
	* @params string $type
	* @params Array $array
	* @params Array $infos
	*/
	public static function update($type, $array, $infos);
	
	/**
	* Find in database an entity with a specified primary key and extract it
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	* @return Array
	*/
	public static function find($type, $id, $infos);
	
	/**
	* Show if there are an entity with a specified primary key in database
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	* @return bool
	*/
	public static function exist($type, $id, $infos);
	
	/**
	* Delete informations from database with a specified primary key
	*
	* @params string $type
	* @params int $id
	* @params Array $infos
	*/
	public static function delete($type, $id, $infos);
	
	/**
	* Find in database an entity with a specified foreign key and extract it
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @params Array $infos
	* @return Array
	*/
	public static function findByCriteria($type, $condition, $infos);
	
	/**
	* Find in database several entities with a specified foreign key and extract them
	*
	* @params string $type
	* @params Array $array
	* @params Array $alias
	* @params Array $infos
	* @return Array
	*/
	public static function findAllByCriteria($type, $condition, $infos);
}
