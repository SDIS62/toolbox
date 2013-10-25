<?php

class LibraryTest extends PHPUnit_Framework_TestCase
{
    public function testServices()
    {
        $service = new SDIS62_Service_Module_Generic("http://apps.sdis62.local/admin/api/user");
        Zend_Debug::Dump($service->isAuthorized("9", "4eed9098bb09144dd00f431cc3c512031585bb9c"));
        // Zend_Debug::Dump($service->getMessagesByFeed("admin", null, 20, 1));
    }
}