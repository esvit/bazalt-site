<?php

namespace Bazalt\Site\Model;
use Bazalt\ORM;

/**
 * @property Site|null originalSite Оригінальний сайт, з якого був здійснений редірект
 */
class Site extends Base\Site
{
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
}