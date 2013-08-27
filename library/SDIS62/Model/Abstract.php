<?php

abstract class SDIS62_Model_Abstract implements SDIS62_Model_Interface_Abstract
{
    protected $id;
      
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
  
    public function extract()
    {
        $vars = get_object_vars($this);
        
        foreach($vars as $key => $var)
        {
            if(is_object($var))
            {
                $key = $var->extract();
            }
        }
        
        $vars["classname"] = get_class($this);
        
        return $vars;
    }
    
    public function hydrate(array $data)
    {
        $this->_hydrate($data);
    
        foreach($data as $n => $v)
		{
            if(array_key_exists($n, $this->extract()))
            {
                $this->$n = $v;
            }
        }

		return $this;
    }
    
    private function _hydrate(&$array) 
    {
        if(is_array($array))
        {
            foreach($array as &$item)
            {
                if(is_array($item))
                {
                    $this->_hydrate($item);

                    if(array_key_exists("classname", $item))
                    {
                        $object = new $item["classname"];
                        $object->hydrate($item);
                        $item = $object;
                        unset($object);
                    }
                }
            }
        }
    }
}