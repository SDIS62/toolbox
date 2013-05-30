<?php

interface SDIS62_Model_DAO_Interface
{
	public function save(SDIS62_Model_Proxy_Abstract $proxy);
	public function delete($id);
	public function fetch($id);
	public function remplir(SDIS62_Model_Proxy_Abstract $proxy);
}
