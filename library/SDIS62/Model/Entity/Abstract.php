<?php

abstract class SDIS62_Model_Entity_Abstract // implements hydrator
{
	public function setPrimary($primary)
	{
		if($this->primary === null)
			$this->primary = $primary;
		else
			throw new Exception(500, "La clé primaire existe déjà !");
	}
	
	public function getPrimary()
	{
		return $this->primary;
	}
}
