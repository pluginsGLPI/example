CREATE TABLE `glpi_plugin_example` (
  `ID` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `FK_dropdown` int(11) NOT NULL default '0',
  `deleted` smallint(6) NOT NULL default '0',
  `is_template` smallint(6) NOT NULL default '0',
  `tplname` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `glpi_dropdown_plugin_example` (
  `ID` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `comments` text collate utf8_unicode_ci,
  PRIMARY KEY  (`ID`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;