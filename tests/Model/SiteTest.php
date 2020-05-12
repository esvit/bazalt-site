<?php

namespace tests\Data;

use Bazalt\Site\Model\Language;

class SiteTest extends \Bazalt\Site\Test\BaseCase
{
    /**
     * @var \Bazalt\Site\Model\Site
     */
    protected $site;

    protected function setUp(): void
    {
        $this->site = \Bazalt\Site\Model\Site::create();
        $this->site->id = 999;
        $this->site->save();
    }

    protected function tearDown(): void
    {
        $this->site->delete();
    }

    public function testLanguages()
    {
        $this->site->addLanguage(Language::getByAlias('ru'));

        /** @var \Bazalt\Site\Model\Site $site */
        $site = \Bazalt\Site\Model\Site::getById(999);
        $this->assertEquals('en,ru', $site->languages);
        $this->assertTrue($site->hasLanguage('en'));
        $this->assertTrue($site->hasLanguage('ru'));
        $this->assertFalse($site->hasLanguage('uk'));
        $this->assertEquals([
            'en' => Language::getByAlias('en'),
            'ru' => Language::getByAlias('ru')
        ], $site->getLanguages());

        $this->site->addLanguage(Language::getByAlias('uk'));

        $site = \Bazalt\Site\Model\Site::getById(999);
        $this->assertEquals('en,ru,uk', $site->languages);
        $this->assertTrue($site->hasLanguage('en'));
        $this->assertTrue($site->hasLanguage('ru'));
        $this->assertTrue($site->hasLanguage('uk'));
        $this->assertEquals([
            'en' => Language::getByAlias('en'),
            'ru' => Language::getByAlias('ru'),
            'uk' => Language::getByAlias('uk')
        ], $site->getLanguages());

        $this->site->addLanguage(Language::getByAlias('uk'));

        $site = \Bazalt\Site\Model\Site::getById(999);
        $this->assertEquals('en,ru,uk', $site->languages);
        $this->assertTrue($site->hasLanguage('en'));
        $this->assertTrue($site->hasLanguage('ru'));
        $this->assertTrue($site->hasLanguage('uk'));
        $this->assertEquals([
            'en' => Language::getByAlias('en'),
            'ru' => Language::getByAlias('ru'),
            'uk' => Language::getByAlias('uk')
        ], $site->getLanguages());

        $this->site->removeLanguage(Language::getByAlias('ru'));

        $site = \Bazalt\Site\Model\Site::getById(999);
        $this->assertEquals('en,uk', $site->languages);
        $this->assertTrue($site->hasLanguage('en'));
        $this->assertFalse($site->hasLanguage('ru'));
        $this->assertTrue($site->hasLanguage('uk'));
        $this->assertEquals([
            'en' => Language::getByAlias('en'),
            'uk' => Language::getByAlias('uk')
        ], $site->getLanguages());
    }
}
