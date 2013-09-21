<?php
/**
 * Localizable.php
 *
 * @category   CMS
 * @package    ORM
 * @subpackage Plugin
 * @copyright  2010 Equalteam
 * @license    GPLv3
 * @version    $Revision: 133 $
 */

namespace Bazalt\Site\ORM;

use Bazalt\ORM as ORM;
use tests\Model\Base\Language;

/**
 * Localizable Плагін, що надає змогу локалізувати поля в базі даних
 * @link http://wiki.bazalt.org.ua/CMS_ORM_Localizable
 *
 * @category   CMS
 * @package    ORM
 * @subpackage Plugin
 * @copyright  2010 Equalteam
 * @license    GPLv3
 * @version    $Revision: 133 $
 */
class Localizable extends ORM\Plugin\AbstractPlugin
{
    const TRANSLATION_NOT_COMPLETED = 0;

    const TRANSLATION_ORIGINAL = 1;

    const TRANSLATION_COMPLETED = 2;

    protected static $returnAllLanguages = false;

    protected static $currentSite = null;

    /**
     * Додає додаткові службові поля відповідно до типу локалізації.
     * Викликається в момент ініціалізації моделі
     *
     * @param ORM\Record $model   Модель, для якої викликано initFields
     * @param array       $fields  Масив опцій, передається з базової моделі при ініціалізації плагіна
     *
     * @return void
     */
    protected function initFields(ORM\Record $model, $fields)
    {
        $model->hasColumn('lang_id', 'varchar(2)');
        $model->hasColumn('completed', 'U:tinyint(4)|0');
        foreach ($fields as $field) {
            if (!$model->exists($field)) {
                $model->hasColumn($field, '');
            }
        }
    }

    /**
     * Если false, то будет возвращать только переводы для тех языков, которые есть на сайте и заданы через currentSite
     * Если currentSite не задан, то для текущего сайта
     *
     * @param bool $value
     */
    public static function returnAllLanguages($value)
    {
        self::$returnAllLanguages = $value;
    }

    /**
     * Можно задать сайт, для корого будут возвращатся переводы, так как разные сайты могут иметь разные наборы языков
     * Не играет роли, если задано $returnAllLanguages = true
     *
     * @param \Bazalt\Site\Model\Site|null $value
     */
    public static function currentSite($value)
    {
        self::$currentSite = $value;
    }

    /**
     * Ініціалізує плагін
     *
     * @param \Bazalt\ORM\Record $model   Модель, для якої викликано initFields
     * @param array       $options Масив опцій, передається з базової моделі при ініціалізації плагіна
     *
     * @return void
     */
    public function init(ORM\Record $model, $options)
    {
        ORM\BaseRecord::registerEvent(get_class($model), ORM\BaseRecord::ON_FIELD_GET, array($this,'onGet'), ORM\BaseRecord::FIELD_NOT_SETTED);
        ORM\BaseRecord::registerEvent(get_class($model), ORM\BaseRecord::ON_RECORD_SAVE, array($this,'onSave'));
    }

    /**
     * Хендлер на евент onGet моделей які юзають плагін.
     * Евент запалюється при виклику __get() для поля і повертає локалізоване значення
     *
     * @param ORM\Record   $record  Поточний запис
     * @param string      $field   Поле для якого викликається __get()
     * @param bool|string &$return Результат, який повернеться методом __get()
     *
     * @throws \Exception
     * @return void
     */
    public function onGet(ORM\Record $record, $field, &$return)
    {
        $options = $this->getOptions();
        if (!array_key_exists(get_class($record), $options)) {
            return;
        }

        // Якщо поле вже встановлене
        if (array_key_exists($field, $record->getSettedFields())) {
            return;
        }
        $site = (self::$currentSite) ? self::$currentSite : \Bazalt\Site::get();

        $fields = $options[get_class($record)];
        if (is_array($fields) && in_array($field, $fields)) {
            $translates = $this->getTranslations($record);
            foreach ($fields as $field) {
                $fieldData = [];
                foreach ($translates as $tr) {
                    if (self::$returnAllLanguages || $site->hasLanguage($tr->lang_id)) {
                        $fieldData[$tr->lang_id] = $tr->{$field};
                    }
                }
                $record->{$field} = $fieldData;
            }
        } else {
            $record->{$field} = [];
        }
    }

    /**
     * Хендлер на евент onSave моделей які юзають плагін.
     * Евент запалюється при виклику метаду save() для запису і встановлює значення
     * у локалізовані запис
     *
     * @param ORM\Record $record  Поточний запис
     * @param bool      &$return Флаг, який зупиняє подальше виконання save()
     *
     * @return void
     */
    public function onSave(ORM\Record $record, &$return)
    {
        $options = $this->getOptions();
        if (!array_key_exists(get_class($record), $options)) {
            return;
        }
        $fields = $options[get_class($record)];

        $localRecordClass = get_class($record) . 'Locale';
        $return = false;

        foreach ($record->getColumns() as $column) {
            $fieldName = $column->name();
            if (in_array($fieldName, $fields) && array_key_exists($fieldName, $record->getSettedFields())) {
                $return = true;
            }
        }
        if (!$return) {
            unset($record->lang_id);
            unset($record->completed);
            return;
        }

        $site = (self::$currentSite) ? self::$currentSite : \Bazalt\Site::get();
        $languages = $site->getLanguages();
        $records = [];
        foreach ($languages as $lang) {
            $localRecord = new $localRecordClass();
            $localRecord->lang_id = $lang->id;
            $isFilled = false;

            foreach ($record->getColumns() as $column) {
                $fieldName = $column->name();
                if (in_array($fieldName, $fields) && array_key_exists($fieldName, $record->getSettedFields())) {
                    $data = $record->getField($fieldName);
                    if ( (is_array($data) && isset($data[$lang->id]))
                      || (is_object($data) && isset($data->{$lang->id})) ) {

                        $isFilled = true;
                        $localRecord->$fieldName = is_array($data) ? $data[$lang->id] : $data->{$lang->id};
                    }
                }
            }
            if ($isFilled) {
                $records []= $localRecord;
            }
        }
        foreach ($fields as $field) {
            unset($record->$field);
        }
        unset($record->lang_id);
        unset($record->completed);
        $record->save();

        // set primary keys to locale object
        foreach ($record->getColumns() as $column) {
            $fieldName = $column->name();
            if ($column->isPrimaryKey() && array_key_exists($fieldName, $record->getSettedFields())) {
                foreach ($records as $localRecord) {
                    $localRecord->$fieldName = $record->getField($fieldName);
                }
            }
        }

        // save locale objects
        foreach ($records as $localRecord) {
            $localRecord->save();
        }
    }

    public static function getTranslations(ORM\Record $record)
    {
        if (!$record->id) {
            return null;
        }

        $q = ORM::select(get_class($record) . 'Locale l')
            ->where('l.id = ?', $record->id);
        $locals = $q->fetchAll('stdClass');

        return $locals;
    }

    public function toArray(ORM\Record $record, $itemArray, $fields)
    {
        $tr = self::getTranslations($record);

        foreach ($fields as $field) {
            $itemArray[$field] = [];
            $lastLangAlias = null;
            foreach ($tr as $item) {
                $lastLangAlias = $item->lang_id;
                $itemArray[$field][$lastLangAlias] = $item->{$field};
                if ($item->completed == Localizable::TRANSLATION_ORIGINAL) {
                    $itemArray[$field]['orig'] = $item->lang_id;
                }
            }
            if (!isset($itemArray[$field]['orig'])) {
                $itemArray[$field]['orig'] = $lastLangAlias;
            }
        }
        return $itemArray;
    }
}