<?php

namespace Bazalt\Site\Model\Base;

/**
 * @property    int     id
 * @property    string  domain
 * @property    string  title
 * @property    string  secret_key
 * @property    int     theme_id
 * @property    string  language_id
 * @property    string  languages
 * @property    int     is_subdomain
 * @property    int     user_id
 * @property    int     is_active
 * @property    int     is_multilingual
 * @property    int     is_allow_indexing
 * @property    int     site_id
 * @property    bool    is_redirect
 * @property    bool    is_https
 * @property    bool    use_letsencrypt
 * @property    int     partner_id
 * @property    Language       DefaultLanguage
 * @property    Language[]     Languages
 */
abstract class Site extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'cms_sites';

    const MODEL_NAME = 'Bazalt\\Site\\Model\\Site';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PUA:int(10)');
        $this->hasColumn('domain', 'varchar(255)|localhost');
        $this->hasColumn('title', 'varchar(255)');
        $this->hasColumn('path', 'varchar(255)|/');
        $this->hasColumn('languages', 'N:varchar(255)');
        $this->hasColumn('secret_key', 'N:varchar(255)');
        $this->hasColumn('theme_id', 'NU:int(11)');
        $this->hasColumn('language_id', 'NU:varchar(2)');
        $this->hasColumn('is_active', 'U:tinyint(3)|0');
        $this->hasColumn('is_https', 'U:tinyint(3)|0');
        $this->hasColumn('use_letsencrypt', 'U:tinyint(3)|0');
        $this->hasColumn('partner_id', 'NU:int(10)');
    }

    public function initRelations()
    {
        $this->hasRelation('Site', new \Bazalt\ORM\Relation\One2One(self::MODEL_NAME, 'site_id', 'id'));

        $this->hasRelation('Theme', new \Bazalt\ORM\Relation\One2One('Bazalt\\Site\\Model\\Theme', 'theme_id', 'id'));

        $this->hasRelation('DefaultLanguage', new \Bazalt\ORM\Relation\One2One('Bazalt\\Site\\Model\\Language', 'language_id', 'id'));
        $this->hasRelation('Languages', new \Bazalt\ORM\Relation\Many2Many('Bazalt\\Site\\Model\\Language', 'site_id', 'Bazalt\\Site\\Model\\LanguageRefSite', 'language_id'));
    }

    public function initPlugins()
    {
        //$this->hasPlugin('Bazalt\\Site\\ORM\\Localizable', ['title']);
        $this->hasPlugin('Bazalt\\ORM\\Plugin\\Timestampable', ['created' => 'created_at', 'updated' => 'updated_at']);
    }
}
