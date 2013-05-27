<?php

abstract class SDIS62_Model_Proxy_Abstract
{
	private $entity = null;
	//protected $dao = null;
	
	public function getDAO()
	{
		if($this::$dao === null)
		{
			$class_dao = 'Application_Model_DAO_'.$this->type_objet;
			$this::$dao = new $class_dao($this->type_objet);
		}
		return $this::$dao;
	}
	
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
