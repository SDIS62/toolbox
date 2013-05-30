<?php

abstract class SDIS62_Model_Entity_Abstract implements SDIS62_Model_Entity_Hydrator_Interface
{
	public function setPrimary($primary)
	{
		if($this->primary === null)
			$this->primary = $primary;
		else
			throw new Exception(500, "La clé primaire de l'entité existe déjà !");
	}
	
	public function getPrimary()
	{
		return $this->primary;
	}
	
	public abstract function hydrate($array);
	public abstract function extract();
}
