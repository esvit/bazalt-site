<?php

namespace tests\Data;

class OptionTest extends \Bazalt\Site\Test\BaseCase
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
        parent::setUp();
        
        $this->option = new \Bazalt\Site\Model\Option();
        $this->option->name = 'test';
        $this->option->value = 'testValue';
        $this->option->site_id = $this->site->id;
        $this->option->save();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->option->delete();
    }

    public function testGet()
    {
        $opt = \Bazalt\Site\Model\Option::get('test', $this->site->id);
        $this->assertEquals('testValue', $opt->value);
    }

    public function testSet()
    {
        $opt = \Bazalt\Site\Model\Option::set('test', 'testValue2', $this->site->id);
        $this->assertEquals('testValue2', $opt->value);

        $opt = \Bazalt\Site\Model\Option::get('test', $this->site->id);
        $this->assertEquals('testValue2', $opt->value);
    }

    public function testGetSiteOptions()
    {
        $opts = \Bazalt\Site\Model\Option::getSiteOptions($this->site->id);
        $this->assertEquals(1, count($opts));
        $this->assertEquals('testValue', $opts[0]->value);
    }
}
