<?php

namespace Bazalt\Site\Model\Base;

/**
 * @property-read int    $id
 * @property-read string $title
 */
abstract class Language extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'cms_languages';

    const MODEL_NAME = 'Bazalt\\Site\\Model\\Language';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PUA:varchar(2)');
        $this->hasColumn('title', 'varchar(50)');
    }

    public function initRelations()
    {
    }
}