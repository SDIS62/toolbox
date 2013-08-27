<?php

abstract class SDIS62_Model_Proxy_Abstract implements SDIS62_Model_Interface_Abstract
{
    protected $entity;
    
    public function __construct()
    {
        // On récupère les valeurs de l'entité
        $data = $this->getEntity()->extract();
        
        // On enlève les id
        unset($data["id"]);
    
        // On filtre les valeurs nulles avec un alias :undefined:
        array_walk($data, function(&$var) {
            if($var === null)
            {
                $var = ":undefined:";
            }
        });
        
        // On transforme les entités liées en proxy
        array_walk_recursive($data, function(&$item, $key) {
            if($key === "classname")
            {
                $item = str_replace("Application_Model_", "Application_Model_Proxy_", $item);
            }
        });
        
        // On hydrate l'entité avec ces nouvelles valeurs
        $this->getEntity()->hydrate($data);
    }

	protected function getEntity()
	{
		if($this->entity === null)
		{
            $name_of_entity_class = str_replace("Proxy_", "", get_class($this));
			$this->setEntity(new $name_of_entity_class);
		}
        
		return $this->entity;
	}

	protected function setEntity(&$entity)
	{
		$this->entity = $entity;
		return $this;
	}

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function setId($id)
    {
        if($this->getId($id) === null)
        {
            return $this->getEntity()->setId($id);
        }
    }
  
    public function extract()
    {
        // On charge complètement l'objet
        $methods = get_class_methods($this);
        foreach($methods as $method)
        {
            if(substr($method, 0, 3) === "get")
            {
                $this->$method();
            }
        }
        
        // On lance l'extract de l'entité
        $data = $this->getEntity()->extract();
        
        // On change les proxys en Models
        array_walk_recursive($data, function(&$item, $key) {
            if($key === "classname")
            {
                $item = str_replace("Application_Model_Proxy_", "Application_Model_", $item);
            }
        });
        
        // On annule l'alias de la valeur null
        array_walk($data, function(&$var) {
            if($var === ":undefined:")
            {
                $var = null;
            }
        });
        
        // On retourne les datas
        return $data;
    }
    
    public function hydrate(array $data)
    {
        array_walk_recursive($data, function(&$item, $key) {
            if($key === "classname")
            {
                $item = str_replace("Application_Model_", "Application_Model_Proxy_", $item);
            }
        });
        
        return $this->getEntity()->hydrate($data);
    }
}