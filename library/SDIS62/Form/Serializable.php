<?php

abstract class SDIS62_Form_Serializable
{
    /*
     * Avoid the PDO Exception when we try to serialize the form with a db row
     */
    public function __sleep()
    {
        return array();
    }
}