<?php

namespace Bazalt\Site\Model\Base;

abstract class SiteLocale extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'cms_sites_locale';

    const MODEL_NAME = 'Bazalt\Site\Model\SiteLocale';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PU:int(10)');
        $this->hasColumn('lang_id', 'PU:int(10)');
        $this->hasColumn('title', 'N:varchar(255)');
        $this->hasColumn('completed', 'U:tinyint(4)|0');
    }

    public function initRelations()
    {

    }
}