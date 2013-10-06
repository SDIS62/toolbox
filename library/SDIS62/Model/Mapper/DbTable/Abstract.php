<?php

abstract class SDIS62_Model_Mapper_DbTable_Abstract implements SDIS62_Model_Mapper_Interface
{
    /**
     * La classe DbTable représentant la table mise en jeu dans ce mapper
     * @var Zend_Db_Table_Abstract
     */
    protected $dbTable;

    /**
     * Définition de la classe DbTable à utiliser
     *
     * @param Zend_Db_Table_Abstract|string $dbTable
     * @return SDIS62_Model_Mapper_DbTable_Abstract Interface fluide
     * @throws Zend_Exception Si l'objet n'est pas une instance de Zend_Db_Table_Abstract
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable))
        {
            $dbTable = new $dbTable();
        }
        
        if (!$dbTable instanceof Zend_Db_Table_Abstract)
        {
            throw new Zend_Exception("L'objet n'est pas une instance de type Zend_Db_Table_Abstract");
        }
        
        $this->dbTable = $dbTable;
        
        return $this;
    }
 
    /**
     * Récupération de la DbTable
     * Si la DbTable devant être retournée n'est pas du type Zend_Db_Table_Abstract, on la transforme automatiquement
     *
     * @return Zend_Db_Table_Abstract|null
     */
    public function getDbTable()
    {
        if (is_string($this->dbTable))
        {
            $this->setDbTable($this->dbTable);
        }
        
        return $this->dbTable;
    }
    
    /**
     * Récupération, construction, et envoi d'une entité correspondant à l'id donnée
     *
     * @param int $id
     * @return SDIS62_Model_Proxy_Abstract|null
     */
    public function fetchById($id)
    {
        $row = $this->getDbTable()->find($id)->current();
        
        if(!$row)
        {
            return null;
        }
        
        $data = $row->toArray();
        
        return $this->createEntity($data);
    }
    
    /**
     * Récupération, construction, et envoi d'une entité correspondant aux critères spécifiés
     *
     * @param string|array $where Optionnel
     * @param string|array $order Optionnel
     * @param int $offset Optionnel
     * @return SDIS62_Model_Proxy_Abstract
     */
    public function fetchRow($where = null, $order = null, $offset = null)
    {
        $row = $this->getDbTable()->fetchRow($where, $order, $offset);
        
        if(!$row)
        {
            return null;
        }
        
        $data = $row->toArray();
        
        return $this->createEntity($data);
    }

    /**
     * Récupération, construction, et envoi d'une liste d'entités correspondants aux critères spécifiés
     *
     * @param string|array $where Optionnel
     * @param string|array $order Optionnel
     * @param int $count Optionnel
     * @param int $offset Optionnel
     * @return array
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $entities = array();
        $rows = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
        
        if($rows)
        {
            foreach($rows as $row)
            {
                $data = $row->toArray();
                $entities[] = $this->createEntity($data);
            }
        }
        
        return $entities;
    }
    
    /**
     * On persiste les données de l'entité (sauvegarde)
     * L'implementation de cette fonction est déléguée aux mappers réels
     *
     * @param SDIS62_Model_Proxy_Abstract $entity Paramètre passé en référence
     */
    abstract public function save(SDIS62_Model_Proxy_Abstract &$entity);
    
    /**
     * Suppression des données d'une entité identifiée
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        try
        {
            $this->getDbTable()->find($id)->current()->delete();
            return true;
        }
        catch(Zend_Exception $e)
        {
            return false;
        }
    }
    
    /**
     * Construction et/ou hydratation de l'entité liée au mapper
     * L'implementation de cette fonction est déléguée aux mappers réels
     *
     * @param array $data
     * @return SDIS62_Model_Proxy_Abstract
     */    
    abstract protected function createEntity(array $data);
}