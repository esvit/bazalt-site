DROP TABLE IF EXISTS `cms_languages`;
CREATE TABLE IF NOT EXISTS `cms_languages` (
  `id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `cms_themes`;
CREATE TABLE IF NOT EXISTS `cms_themes` (
  `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `settings` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `cms_sites`;
CREATE TABLE `cms_sites` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `path` VARCHAR(255) NOT NULL DEFAULT '/' COLLATE 'utf8_unicode_ci',
  `secret_key` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `theme_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `language_id` VARCHAR(2) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
  `languages` VARCHAR(255) NOT NULL DEFAULT 'en' COLLATE 'utf8_unicode_ci',
  `is_subdomain` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_multilingual` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_allow_indexing` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `site_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `is_redirect` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `domain` (`domain`),
  INDEX `FK_cms_sites_cms_sites` (`site_id`),
  INDEX `FK_cms_sites_cms_users` (`user_id`),
  INDEX `FK_cms_sites_cms_languages` (`language_id`),
  INDEX `FK_cms_sites_cms_themes` (`theme_id`),
  CONSTRAINT `FK_cms_sites_cms_languages` FOREIGN KEY (`language_id`) REFERENCES `cms_languages` (`id`) ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT `FK_cms_sites_cms_sites` FOREIGN KEY (`site_id`) REFERENCES `cms_sites` (`id`) ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT `FK_cms_sites_cms_themes` FOREIGN KEY (`theme_id`) REFERENCES `cms_themes` (`id`) ON UPDATE NO ACTION ON DELETE SET NULL,
  CONSTRAINT `FK_cms_sites_cms_users` FOREIGN KEY (`user_id`) REFERENCES `cms_users` (`id`) ON UPDATE NO ACTION ON DELETE SET NULL
)
  COLLATE='utf8_unicode_ci'
  ENGINE=InnoDB;


DROP TABLE IF EXISTS `cms_sites_locale`;
CREATE TABLE IF NOT EXISTS `cms_sites_locale` (
  `id` int(10) unsigned NOT NULL,
  `lang_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`lang_id`),
  KEY `FK_cms_sites_locale_cms_languages` (`lang_id`),
  CONSTRAINT `FK_cms_sites_locale_cms_languages` FOREIGN KEY (`lang_id`) REFERENCES `cms_languages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_cms_sites_locale_cms_sites` FOREIGN KEY (`id`) REFERENCES `cms_sites` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `cms_languages_ref_sites`;
CREATE TABLE IF NOT EXISTS `cms_languages_ref_sites` (
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `is_active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`,`site_id`),
  KEY `FK__cms_sites` (`site_id`),
  KEY `language_id_site_id_is_active` (`language_id`,`site_id`,`is_active`),
  CONSTRAINT `FK__cms_languages` FOREIGN KEY (`language_id`) REFERENCES `cms_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__cms_sites` FOREIGN KEY (`site_id`) REFERENCES `cms_sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `cms_options`;
CREATE TABLE `cms_options` (
	`site_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`value` TEXT NULL,
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `site_id_name` (`site_id`, `name`),
	CONSTRAINT `FK_cms_options_cms_sites` FOREIGN KEY (`site_id`) REFERENCES `cms_sites` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
)
COMMENT='Options of site'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `cms_languages` (`id`, `title`) VALUES ('en', 'English');
INSERT INTO `cms_languages` (`id`, `title`) VALUES ('ru', 'Русский (Russian)');
INSERT INTO `cms_languages` (`id`, `title`) VALUES ('uk', 'Українська (Ukrainian)');

INSERT INTO `cms_sites` (`id`, `domain`, `path`, `secret_key`, `theme_id`, `language_id`, `languages`, `is_subdomain`, `is_active`, `is_multilingual`, `is_allow_indexing`, `user_id`, `created_at`, `updated_at`, `site_id`, `is_redirect`)
  VALUES (1, 'localhost', '/', NULL, 'default', 'en', 'en', 0, 1, 1, 0, NULL, '2013-08-03 12:45:21', '2013-10-15 00:59:36', NULL, 0);
