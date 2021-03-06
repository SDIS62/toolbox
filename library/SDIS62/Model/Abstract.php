<?php

abstract class SDIS62_Model_Abstract implements SDIS62_Model_Interface_Abstract
{
    /**
     * @var int|string|null $id
     */
    protected $id;
    
    /**
     * Constructeur (et hydratation de l'entité si le $data est fourni)
     *
     * @param array $data Optionnel
     */
    public function __construct($data = array())
    {
        $this->hydrate($data);
    }

    /**
     * Récupération de l'id de l'entité
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Définition de l'id de l'entité
     *
     * @param int|string|null $id
     * @return SDIS62_Model_Abstract Interface fluide
     * @throws Zend_Exception Si l'id de l'entité est déjà spécifié (prévention d'une modification de l'identifiant)
     */
    public function setId($id)
    {
        if($this->getId($id) == null)
        {
            $this->id = $id;
        }
        else
        {
            throw new Zend_Exception("L'id d'une entité ne peut pas être modifié");
        }
        
        return $this;
    }

    /**
     * Extraction de l'entité en un tableau de données
     *
     * @return array
     */
    public function extract()
    {
        $vars = get_object_vars($this);
        $this->extractRecursive($vars);
        
        // On enlève les données parasites
        unset($vars["__initializer__"]);
        unset($vars["__cloner__"]);
        unset($vars["__isInitialized__"]);
        
        return $vars;
    }

    /**
     * Fonction permettant l'extraction d'un objet de façon récursive
     * Ignore les attributs commençants par "related_"
     *
     * @param array $array Paramètre passé par référence
     */
    protected function extractRecursive(array &$array)
    {
        foreach($array as $key => &$var)
        {
            if(substr($key, 0, 8) == 'related_')
            {
                unset($array[$key]);
                continue;
            }
            elseif(is_object($var))
            {
                if($var instanceof Doctrine\ORM\PersistentCollection)
                {
                    $var = $var->toArray();
                    
                }
                elseif($var instanceof Doctrine\Common\Collections\ArrayCollection)
                {
                    $var = $var->toArray();
                }
                elseif($var instanceof \DateTime)
                {
                    $var = $var->format('H:i:s') == "00:00:00" ? $var->format('Y-m-d') : $var->format('Y-m-d H:i:s');
                }
                else
                {
                    $var = $var->extract();
                }
            }
            
            if(is_array($var))
            {   
                $this->extractRecursive($var);
            }
        }
    }
    
    /**
     * Hydratation (remplissage) de l'entité à partir d'un tableau de données
     *
     * @return SDIS62_Model_Abstract Interface fluide
     */
    public function hydrate(array $data)
    {
        $this->hydrateRecursive($data);
    
        foreach($data as $n => $v)
		{
            if(array_key_exists($n, $this->extract()))
            {
                $this->$n = $v;
            }
        }

		return $this;
    }
    
    /**
     * Fonction permettant d'hydrater un objet de façon récursive
     *
     * @param array $array Paramètre passé par référence
     */
    private function hydrateRecursive(array &$array) 
    {
        if(is_array($array))
        {
            foreach($array as &$item)
            {
                if(is_array($item))
                {
                    $this->hydrateRecursive($item);

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
    
    /**
     * Conversion de l'objet en chaine de caractères
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->id);
    }
}