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
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTPS'] = 'off';
    }

    public function testGetDomain()
    {
        $_SERVER['SERVER_NAME'] = 'xn--80aaysq4a1e.com';
        $this->assertEquals('http://анархия.com', Site::getDomain());

        $_SERVER['SERVER_NAME'] = 'bazalt-cms.com';
        $this->assertEquals('http://bazalt-cms.com', Site::getDomain());

        $_SERVER['HTTPS'] = 'on';
        $this->assertEquals('https://bazalt-cms.com', Site::getDomain());

        $_SERVER['SERVER_PORT'] = '145';
        $this->assertEquals('https://bazalt-cms.com:145', Site::getDomain());
    }

    /**
     * @expectedException \Bazalt\Site\Exception\DomainNotFound

    public function testFetchError()
    {
        //$this->assertEquals('-', $this->view->fetch('test-invalid'));
    }*/
}