<?php

namespace tests;

use Bazalt\Site;

class SiteTest extends \Bazalt\Site\Test\BaseCase
{
    protected $view;

    protected function tearDown()
    {
        parent::tearDown();

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

        $_SERVER['HTTP_ORIGIN'] = 'http://localhost';
        //$this->assertEquals('http://localhost', Site::getDomain());
        unset($_SERVER['HTTP_ORIGIN']);
    }

    public function testGetDomainName()
    {
        $_SERVER['SERVER_NAME'] = 'bazalt-cms.com';
        $this->assertEquals('bazalt-cms.com', Site::getDomainName());

        $_SERVER['HTTPS'] = 'on';
        $this->assertEquals('bazalt-cms.com', Site::getDomainName());

        $_SERVER['SERVER_PORT'] = '145';
        $this->assertEquals('bazalt-cms.com', Site::getDomainName());

        $_SERVER['HTTP_ORIGIN'] = 'http://localhost';
        //$this->assertEquals('localhost', Site::getDomainName());
        unset($_SERVER['HTTP_ORIGIN']);
    }

    /**
     * @expectedException \Bazalt\Site\Exception\DomainNotFound

    public function testFetchError()1
    {
        //$this->assertEquals('-', $this->view->fetch('test-invalid'));
    }*/
}