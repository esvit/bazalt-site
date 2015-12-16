<?php

namespace Bazalt\Site\Model;
use Bazalt\ORM;


class Option extends Base\Option
{
    public static function set($name, $value, $siteId = null)
    {
        $res = self::get($name, $siteId);
        if ($siteId == null) {
            $siteId = \Bazalt\Site::getId();
        }

        if ($res == null || $res->site_id != $siteId) {
            $res = new Option();
            $res->name = $name;
            $res->site_id = $siteId;
        }
        $res->value = $value;
        $res->save();

        return $res;
    }
    
    public static function getValue($name, $defaultValue = null)
    {
        $obj = self::get($name);
        return $obj ? $obj->value : $defaultValue;
    }

    public static function get($name, $siteId = null)
    {
        $opt = false;
        if ($siteId == null) {
            $siteId = \Bazalt\Site::getId();
        }

        $q = Option::select()
            ->where('name = ?', $name)
            ->andWhere('site_id = ?', $siteId);
        $opt = $q->fetch();

        if (!$opt) {
            $q = Option::select()
                ->where('name = ?', $name)
                ->andWhere('site_id IS NULL');

            $opt = $q->fetch();
        }
        return $opt;
    }

    public static function getSiteOptions($siteId = null)
    {
        if ($siteId == null) {
            $siteId = \Bazalt\Site::getId();
        }

        $q = Option::select()
            ->where('site_id IS NULL OR site_id = ?', $siteId)
            ->orderBy('site_id');

        return $q->fetchAll();
    }
}
