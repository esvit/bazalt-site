<?php

namespace Bazalt\Site\Model;
use Bazalt\ORM;

/**
 * @property Site|null originalSite Оригінальний сайт, з якого був здійснений редірект
 */
class Site extends Base\Site
{
    private $_languages = null;

    public static function create()
    {
        $site = new Site();
        return $site;
    }

    public static function getSiteByDomain($domain, $onlyActive = true)
    {
         $q = ORM::select('Bazalt\Site\Model\Site s')
                 ->where('s.domain = ?', $domain);

         if ($onlyActive) {
             $q->andWhere('s.is_active = ?', 1);
         }
         return $q->fetch();
    }

    public static function getSiteMirrors($site)
    {
        $siteId = $site->id;
        if ($site->site_id) {
            $siteId = $site->site_id;
        }
        $mirrors = ORM::select('Bazalt\Site\Model\Site s')
            ->where('s.site_id = ?', $siteId)
            ->fetchAll();
        return $mirrors;
    }

    public function getUrl()
    {
        return 'http://' . $this->domain . $this->path;
    }

    public function getMirrors()
    {
        return self::getSiteMirrors($this);
    }

    public function addLanguage(Language $language)
    {
        $this->Languages->add($language, array('is_active' => 1));
    }

    /**
     * @return Language[]
     */
    public function getLanguages()
    {
        if (!$this->_languages) {
            $langs = explode(',', $this->languages);
            $q = Language::select()->whereIn('id', $langs);
            $languages = $q->fetchAll();
            $this->_languages = [];

            // fill array in order of $this->languages
            foreach ($langs as $l) {
                foreach ($languages as $lang) {
                    if ($lang->id == $l) {
                        $this->_languages[$lang->id] = $lang;
                    }
                }
            }
        }
        return $this->_languages;
    }
}