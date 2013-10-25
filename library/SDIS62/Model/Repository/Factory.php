<?php

class SDIS62_Repository_Factory
{
    /**
     * Récupération d'un repository
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public static function get($repository)
    {
        return Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getResource('doctrine2')
            ->getRepository($repository);
    }  
}