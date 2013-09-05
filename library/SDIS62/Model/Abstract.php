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
        $this->_extract($vars);
        $vars["classname"] = get_class($this);
        return $vars;
    }
    
    public function _extract(&$array)
    {
        foreach($array as $key => &$var)
        {
            if(is_object($var))
            {
                if($var instanceof SDIS62_Model_Proxy_Abstract)
                {
                    $var = $var->getEntity()->extract();
                }
                else
                {
                    $var = $var->extract();
                }
            }
            elseif(is_array($var))
            {   
                $this->_extract($var);
            }
        }
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