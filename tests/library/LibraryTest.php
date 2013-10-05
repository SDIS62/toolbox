<?php

class LibraryTest extends PHPUnit_Framework_TestCase
{
    public function testServices()
    {
        $service = new SDIS62_Service_Module_Generic("http://apps.sdis62.local/modules/feed/api");
        Zend_Debug::Dump($service->getMessagesByFeed("admin", null, 20, 1));
    }
}