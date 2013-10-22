<?php

namespace tests;

use Bazalt\Site\Option;

class OptionTest extends \tests\BaseCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

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
        $this->assertEquals('t', Option::getForSite('gqtest', 1, 't'));//non exists

        Option::set('gtest', 'testValue', 1, 't');
        $this->assertEquals('testValue', Option::getForSite('gtest', 1, 't'));
    }

    public function testSet()
    {
        Option::set('gtest', 'testValue2', 1, 't');
        $this->assertEquals('testValue2', Option::getForSite('gtest', 1, 't'));

        Option::set('gtest', 'testValue3', 1, 't', true);
        $this->assertEquals('testValue3', Option::getForSite('gtest', 1, 't', true));
    }

    public function testDelete()
    {
        Option::delete('gtest', 1);
        $this->assertEquals('t', Option::getForSite('gtest', 1, 't'));//non exists
    }
}