<?php

namespace tests;

use Bazalt\Site\Option;

class OptionTest extends \Bazalt\Site\Test\BaseCase
{
    public function testCryptOption()
    {
        $this->assertEquals(Option::cryptOption('test'), 'FZhR9XYytydxNFDRJRTyCw==');
        $this->assertEquals(Option::cryptOption('test', 'test'), 'N1GGQkmaasxPp8nJn1DNNQ==');
    }

    public function testDecryptOption()
    {
        $this->assertEquals(Option::decryptOption('FZhR9XYytydxNFDRJRTyCw=='), 'test');
        $this->assertEquals(Option::decryptOption('N1GGQkmaasxPp8nJn1DNNQ==', 'test'), 'test');
    }

    public function testGetForSite()
    {
        $this->assertEquals('t', Option::getForSite('gqtest', $this->site->id, 't'));//non exists

        Option::set('gtest', 'testValue', $this->site->id, 't');
        $this->assertEquals('testValue', Option::getForSite('gtest', $this->site->id, 't'));
    }

    public function testSet()
    {
        Option::set('gtest', 'testValue2', $this->site->id, 't');
        $this->assertEquals('testValue2', Option::getForSite('gtest', $this->site->id, 't'));

        Option::set('gtest', 'testValue3', $this->site->id, 't', true);
        $this->assertEquals('testValue3', Option::getForSite('gtest', $this->site->id, 't', true));
    }

    public function testDelete()
    {
        Option::delete('gtest', $this->site->id);
        $this->assertEquals('t', Option::getForSite('gtest', $this->site->id, 't'));//non exists
    }
}