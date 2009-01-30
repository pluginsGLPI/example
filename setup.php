<?php
/*
 * @version $Id: HEADER 7762 2009-01-06 18:30:32Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// Init the hooks of the plugins -Needed
function plugin_init_example() {
	global $PLUGIN_HOOKS,$LANG,$CFG_GLPI;

	$plugin_name = "example";

	// Display a menu entry ?
	$submenu_entries["add"]="example.form.php";
	$submenu_entries["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_example']["test"]."'" .
									" alt='".$LANG['plugin_example']["test"]."'>"]="index.php";
	$submenu_entries[$LANG['plugin_example']["test"]]="index.php";
	$submenu_entries["config"]="index.php";								
							
	pluginEnableMenuEntry($plugin_name,$submenu_entries);

	// Onglets management
	pluginEnableHeadings($plugin_name);

	// Item action event // See define.php for defined ITEM_TYPE
	$hooks = array ('pre_item_update',
					'item_update',
					'item_update',
					'item_add',
					'pre_item_delete',
					'item_delete',
					'pre_item_purge',
					'item_purge',
					'pre_item_restore',
					'item_restore',
					'item_transfer');
					
	pluginEnableHooks($plugin_name,$hooks);	
	
	pluginEnableMassiveActions($plugin_name);
	
	// Cron action
	pluginAddCronTask($plugin_name,DAY_TIMESTAMP);

	//redirect appel http://localhost/glpi/index.php?redirect=plugin_example_2 (ID 2 du form)
	pluginAddRedirectPage($plugin_name,"example.form.php");

	//function to populate & display planning
	pluginUsePlanning($plugin_name,array('planning_populate','display_planning'));
	
	// Add specific files to add to the header : javascript or css
	pluginAddJavascriptPage($plugin_name,"example.js");
	pluginAddSpecificCss($plugin_name,"example.css");

	// Retrieve others datas from LDAP
	//$PLUGIN_HOOKS['retrieve_more_data_from_ldap']['example']="plugin_retrieve_more_data_from_ldap_example";

	// Reports & stats
	$params["reports"] = array('report.php'=>'New Report', 'report.php?other'=>'New Report 2',);
	$params["stats"] = array('stat.php'=>'New stat', 'stat.php?other'=>'New stats 2',);
	pluginAddStatsOrReports($plugin_name,$params);
	
	// Params : plugin name - string type - ID - class - table - form page - Type name
	pluginNewType('example',"PLUGIN_EXAMPLE_TYPE",1001,"pluginExample","glpi_plugin_example","example.form.php","Example Type");
}


// Get the name and the version of the plugin - Needed
function plugin_version_example(){
	return array( 
		'name'    => 'Plugin Example',
		'version' => '0.1.0',
		'author' => 'Julien Dombre',
		'homepage'=> 'http://glpi-project.org',
		'minGlpiVersion' => '0.72',// For compatibility / no install in version < 0.72
	);
}

// Install process for plugin : need to return true if succeeded
function plugin_example_install(){
	global $DB;
	if (!TableExists("glpi_plugin_example")){
		$query="CREATE TABLE `glpi_plugin_example` (
			`ID` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`serial` varchar(255) collate utf8_unicode_ci NOT NULL,
			`FK_dropdown` int(11) NOT NULL default '0',
			`deleted` smallint(6) NOT NULL default '0',
			`is_template` smallint(6) NOT NULL default '0',
			`tplname` varchar(255) collate utf8_unicode_ci default NULL,
			PRIMARY KEY  (`ID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			";
		$DB->query($query) or die("error creating glpi_plugin_example ". $DB->error());
		$query="INSERT INTO `glpi_plugin_example` (`ID`, `name`, `serial`, `FK_dropdown`, `deleted`, `is_template`, `tplname`) VALUES
			(1, 'example 1', 'serial 1', 1, 0, 0, NULL),
			(2, 'example 2', 'serial 2', 2, 0, 0, NULL),
			(3, 'example 3', 'serial 3', 1, 0, 0, NULL);";
		$DB->query($query) or die("error populate glpi_plugin_example ". $DB->error());

	}
	if (!TableExists("glpi_dropdown_plugin_example")){

		$query="CREATE TABLE `glpi_dropdown_plugin_example` (
			`ID` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`comments` text collate utf8_unicode_ci,
			PRIMARY KEY  (`ID`),
			KEY `name` (`name`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		$DB->query($query) or die("error creating glpi_dropdown_plugin_example". $DB->error());
		$query="INSERT INTO `glpi_dropdown_plugin_example` (`ID`, `name`, `comments`) VALUES
			(1, 'dp 1', 'comment 1'),
			(2, 'dp2', 'comment 2');";
		$DB->query($query) or die("error populate glpi_dropdown_plugin_example". $DB->error());

	}
	return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_example_uninstall(){
	global $DB;

	if (TableExists("glpi_plugin_example")){
		$query="DROP TABLE `glpi_plugin_example`;";
		$DB->query($query) or die("error creating glpi_plugin_example");
	}
	if (TableExists("glpi_dropdown_plugin_example")){

		$query="DROP TABLE `glpi_dropdown_plugin_example`;";
		$DB->query($query) or die("error creating glpi_dropdown_plugin_example");
	}
	return true;
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_example_check_prerequisites(){
	if (GLPI_VERSION>=0.72){
		return true;
	} else {
		echo "GLPI version not compatible need 0.72";
	}
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_example_check_config(){
	return true;
}

// Define rights for the plugin types
function plugin_example_haveTypeRight($type,$right){
	switch ($type){
		case PLUGIN_EXAMPLE_TYPE :
			// 1 - All rights for all users
			// return true;
			// 2 - Similarity right : same right of computer
			return haveRight("computer",$right);
			break;
	}
}


?>
