<?php

namespace Bazalt;

class Site
{
    const PUNYCODE_PREFIX = 'xn--';

    protected static $enableMultisiting = false;

    protected static $currentSite = null;

    public static function enableMultisiting($enableMultisiting = null)
    {
        if ($enableMultisiting !== null) {
            self::$enableMultisiting = $enableMultisiting;
        }
        return self::$enableMultisiting;
    }

    /**
     * Return current protocol
     *
     * @return string "http://" or "https://"
     */
    public static function getProtocol()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    }

    public static function needPortInDomain()
    {
        if (!isset($_SERVER['SERVER_PORT'])) {
            return false;
        }
        return !($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443);
    }

    /**
     * Return current server name
     *
     * @return string like "http://bazalt-cms.com" or "https://bazalt-cms.com:8080"
     */
    public static function getDomain()
    {
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
        // CORS
        /*if (isset($_SERVER['HTTP_ORIGIN'])) {
            return $_SERVER['HTTP_ORIGIN'];
        }*/
        if (!$serverName) {
            // when in cli mode
            return null;
        }

        if (substr($serverName, 0, strLen(self::PUNYCODE_PREFIX)) == self::PUNYCODE_PREFIX) {
            $convertor = new \Bazalt\Site\IDNConvertor(array('idn_version' => 2008));
            $serverName = $convertor->decode($serverName);
        }
        // If server name has port number attached then strip it
        $colon = strpos($serverName, ':');
        if ($colon) {
            $serverName = substr($serverName, 0, $colon);
        }
        $serverName .= !self::needPortInDomain() ? '' : ':' . $_SERVER['SERVER_PORT'];

        return self::getProtocol() . $serverName;
    }

    public static function getDomainName()
    {
        $domain = strToLower(self::getDomain());
        // remove protocol
        if (strpos($domain, '://') !== false) {
            $domain = substr($domain, strpos($domain, '://') + 3);
        }
        // remove port
        if (strpos($domain, ':') !== false) {
            $domain = substr($domain, 0, strpos($domain, ':'));
        }
        if (substr($domain, 0, 4) == 'www.') {
            $domain = substr($domain, 4);
        }
        return $domain;
    }

    public static function getId()
    {
        return self::get()->id;
    }

    /**
     * Detect current site from domain name and redirect as required
     *
     * @throws Site\Exception\DomainNotFound
     */
    public static function get()
    {
        if (self::$currentSite) {
            return self::$currentSite;
        }
        $domain = self::getDomainName();
        
        if (!self::$enableMultisiting || TESTING_STAGE) {
            $site = Site\Model\Site::getById(1);
            if (!$site) {
                $site = Site\Model\Site::create();
                $site->id = 1;
                $site->domain = $domain;
                $site->is_subdomain = false;
                $site->is_active = true;
                $site->save();
            } else {
                $site->is_subdomain = false;
                $site->is_active = true;
            }
        } else {
            $site = Site\Model\Site::getSiteByDomain($domain);
            if (!$site) {
                $wildcard = '*' . substr($domain, strpos($domain, '.'));
                $site = Site\Model\Site::getSiteByDomain($wildcard);
                if ($site) {
                    $site->subdomain = substr($domain, 0, strpos($domain, '.'));
                }
            }
        }
        if (!$site) {
            throw new Site\Exception\DomainNotFound($domain);
        }
        if ($site->is_redirect && $site->site_id) {
            header('Location: ' . self::getProtocol() . $site->Site->domain);
        }
        self::$currentSite = $site;
        return $site;
    }

    public static function setCurrent($site)
    {
        self::$currentSite = $site;
    }
}