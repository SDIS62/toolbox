<?php

abstract class SDIS62_Model_Proxy_Abstract
{
	private $entity = null;
	
	public function getEntity()
	{
		if($this->entity === null)
		{
			$class = 'Application_Model_Entity_'.$this->type_objet;
			$this->entity = new $class;
		}
		return $this->entity;
	}
	
	public function setEntity(SDIS62_Model_Entity_Abstract $entity)
	{
		$this->entity = $entity;
	}
		
	public function setPrimary($primary)
	{	
		$this->getEntity()->setPrimary($primary);
	}
	
	public function getPrimary()
	{	
		return $this->getEntity()->getPrimary();
	}
}
