<?php

namespace tests\Data;

use Bazalt\Site\Data\Validator;
use Bazalt\Site\Model\Language;
use Bazalt\Site\ORM\Localizable;

class ValidatorTest extends \Bazalt\Site\Test\BaseCase
{
    protected $site;

    protected function setUp(): void
    {
        $this->site = \Bazalt\Site\Model\Site::create();
        $this->site->id = 999;
        $this->site->save();

        Localizable::setCurrentSite($this->site);
        Localizable::setReturnAllLanguages(false);

        $this->site->addLanguage(Language::getByAlias('en'));
        $this->site->addLanguage(Language::getByAlias('ru'));
        $this->site->addLanguage(Language::getByAlias('uk'));
    }

    protected function tearDown(): void
    {
        $this->site->delete();
        Localizable::setCurrentSite(null);
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
