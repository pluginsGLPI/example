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

	// Params : plugin name - string type - ID - Array of attributes
	registerPluginType('example', 'PLUGIN_EXAMPLE_TYPE', 1001, array(
		'classname'  => 'pluginExample',
		'tablename'  => 'glpi_plugin_example',
		'formpage'   => 'example.form.php',
		'searchpage' => 'index.php',
		'typename'   => 'Example Type',
		'deleted_tables' => false,
		'template_tables' => false,
		'specif_entities_tables' => false,
		'recursive_type' => false,
		//'linkuser_types' => true,
		//'linkgroup_types' => true
		));

	// Display a menu entry ?
	if (plugin_example_haveTypeRight(PLUGIN_EXAMPLE_TYPE,'r')) { // Right set in change_profile hook
		$PLUGIN_HOOKS['menu_entry']['example'] = true;
		$PLUGIN_HOOKS['submenu_entry']['example']['add'] = 'example.form.php';
		$PLUGIN_HOOKS['submenu_entry']['example']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANG['plugin_example']["test"]."' alt='".$LANG['plugin_example']["test"]."'>"] = 'index.php';
		$PLUGIN_HOOKS['submenu_entry']['example'][$LANG['plugin_example']["test"]] = 'index.php';
		$PLUGIN_HOOKS['submenu_entry']['example']['config'] = 'index.php';

		$PLUGIN_HOOKS["helpdesk_menu_entry"]['example'] = true;
	}

	// Config page
	if (haveRight('config','w')) {
		$PLUGIN_HOOKS['config_page']['example'] = 'config.php';
	}

	// Init session
	//$PLUGIN_HOOKS['init_session']['example'] = 'plugin_init_session_example';
	// Change profile
	$PLUGIN_HOOKS['change_profile']['example'] = 'plugin_change_profile_example';
	// Change entity
	//$PLUGIN_HOOKS['change_entity']['example'] = 'plugin_change_entity_example';


	// Onglets management
	$PLUGIN_HOOKS['headings']['example'] = 'plugin_get_headings_example';
	$PLUGIN_HOOKS['headings_action']['example'] = 'plugin_headings_actions_example';

	// Item action event // See define.php for defined ITEM_TYPE
	$PLUGIN_HOOKS['pre_item_update']['example'] = 'plugin_pre_item_update_example';
	$PLUGIN_HOOKS['item_update']['example'] = 'plugin_item_update_example';

	$PLUGIN_HOOKS['pre_item_add']['example'] = 'plugin_pre_item_add_example';
	$PLUGIN_HOOKS['item_add']['example'] = 'plugin_item_add_example';

	$PLUGIN_HOOKS['pre_item_delete']['example'] = 'plugin_pre_item_delete_example';
	$PLUGIN_HOOKS['item_delete']['example'] = 'plugin_item_delete_example';

	$PLUGIN_HOOKS['pre_item_purge']['example'] = 'plugin_pre_item_purge_example';
	$PLUGIN_HOOKS['item_purge']['example'] = 'plugin_item_purge_example';

	$PLUGIN_HOOKS['pre_item_restore']['example'] = 'plugin_pre_item_restore_example';
	$PLUGIN_HOOKS['item_restore']['example'] = 'plugin_item_restore_example';

	$PLUGIN_HOOKS['item_transfer']['example'] = 'plugin_item_transfer_example';

	//redirect appel http://localhost/glpi/index.php?redirect=plugin_example_2 (ID 2 du form)
	$PLUGIN_HOOKS['redirect_page']['example']="example.form.php";

	//function to populate planning
	$PLUGIN_HOOKS['planning_populate']['example']="plugin_planning_populate_example";

	//function to display planning items
	$PLUGIN_HOOKS['display_planning']['example']="plugin_display_planning_example";

	// Massive Action definition
	$PLUGIN_HOOKS['use_massive_action']['example']=1;

	$PLUGIN_HOOKS['assign_to_ticket']['example']=1;

	// Add specific files to add to the header : javascript or css
	$PLUGIN_HOOKS['add_javascript']['example']="example.js";
	$PLUGIN_HOOKS['add_css']['example']="example.css";

	// Retrieve others datas from LDAP
	//$PLUGIN_HOOKS['retrieve_more_data_from_ldap']['example']="plugin_retrieve_more_data_from_ldap_example";

	// Reports
	$PLUGIN_HOOKS['reports']['example'] = array('report.php'=>'New Report', 'report.php?other'=>'New Report 2',);

	// Stats
	$PLUGIN_HOOKS['stats']['example'] = array('stat.php'=>'New stat', 'stat.php?other'=>'New stats 2',);

}


// Get the name and the version of the plugin - Needed
function plugin_version_example(){
	return array(
		'name'    => 'Plugin Example',
		'version' => '0.2.0',
		'author' => 'Julien Dombre',
		'homepage'=> 'http://glpi-project.org',
		'minGlpiVersion' => '0.80',// For compatibility / no install in version < 0.72
	);
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_example_check_prerequisites(){
	if (GLPI_VERSION>=0.80){
		return true;
	} else {
		echo "GLPI version not compatible need 0.80";
	}
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_example_check_config($verbose=false){
	global $LANG;

	if (true) { // Your configuration check
		return true;
	}
	if ($verbose) {
		echo $LANG['plugins'][2];
	}
	return false;
}

// Define rights for the plugin types
function plugin_example_haveTypeRight($type,$right){

	if (!isset($_SESSION["glpi_plugin_example_profile"])) {
		// No right
		return false;
	}
	switch ($type){
		case PLUGIN_EXAMPLE_TYPE :
			// Evaluate the right from data saved in session by change_profile hook
			return ($right=='r' || $_SESSION["glpi_plugin_example_profile"]=='w');
			break;
	}
}


?>
