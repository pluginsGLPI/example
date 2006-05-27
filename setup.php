<?php
/*
 * @version $Id: setup.php 3050 2006-04-04 20:40:10Z moyo $
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2006 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// TODO
// Manage dropdowns : ajout nouveau type, cas de la suppression

// Init the hooks of the plugins -Needed
function plugin_init_example() {
        global $plugin_hooks;
	
	// Display a menu entry ?
	$plugin_hooks['menu_entry']['example'] = true;
	// Setup/Update functions
	$plugin_hooks['setup']['example'] = "plugin_setup_example";
	// Config function
        $plugin_hooks['config']['example'] = 'plugin_config_example';
	// Config page
	$plugin_hooks['config_page']['example'] = 'config.php';
	// Item action event // See config.php for defined ITEM_TYPE
	$plugin_hooks['item_update']['example'] = 'plugin_item_update_example';
	$plugin_hooks['item_add']['example'] = 'plugin_item_add_example';
	$plugin_hooks['item_delete']['example'] = 'plugin_item_delete_example';
	$plugin_hooks['item_purge']['example'] = 'plugin_item_purge_example';
	$plugin_hooks['item_restore']['example'] = 'plugin_item_restore_example';
	// Onglets management
	$plugin_hooks['headings']['example'] = 'plugin_get_headings_example';
	$plugin_hooks['headings_action']['example'] = 'plugin_headings_actions_example';
	// Display on central page
	$plugin_hooks['central_action']['example'] = 'plugin_central_action_example';

}


// Get the name and the version of the plugin - Needed
function plugin_version_example(){
	return array( 'name'    => 'Plugin Example',
                      'version' => '0.0.1');
}

// Get config of the plugin
function plugin_config_example(){
	global $cfg_glpi_plugins;

	$cfg_glpi_plugins["example"]["test"]="test";
}

// Config form od the plugin
function plugin_config_form_example(){

	echo "This is the form config of the plugin";

}

// Hook done on update item case
function plugin_item_update_example($parm){

	if (isset($parm["type"]))
	switch ($parm["type"]){
		case COMPUTER_TYPE :
			if (!empty($_SESSION["MESSAGE_AFTER_REDIRECT"])) $_SESSION["MESSAGE_AFTER_REDIRECT"].="<br>";
			$_SESSION["MESSAGE_AFTER_REDIRECT"].="Update Computer Hook";
			return true;
			break;
	}
	return false;
}

// Hook done on add item case
function plugin_item_add_example($parm){

	if (isset($parm["type"]))
	switch ($parm["type"]){
		case COMPUTER_TYPE :
			if (!empty($_SESSION["MESSAGE_AFTER_REDIRECT"])) $_SESSION["MESSAGE_AFTER_REDIRECT"].="<br>";
			$_SESSION["MESSAGE_AFTER_REDIRECT"].="Add Computer Hook";
			return true;
			break;
	}
	return false;
}

// Hook done on delete item case
function plugin_item_delete_example($parm){

	if (isset($parm["type"]))
	switch ($parm["type"]){
		case COMPUTER_TYPE :
			if (!empty($_SESSION["MESSAGE_AFTER_REDIRECT"])) $_SESSION["MESSAGE_AFTER_REDIRECT"].="<br>";
			$_SESSION["MESSAGE_AFTER_REDIRECT"].="Delete Computer Hook";
			return true;
			break;
	}
	return false;
}

// Hook done on purge item case
function plugin_item_purge_example($parm){

	if (isset($parm["type"]))
	switch ($parm["type"]){
		case COMPUTER_TYPE :
			if (!empty($_SESSION["MESSAGE_AFTER_REDIRECT"])) $_SESSION["MESSAGE_AFTER_REDIRECT"].="<br>";
			$_SESSION["MESSAGE_AFTER_REDIRECT"].="Purge Computer Hook";
			return true;
			break;
	}
	return false;
}

// Hook done on restore item case
function plugin_item_restore_example($parm){

	if (isset($parm["type"]))
	switch ($parm["type"]){
		case COMPUTER_TYPE :
			if (!empty($_SESSION["MESSAGE_AFTER_REDIRECT"])) $_SESSION["MESSAGE_AFTER_REDIRECT"].="<br>";
			$_SESSION["MESSAGE_AFTER_REDIRECT"].="Restore Computer Hook";
			return true;
			break;
	}
	return false;
}

// Define headings added by the plugin
function plugin_get_headings_example($type,$withtemplate){
	switch ($type){
		case COMPUTER_TYPE :
			// template case
			if ($withtemplate)
				return array();
			// Non template case
			else 
				return array(
						1 => "Test PLugin",
					);
			break;
		case ENTERPRISE_TYPE :
			return array(
					1 => "Test PLugin",
					2 => "Test PLugin 2",
				);
			break;

	}
	return false;
}

// Define headings actions added by the plugin	 
function plugin_headings_actions_example($type){
	
	switch ($type){
		case COMPUTER_TYPE :
			return array(
					1 => "plugin_headings_example",
				);
			
			break;
		case ENTERPRISE_TYPE :
			return array(
					1 => "plugin_headings_example",
					2 => "plugin_headings_example",
				);
			
			break;

	}
	return false;
}

// Example of an action heading
function plugin_headings_example($type,$ID,$withtemplate=0){
	if (!$withtemplate){
		echo "<div align='center'>";
		echo "Plugin function with headings TYPE=".$type." ID=".$ID;
		echo "</div>";
	}
}

// Hook to be launch on central
function plugin_central_action_example(){
	global $langexample;
	
	echo "<div align='center'>";
	echo "Plugin central action ".$langexample["test"];
	echo "</div>";
}

?>