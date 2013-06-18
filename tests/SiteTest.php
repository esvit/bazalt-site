<?php

namespace tests;

use Bazalt\Site;

class SiteTest extends \tests\BaseCase
{
    protected $view;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testGet()
    {
        $_SERVER['SERVER_NAME'] = 'bazalt-cms.com';
        $this->assertEquals($event->getName(), Site::getDomainName());
    }

    /**
     * @expectedException Exception
     
    public function testFetchError()
    {
        //$this->assertEquals('-', $this->view->fetch('test-invalid'));
    }*/
}