<?php

namespace Bazalt\Site\Model\Base;

/**
 * @property    int     id
 * @property    int     site_id
 * @property    int     component_id
 * @property    string  name
 * @property    string  value
 */
abstract class Option extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'cms_options';

    const MODEL_NAME = 'Bazalt\\Site\\Model\\Option';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PUA:int(10)');
        $this->hasColumn('site_id', 'UN:int(10)');
        $this->hasColumn('name', 'varchar(255)');
        $this->hasColumn('value', 'text');
    }

    public function initRelations()
    {
    }

    public function initPlugins()
    {
        $this->hasPlugin('Bazalt\\ORM\\Plugin\\Timestampable', ['created' => 'created_at', 'updated' => 'updated_at']);
    }
}