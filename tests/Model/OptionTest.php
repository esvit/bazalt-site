<?php

namespace tests\Data;

class OptionTest extends \tests\BaseCase
{
    /**
     * @var \Bazalt\Site\Model\Option
     */
    protected $option;
    
    /**
     * @var \Bazalt\Site\Model\Site
     */
    protected $site;

    protected function setUp()
    {
        $this->site = \Bazalt\Site\Model\Site::create();
        $this->site->id = 1;
        $this->site->save();
        
        $this->option = new \Bazalt\Site\Model\Option();
        $this->option->name = 'test';
        $this->option->value = 'testValue';
        $this->option->site_id = 1;
        $this->option->save();
    }

    protected function tearDown()
    {
        $this->site->delete();
    }

    public function testGet()
    {
        $opt = \Bazalt\Site\Model\Option::get('test', 1);
        $this->assertEquals('testValue', $opt->value);
    }

    public function testSet()
    {
        $opt = \Bazalt\Site\Model\Option::set('test', 'testValue2');
        $this->assertEquals('testValue2', $opt->value);

        $opt = \Bazalt\Site\Model\Option::get('test', 1);
        $this->assertEquals('testValue2', $opt->value);
    }

    public function testGetSiteOptions()
    {
        $opts = \Bazalt\Site\Model\Option::getSiteOptions(1);
        $this->assertEquals(1, count($opts));
        $this->assertEquals('testValue', $opts[0]->value);
    }
}
