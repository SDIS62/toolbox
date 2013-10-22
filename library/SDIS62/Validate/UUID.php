<?php

class SDIS62_Validate_UUID extends Zend_Validate_Regex
{
    /**
     * On appel le constructeur de Zend_Validate_Regex pour faire correspondre le regex à un UUID
     */
    public function __construct()
    {
        parent::__construct(array(
            'pattern' => '/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/'
        ));
    }
}