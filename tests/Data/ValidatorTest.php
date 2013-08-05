<?php

namespace tests\Data;

use Bazalt\Site\Data\Validator;

class ValidatorTest extends \tests\BaseCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testRequired()
    {
        $data = Validator::create([
            'title' => [
                'en' => 'Test English',
                'ru' => 'Проверка русского',
                'uk' => 'Слава Україні'
            ]
        ]);

        $data->localizableField('title')->required();

        $this->assertTrue($data->validate(), json_encode($data->errors()));

        $data->data([
            'title' => 'test'
        ]);

        $this->assertFalse($data->validate());

        $data->data([
            'title' => [
                'en' => '',
                'ru' => 'Проверка русского',
                'uk' => 'Слава Україні'
            ]
        ]);

        $this->assertFalse($data->validate());
    }
}