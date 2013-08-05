<?php

namespace tests\Data;

class LocalizableTest extends \tests\BaseCase
{
    protected $site;

    protected function setUp()
    {
        $this->site = \Bazalt\Site\Model\Site::create();
        $this->site->id = 999;
    }

    protected function tearDown()
    {
        $this->site->delete();
    }

    public function testRequired()
    {
        $this->site->title = [
            'en' => 'English title',
            'ru' => 'Русский заголовок',
            'uk' => 'Українська назва'
        ];
        $this->site->save();

        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
                                    ['siteId' => $this->site->id, 'languageId' => 'ru']);
        $obj = $q->fetch();
        $this->assertEquals('Русский заголовок', $obj->title);

        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
                                    ['siteId' => $this->site->id, 'languageId' => 'en']);
        $obj = $q->fetch();
        $this->assertEquals('English title', $obj->title);


        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
                                    ['siteId' => $this->site->id, 'languageId' => 'uk']);
        $obj = $q->fetch();
        $this->assertEquals('Українська назва', $obj->title);

        $this->site->title = [
            'en' => '123321',
            'ru' => 'awdawd'
        ];
        $this->site->save();

        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
            ['siteId' => $this->site->id, 'languageId' => 'ru']);
        $obj = $q->fetch();
        $this->assertEquals('awdawd', $obj->title);

        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
            ['siteId' => $this->site->id, 'languageId' => 'en']);
        $obj = $q->fetch();
        $this->assertEquals('123321', $obj->title);


        $q = new \Bazalt\ORM\Query('SELECT title FROM cms_sites_locale WHERE id = :siteId AND lang_id = :languageId',
            ['siteId' => $this->site->id, 'languageId' => 'uk']);
        $obj = $q->fetch();
        $this->assertEquals('Українська назва', $obj->title);
    }

    public function testGet()
    {
        $this->site->title = [
            'en' => 'English title',
            'ru' => 'Русский заголовок',
            'uk' => 'Українська назва'
        ];

        $this->assertEquals($this->site->title, [
            'en' => 'English title',
            'ru' => 'Русский заголовок',
            'uk' => 'Українська назва'
        ]);
        $this->site->save();

        $site = \Bazalt\Site\Model\Site::getById(999);
        $this->assertEquals($site->title, [
            'en' => 'English title',
            'ru' => 'Русский заголовок',
            'uk' => 'Українська назва'
        ]);
    }
}