<?php

abstract class SDIS62_Model_Mapper_Abstract
{
    protected $dbTable;
    protected $matches = array();
    
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable))
        {
            $dbTable = new $dbTable();
        }
        
        if (!$dbTable instanceof Zend_Db_Table_Abstract)
        {
            throw new Exception('Invalid table data gateway provided');
        }
        
        $this->dbTable = $dbTable;
        
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->dbTable || is_string($this->dbTable))
        {
            $this->setDbTable($this->dbTable);
        }
        return $this->dbTable;
    }
    
    public function find($id, &$entity = null)
    {
        $data = $this->getDbTable()->find($id)->current()->toArray();
        
        if($entity === null)
        {
            $entity = $this->createEntityFromDb($data);
        }
        else
        {
            $entity->hydrate($data);
        }
        
        return $entity;
    }
    
    public function save(&$entity)
    {
        $row = null;
        $data = array_intersect_key($this->entityToDbMapper($entity->extract()), array_flip($this->getDbTable()->info(Zend_Db_Table_Abstract::COLS)));
        $this->fillArrayWithDefaultValues($data);

        if($entity->getId() === null)
        {
            $entity->setId($this->getDbTable()->insert($data));
        }
        else
        {
            $row = $this->getDbTable()->find($entity->getId())->current();
            $row->setFromArray($data);
            $entity->setId($row->save());
        }
        
        return $entity->getId();
    }
    
    public function delete($id)
    {
        $entity = $this->getDbTable()->find($id)->current();
        $entity->delete();
    }
    
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $entities = array();
        $rows = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
        
        foreach($rows as $row)
        {
            $entities[] = $this->createEntityFromDb($row->toArray());
        }
        
        return $entities;
    }
    
    public function fetchRow($where = null, $order = null, $offset = null)
    {
        $data = $this->getDbTable()->fetchRow($where, $order, $offset)->toArray();
        $entity = $this->createEntityFromDb($data);
        
        return $entity;
    }
    
    final protected function dbToEntityMapper(&$data)
    {
        foreach($data as $key => $value)
        {
            if(array_key_exists($key, $this->matches))
            {
                $newkey = $this->matches[$key];
                $oldkey = $key;
                
                $data[$newkey] = $data[$oldkey];
                unset($data[$oldkey]);
            }
        }
        
        return $data;
    }
    
    final protected function entityToDbMapper(&$data)
    {
        $matches_inverse = array_flip($this->matches);

        foreach($data as $key => $value)
        {
            if(array_key_exists($key, $matches_inverse))
            {
                $newkey = $matches_inverse[$key];
                $oldkey = $key;
                
                $data[$newkey] = $data[$oldkey];
                unset($data[$oldkey]);
            }
        }
        
        return $data;
    }
    
    final protected function fillArrayWithDefaultValues(array &$data)
    {
        $cols = $this->getDbTable()->info(Zend_Db_Table_Abstract::METADATA);
        
        foreach($data as $key => $value)
        {
            if($value === null)
            {
                if(array_key_exists($key, $cols) && $cols[$key]["DEFAULT"] !== null)
                {
                    switch($cols[$key]["DEFAULT"])
                    {
                        case "CURRENT_TIMESTAMP":
                            $data[$key] = new Zend_Db_Expr('CURRENT_TIMESTAMP');
                            break;
                        default:
                            $data[$key] = $cols[$key]["DEFAULT"];
                    }
                }
            }
        }
    }
    
    abstract public function createEntityFromDb(array $data);
}