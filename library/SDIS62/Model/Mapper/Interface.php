<?php

interface SDIS62_Model_Mapper_Interface
{
	public static function update(SDIS62_Model_Proxy_Abstract $proxy);
	public static function find($entity, $id);
	public static function delete($type, $id);
}
