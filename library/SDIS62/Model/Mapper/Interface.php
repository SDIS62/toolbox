<?php

interface SDIS62_Model_Mapper_Interface
{
	public static function insert($type, $array);
	public static function update($type, $array);
	public static function fetch($type, $id);
	public static function find($type, $id);
	public static function delete($type, $id);
}
