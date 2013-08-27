<?php

interface SDIS62_Model_Interface_Abstract
{
    public function getId();
    public function setId($id);
    public function extract();
    public function hydrate(array $data);
}