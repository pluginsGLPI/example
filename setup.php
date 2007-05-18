<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2006 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org
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
	global $PLUGIN_HOOKS,$LANGEXAMPLE,$LANG,$CFG_GLPI;

	// Display a menu entry ?
	$PLUGIN_HOOKS['menu_entry']['example'] = true;
	$PLUGIN_HOOKS['submenu_entry']['example']['add'] = 'example.form.php';
	$PLUGIN_HOOKS['submenu_entry']['example']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".$LANGEXAMPLE["test"]."' alt='".$LANGEXAMPLE["test"]."'>"] = 'index.php';
	$PLUGIN_HOOKS['submenu_entry']['example'][$LANGEXAMPLE["test"]] = 'index.php';

	$PLUGIN_HOOKS["helpdesk_menu_entry"]['example'] = true;

	// Config page
	$PLUGIN_HOOKS['config_page']['example'] = 'config.php';

	// Onglets management
	$PLUGIN_HOOKS['headings']['example'] = 'plugin_get_headings_example';
	$PLUGIN_HOOKS['headings_action']['example'] = 'plugin_headings_actions_example';
	// Display on central page
	$PLUGIN_HOOKS['central_action']['example'] = 'plugin_central_action_example';

	// Item action event // See config.php for defined ITEM_TYPE
	$PLUGIN_HOOKS['item_update']['example'] = 'plugin_item_update_example';
	$PLUGIN_HOOKS['item_add']['example'] = 'plugin_item_add_example';
	$PLUGIN_HOOKS['item_delete']['example'] = 'plugin_item_delete_example';
	$PLUGIN_HOOKS['item_purge']['example'] = 'plugin_item_purge_example';
	$PLUGIN_HOOKS['item_restore']['example'] = 'plugin_item_restore_example';

	// Cron action
	$PLUGIN_HOOKS['cron']['example'] = DAY_TIMESTAMP;
	//redirect appel http://localhost/glpi/index.php?redirect=plugin_example_2 (ID 2 du form)
	$PLUGIN_HOOKS['redirect_page']['example']="example.form.php";
	//function to populate planning
	$PLUGIN_HOOKS['planning_populate']['example']="plugin_planning_populate_example";
	//function to populate planning
	$PLUGIN_HOOKS['display_planning']['example']="plugin_display_planning_example";

	// Add specific files to add to the header : javascript or css
	$PLUGIN_HOOKS['add_javascript']['example']="example.js";
	$PLUGIN_HOOKS['add_css']['example']="example.css";

	// Params : plugin name - string type - number - tabke - form page
	pluginNewType('example',"PLUGIN_EXAMPLE_TYPE",1001,"glpi_plugin_example","example.form.php");

}


// Get the name and the version of the plugin - Needed
function plugin_version_example(){
	return array( 'name'    => 'Plugin Example',
			'version' => '0.0.1');
}
// Define dropdown relations
function plugin_example_getDatabaseRelations(){
	// 
	return array("glpi_dropdown_plugin_example"=>array("glpi_plugin_example"=>"FK_dropdown"));
}

// Define Dropdown tables to be manage in GLPI :
function plugin_example_getDropdown(){
	// Table => Name
	return array("glpi_dropdown_plugin_example"=>"Plugin Example Dropdown");
}

////// SEARCH FUNCTIONS ///////(){

// Define search option for types of the plugins
function plugin_example_getSearchOption(){
	global $LANGEXAMPLE;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_EXAMPLE_TYPE]['common']="Header Needed";

	$sopt[PLUGIN_EXAMPLE_TYPE][1]['table']='glpi_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['field']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['name']=$LANGEXAMPLE["name"];

	$sopt[PLUGIN_EXAMPLE_TYPE][2]['table']='glpi_dropdown_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['field']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['linkfield']='FK_dropdown';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['name']='Dropdown';
	
	return $sopt;
}


function plugin_example_addLeftJoin($type,$ref_table,$new_table,$linkfield){
	switch ($new_table){
		case "glpi_dropdown_plugin_example" :
			// Standard LEFT JOIN for the example but use it for specific jointures
			return " LEFT JOIN $new_table ON ($ref_table.$linkfield = $new_table.ID) ";
			break;
	}
	return "";
}

function plugin_example_giveItem($type,$field,$data,$num,$linkfield=""){
	global $CFG_GLPI, $INFOFORM_PAGES;

	switch ($field){
		case "glpi_plugin_example.name" :
			$out= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['ID']."\">";
			$out.= $data["ITEM_$num"];
			if ($CFG_GLPI["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ID"].")";
			$out.= "</a>";
			return $out;
			break;
	}
	return "";
}

function plugin_example_addWhere($link,$nott,$type,$ID,$val){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];
	
	$SEARCH=makeTextSearch($val,$nott);

	switch ($table.".".$field){
		case "glpi_plugin_example.name" :
			// Standard Where clause for the example but use it for specific jointures
			$ADD="";	
			if ($nott&&$val!="NULL") {
				$ADD=" OR $table.$field IS NULL";
			}
			
			return $link." ($table.$field $SEARCH ".$ADD." ) ";
			break;
	}
	return "";
}

function plugin_example_addSelect($type,$ID,$num){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($table.".".$field){
		case "glpi_plugin_example.name" :
			// Standard Select clause for the example but use it for specific selection
			return $table.".".$field." AS ITEM_$num, ";
			break;
	}
	return "";
}

function plugin_example_addOrderBy($type,$ID,$order,$key=0){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($table.".".$field){
		case "glpi_plugin_example.name" :
			// Standard Order By clause for the example but use it for specific selection
			return " ORDER BY $table.$field $order ";
			break;
	}
	return "";
}
//////////////////////////////

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

// Parm contains begin, end and who
// Create data to be displayed in the planning of $parm["who"] between $parm["begin"] and $parm["end"] 
function plugin_planning_populate_example($parm){

	// Add items in the items fields of the parm array
	// Items need to have an unique index beginning by the begin date of the item to display
	// needed to be correcly displayed

	list($date,$time)=split(" ",$parm["begin"]);
	$end=$date." 13:33:00";

	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["plugin"]="example";
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["begin"]=$parm["begin"];
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["end"]=$end;
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["name"]="test planning example 1 ";

	return $parm;
}

// Display the planning item
function plugin_display_planning_example($parm){
	// $parm["type"] say begin end in or from type
	// Add items in the items fields of the parm array
	global $LANG;
	switch ($parm["type"]){
		case "in":
			echo date("H:i",strtotime($parm["begin"]))." -> ".date("H:i",strtotime($parm["end"])).": ";
			break;
		case "from":
			break;
		case "begin";
			echo $LANG["planning"][19]." ".date("H:i",strtotime($parm["begin"])).": ";
			break;
		case "end";
			echo $LANG["planning"][20]." ".date("H:i",strtotime($parm["end"])).": ";
			break;
	}
	echo $parm["name"];
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
	global $LANGEXAMPLE;

	echo "<div align='center'>";
	echo "Plugin central action ".$LANGEXAMPLE["test"];
	echo "</div>";
}

// Cron function : name= cron_plugin_PLUGINNAME
function cron_plugin_example(){
	echo "tttt";
}
?>
