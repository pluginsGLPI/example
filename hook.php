<?php
/*
 * @version $Id$
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

// Class of the defined type
class pluginExample extends CommonDBTM {
	function __construct () {
		$this->table="glpi_plugin_example";
		$this->type=PLUGIN_EXAMPLE_TYPE;
	}

   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common']="Header Needed";

      $tab[1]['table']='glpi_plugin_example';
      $tab[1]['field']='name';
      $tab[1]['linkfield']='name';
      $tab[1]['name']=$LANG['plugin_example']["name"];

      $tab[2]['table']='glpi_plugin_example_dropdown';
      $tab[2]['field']='name';
      $tab[2]['linkfield']='plugin_example_dropdown_id';
      $tab[2]['name']='Dropdown';

      $tab[3]['table']='glpi_plugin_example';
      $tab[3]['field']='serial';
      $tab[3]['linkfield']='serial';
      $tab[3]['name']='Serial';
      $tab[3]['usehaving']=true;

      $tab[30]['table']='glpi_plugin_example';
      $tab[30]['field']='id';
      $tab[30]['linkfield']='';
      $tab[30]['name']=$LANG["common"][2];

      return $tab;
   }
}

// Class for a Dropdown
class pluginExampleDropdown extends CommonDropdown {
   function __construct () {
      $this->table="glpi_plugin_example_dropdown";
      $this->type=PLUGIN_EXAMPLEDROPDOWN_TYPE;
   }

   static function getTypeName() {
      global $LANG;

      return "Plugin Example Dropdown";
   }
}

// Hook called on profile change
// Good place to evaluate the user right on this plugin
// And to save it in the session
function plugin_change_profile_example() {

	// For example : same right of computer
	if (haveRight('computer','w')) {
		$_SESSION["glpi_plugin_example_profile"]=array('example'=>'w');

	} else if (haveRight('computer','r')) {
		$_SESSION["glpi_plugin_example_profile"]=array('example'=>'r');

	} else {
		unset($_SESSION["glpi_plugin_example_profile"]);
	}

}

// Define dropdown relations
function plugin_example_getDatabaseRelations(){
	//
	return array("glpi_plugin_example_dropdown"=>array("glpi_plugin_example"=>"plugin_example_dropdown_id"));
}


// Define Dropdown tables to be manage in GLPI :
function plugin_example_getDropdown(){
	// Table => Name
	return array(PLUGIN_EXAMPLEDROPDOWN_TYPE => "Plugin Example Dropdown");
}

////// SEARCH FUNCTIONS ///////(){

// Define Additionnal search options for types (other than the plugin ones)
function plugin_example_getAddSearchOptions($itemtype){
	global $LANG;

	$sopt=array();
   if ($itemtype == COMPUTER_TYPE) {
         // Just for example, not working...
         $sopt[1001]['table']     = 'glpi_plugin_example_dropdown';
         $sopt[1001]['field']     = 'name';
         $sopt[1001]['linkfield'] = 'plugin_example_dropdown_id';
         $sopt[1001]['name']      = 'Example plugin';
   }

	return $sopt;
}

function plugin_example_giveItem($type,$ID,$data,$num){
	global $CFG_GLPI, $INFOFORM_PAGES;

	$searchopt=getSearchOptions($type);
  
	$table=$searchopt[$ID]["table"];
	$field=$searchopt[$ID]["field"];

	switch ($table.'.'.$field){
		case "glpi_plugin_example.name" :
			$out= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[$type]."?ID=".$data['id']."\">";
			$out.= $data["ITEM_$num"];
			if ($_SESSION["glpiis_ids_visible"]||empty($data["ITEM_$num"])) $out.= " (".$data["id"].")";
			$out.= "</a>";
			return $out;
			break;
	}
	return "";
}

function plugin_example_addLeftJoin($type,$ref_table,$new_table,$linkfield){

	// Example of standard LEFT JOIN  clause but use it ONLY for specific LEFT JOIN
	// No need of the function if you do not have specific cases
	switch ($new_table){
		case "glpi_plugin_example_dropdown" :
			return " LEFT JOIN $new_table ON ($ref_table.$linkfield = $new_table.id) ";
			break;
	}
	return "";
}



function plugin_example_forceGroupBy($type){
	switch ($type){
		case PLUGIN_EXAMPLE_TYPE :
                        // Force add GROUP BY IN REQUEST
			return true;
			break;
	}
	return false;
}

function plugin_example_addWhere($link,$nott,$type,$ID,$val){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	$SEARCH=makeTextSearch($val,$nott);

	// Example of standard Where clause but use it ONLY for specific Where
	// No need of the function if you do not have specific cases
//	switch ($table.".".$field){
//		case "glpi_plugin_example.name" :
//			$ADD="";
//			if ($nott&&$val!="NULL") {
//				$ADD=" OR $table.$field IS NULL";
//			}
//			return $link." ($table.$field $SEARCH ".$ADD." ) ";
//			break;
//	}
	return "";
}

// This is not a real example because the use of Having condition in this case is not suitable
function plugin_example_addHaving($link,$nott,$type,$ID,$val,$num){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	$SEARCH=makeTextSearch($val,$nott);

	// Example of standard Having clause but use it ONLY for specific Having
	// No need of the function if you do not have specific cases
	switch ($table.".".$field){
		case "glpi_plugin_example.serial" :
			$ADD="";
			if (($nott&&$val!="NULL")||$val=='^$') {
				$ADD=" OR ITEM_$num IS NULL";
			}

			return " $LINK ( ITEM_".$num.$SEARCH." $ADD ) ";
			break;
	}


	return "";
}

function plugin_example_addSelect($type,$ID,$num){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

// Example of standard Select clause but use it ONLY for specific Select
// No need of the function if you do not have specific cases
//	switch ($table.".".$field){
//		case "glpi_plugin_example.name" :
//			return $table.".".$field." AS ITEM_$num, ";
//			break;
//	}
	return "";
}

function plugin_example_addOrderBy($type,$ID,$order,$key=0){
	global $SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

// Example of standard OrderBy clause but use it ONLY for specific order by
// No need of the function if you do not have specific cases
//	switch ($table.".".$field){
//		case "glpi_plugin_example.name" :
//			return " ORDER BY $table.$field $order ";
//			break;
//	}
	return "";
}
//////////////////////////////
////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

// Define actions :
function plugin_example_MassiveActions($type){
	global $LANG;
	switch ($type){
		// New action for core and other plugin types : name = plugin_PLUGINNAME_actionname
		case COMPUTER_TYPE :
			return array(
				"plugin_example_DoIt"=>"plugin_example_DoIt",
			);
			break;

		// Actions for types provided by the plugin
		case PLUGIN_EXAMPLE_TYPE :
			return array(
				// GLPI core one
				"add_document"=>$LANG["document"][16],
				// Specific one
				"do_nothing"=>'Do Nothing - just for fun'
				);
		break;
	}
	return array();
}

// How to display specific actions ?
function plugin_example_MassiveActionsDisplay($type,$action){
	global $LANG;
	switch ($type){
		case COMPUTER_TYPE:
			switch ($action){
				case "plugin_example_DoIt":
				echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG["buttons"][2]."\" >&nbsp;but do nothing :)";
				break;
			}
			break;
		case PLUGIN_EXAMPLE_TYPE:
			switch ($action){
				// No case for add_document : use GLPI core one
				case "do_nothing":
					echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG["buttons"][2]."\" >&nbsp;but do nothing :)";
				break;
			}
		break;
	}
	return "";
}

// How to process specific actions ?
function plugin_example_MassiveActionsProcess($data){
	global $LANG;


	switch ($data['action']){
		case 'plugin_example_DoIt':
			if ($data['device_type']==COMPUTER_TYPE){
				$ci =new CommonItem();
				addMessageAfterRedirect("Right it is the type I want...");
				addMessageAfterRedirect("But... I say I will do nothing for :");
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($ci->getFromDB($data["device_type"],$key)){
						addMessageAfterRedirect("- ".$ci->getField("name"));
						}
					}
				}
			}
			break;
		case 'do_nothing':
			if ($data['device_type']==PLUGIN_EXAMPLE_TYPE){
				$ci =new CommonItem();
				addMessageAfterRedirect("Right it is the type I want...");
				addMessageAfterRedirect("But... I say I will do nothing for :");
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						if ($ci->getFromDB($data["device_type"],$key)){
							addMessageAfterRedirect("- ".$ci->getField("name"));
						}
					}
				}
			}
		break;
	}
}
// How to display specific update fields ?
function plugin_example_MassiveActionsFieldsDisplay($type,$table,$field,$linkfield){
	global $LINK_ID_TABLE;
	if ($table==$LINK_ID_TABLE[$type]){
		// Table fields
		switch ($table.".".$field){
			case 'glpi_plugin_example.serial':
				echo "Not really specific - Just for example&nbsp;";
				autocompletionTextField($linkfield,$table,$field);
				// dropdownYesNo($linkfield);
				// Need to return true if specific display
				return true;
			break;
		}

	} else {
		// Linked Fields
		switch ($table.".".$field){
			case "glpi_plugin_example_dropdown.name" :
				echo "Not really specific - Just for example&nbsp;";
				dropdown($table,$linkfield,1,$_SESSION["glpiactive_entity"]);
				//dropdownUsers($linkfield,0,"own_ticket",0,1,$_SESSION["glpiactive_entity"]);
 				// Need to return true if specific display
				return true;
				break;
		}
	}
	// Need to return false on non display item
	return false;
}

//////////////////////////////

// Hook done on before update item case
function plugin_pre_item_update_example($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case COMPUTER_TYPE :
				// Manipulate data if needed
				addMessageAfterRedirect("Pre Update Computer Hook",true);
				break;
		}
	return $input;
}


// Hook done on update item case
function plugin_item_update_example($parm){

	if (isset($parm["type"]))
		switch ($parm["type"]){
			case COMPUTER_TYPE :
				addMessageAfterRedirect("Update Computer Hook",true);
				return true;
				break;
		}
	return false;
}

// Hook done on before add item case
function plugin_pre_item_add_example($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case COMPUTER_TYPE :
				// Manipulate data if needed
				addMessageAfterRedirect("Pre Add Computer Hook",true);
				break;
		}
	return $input;
}

// Hook done on add item case
function plugin_item_add_example($parm){

	if (isset($parm["type"]))
		switch ($parm["type"]){
			case COMPUTER_TYPE :
				addMessageAfterRedirect("Add Computer Hook",true);
				return true;
				break;
		}
	return false;
}

// Hook done on before delete item case
function plugin_pre_item_delete_example($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case COMPUTER_TYPE :
				// Manipulate data if needed
				addMessageAfterRedirect("Pre Delete Computer Hook",true);
				break;
		}
	return $input;
}
// Hook done on delete item case
function plugin_item_delete_example($parm){

	if (isset($parm["type"]))
		switch ($parm["type"]){
			case COMPUTER_TYPE :
				addMessageAfterRedirect("Delete Computer Hook",true);
				return true;
				break;
		}
	return false;
}

// Hook done on before purge item case
function plugin_pre_item_purge_example($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case COMPUTER_TYPE :
				// Manipulate data if needed
				addMessageAfterRedirect("Pre Purge Computer Hook",true);
				break;
		}
	return $input;
}
// Hook done on purge item case
function plugin_item_purge_example($parm){

	if (isset($parm["type"]))
		switch ($parm["type"]){
			case COMPUTER_TYPE :
				addMessageAfterRedirect("Purge Computer Hook",true);
				return true;
				break;
		}
	return false;
}

// Hook done on before restore item case
function plugin_pre_item_restore_example($input){
	if (isset($input["_item_type_"]))
		switch ($input["_item_type_"]){
			case COMPUTER_TYPE :
				// Manipulate data if needed
				addMessageAfterRedirect("Pre Restore Computer Hook");
				break;
		}
	return $input;
}
// Hook done on restore item case
function plugin_item_restore_example($parm){

	if (isset($parm["type"]))
		switch ($parm["type"]){
			case COMPUTER_TYPE :
				addMessageAfterRedirect("Restore Computer Hook");
				return true;
				break;
		}
	return false;
}

// Hook done on restore item case
function plugin_item_transfer_example($parm){

	addMessageAfterRedirect("Transfer Computer Hook ".$parm['type']." ".$parm['id']." -> ".$parm['newID']);

	return false;
}

// Parm contains begin, end and who
// Create data to be displayed in the planning of $parm["who"] or $parm["who_group"] between $parm["begin"] and $parm["end"]

function plugin_planning_populate_example($parm){

	// Add items in the items fields of the parm array
	// Items need to have an unique index beginning by the begin date of the item to display
	// needed to be correcly displayed


	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["plugin"]="example";
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["begin"]=date("Y-m-d 17:00:00");
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["end"]=date("Y-m-d 18:00:00");
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["name"]="test planning example 1 ";
	// Set the ID using the ID of the item in the database to have unique ID
	$ID=date("Ymd"); // Current date for example
	$parm["items"][$parm["begin"]."$$$"."plugin_example1"]["planningID"]="plugin_example".$ID;

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
			echo $LANG["buttons"][33]." ".date("H:i",strtotime($parm["begin"])).": ";
			break;
		case "end";
			echo $LANG["buttons"][32]." ".date("H:i",strtotime($parm["end"])).": ";
			break;
	}
	echo $parm["name"];
}

// Define headings added by the plugin
function plugin_get_headings_example($type,$ID,$withtemplate){
	switch ($type){
		case PROFILE_TYPE:
			$prof = new Profile();
			if ($ID>0 && $prof->getFromDB($ID) && $prof->fields['interface']=='central') {
				return array(
						1 => "Test PLugin",
					    );
			} else {
				return array();
			}
			break;
		case COMPUTER_TYPE :
			// new object / template case
			if ($withtemplate) {
				return array();
			// Non template case / editing an existing object
			} else {
				return array(
						1 => "Test PLugin",
					    );
			}
			break;
		case COMPUTERDISK_TYPE :
		case ENTERPRISE_TYPE :
			if ($ID>0) { // Not in create mode
				return array(
						1 => "Test PLugin",
						2 => "Test PLugin 2",
					    );
			}
			break;
		case "central":
			return array(
				1 => "Test PLugin",
			);
			break;
		case "prefs":
			return array(
				1 => "Test PLugin",
			);
			break;
		case "mailing":
			return array(
				1 => "Test PLugin",
			);
			break;

	}
	return false;
}

// Define headings actions added by the plugin
function plugin_headings_actions_example($type){

	switch ($type){
		case PROFILE_TYPE :
		case COMPUTER_TYPE :
			return array(
					1 => "plugin_headings_example",
				    );

			break;
		case COMPUTERDISK_TYPE :
		case ENTERPRISE_TYPE :
			return array(
					1 => "plugin_headings_example",
					2 => "plugin_headings_example",
				    );

			break;
		case "central" :
			return array(
					1 => "plugin_headings_example",
				    );
			break;
		case "prefs" :
			return array(
					1 => "plugin_headings_example",
				    );
			break;
		case "mailing" :
			return array(
					1 => "plugin_headings_example",
				    );
			break;

	}
	return false;
}

// Example of an action heading
function plugin_headings_example($type,$ID,$withtemplate=0){
	global $LANG;
	if (!$withtemplate){
		echo "<div align='center'>";
		switch ($type){
			case "central":
				echo "Plugin central action ".$LANG['plugin_example']["test"];
			break;
			case "prefs":
				// Complete form display

				$data=plugin_version_example();

				echo "<form action='Where to post form'>";
				echo "<table class='tab_cadre_fixe'>";
					echo "<tr><th colspan='3'>".$data['name'];
					echo " - ".$data['version'];
					echo "</th></tr>";

					echo "<tr class='tab_bg_1'><td>Name of the pref";
					echo "</td><td>Input to set the pref</td>";

					echo "<td><input class='submit' type='submit' name='submit' value='submit'></td>";
					echo "</tr>";

				echo "</table>";
				echo "</form>";
			break;
			case "mailing":
				echo "Plugin mailing action ".$LANG['plugin_example']["test"];
			break;
			default :
				echo "Plugin function with headings TYPE=".$type." ID=".$ID;
			break;
		}
		echo "</div>";
	}
}

/**
 * Execute 1 task manage by the plugin
 *
 * @param $task Object of CronTask class for log / stat
 *
 * @return interger
 *    >0 : done
 *    <0 : to be run again (not finished)
 *     0 : nothing to do
 */
function plugin_example_cron_sample_run($task) {
   $task->log("Example log message");
   $task->setVolume(mt_rand(0,$task->fields['param']));

   return 1;
}

/**
 * Give localized information about 1 task
 *
 * @param $name of the task
 *
 * @return array of strings
 */
function plugin_example_cron_info($name) {
   global $LANG;

   switch ($name) {
      case 'sample':
         return array (
            'description' => $LANG['plugin_example']['test'],  // Mandatory
            'parameter' => $LANG['plugin_example']['test']);   // Optional
         break;
   }
   return array();
}


// Do special actions for dynamic report
function plugin_example_dynamicReport($parm){
	if ($parm["item_type"]==PLUGIN_EXAMPLE_TYPE){
		// Do all what you want for export depending on $parm
		echo "Personalized export for type ".$parm["display_type"];
		echo 'with additional datas : <br>';
		echo "Single data : add1 <br>";
		print $parm['add1'].'<br>';
		echo "Array data : add2 <br>";
		printCleanArray($parm['add2']);
		// Return true if personalized display is done
		return true;
	}
	// Return false if no specific display is done, then use standard display
	return false;
}

// Add parameters to printPager in search system
function plugin_example_addParamFordynamicReport($device_type){
	if ($device_type==PLUGIN_EXAMPLE_TYPE){
		// Return array data containing all params to add : may be single data or array data
		// Search config are available from session variable
		return array(
			'add1' => $_SESSION['glpisearch'][$device_type]['order'],
			'add2' => array('tutu'=>'Second Add','Other Data'));
	}
	// Return false or a non array data if not needed
	return false;
}

// Install process for plugin : need to return true if succeeded
function plugin_example_install(){
	global $DB;


	if (!TableExists("glpi_plugin_example")){
		$query="CREATE TABLE `glpi_plugin_example` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`serial` varchar(255) collate utf8_unicode_ci NOT NULL,
			`plugin_example_dropdown_id` int(11) NOT NULL default '0',
			`is_deleted` smallint(6) NOT NULL default '0',
			`is_template` smallint(6) NOT NULL default '0',
			`template_name` varchar(255) collate utf8_unicode_ci default NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			";
		$DB->query($query) or die("error creating glpi_plugin_example ". $DB->error());
		$query="INSERT INTO `glpi_plugin_example` (`id`, `name`, `serial`, `plugin_example_dropdown_id`,
             `is_deleted`, `is_template`, `template_name`) VALUES
			(1, 'example 1', 'serial 1', 1, 0, 0, NULL),
			(2, 'example 2', 'serial 2', 2, 0, 0, NULL),
			(3, 'example 3', 'serial 3', 1, 0, 0, NULL);";
		$DB->query($query) or die("error populate glpi_plugin_example ". $DB->error());
	}
	if (!TableExists("glpi_plugin_example_dropdown")){

		$query="CREATE TABLE `glpi_plugin_example_dropdown` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`comment` text collate utf8_unicode_ci,
			PRIMARY KEY  (`id`),
			KEY `name` (`name`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		$DB->query($query) or die("error creating glpi_plugin_example_dropdown". $DB->error());
		$query="INSERT INTO `glpi_plugin_example_dropdown` (`id`, `name`, `comment`) VALUES
			(1, 'dp 1', 'comment 1'),
			(2, 'dp2', 'comment 2');";
		$DB->query($query) or die("error populate glpi_plugin_example_dropdown". $DB->error());

	}

   // To be called for each task the plugin manage
   CronTask::Register('example', 'sample', DAY_TIMESTAMP, array('param'=>50));
	return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_example_uninstall(){
	global $DB;

	if (TableExists("glpi_plugin_example")){
		$query="DROP TABLE `glpi_plugin_example`;";
		$DB->query($query) or die("error deleting glpi_plugin_example");
	}
	if (TableExists("glpi_dropdown_plugin_example")){

		$query="DROP TABLE `glpi_dropdown_plugin_example`;";
		$DB->query($query) or die("error deleting glpi_dropdown_plugin_example");
	}
   if (TableExists("glpi_plugin_example_dropdown")){

      $query="DROP TABLE `glpi_plugin_example_dropdown`;";
      $DB->query($query) or die("error deleting glpi_plugin_example_dropdown");
   }
	return true;
}

function plugin_example_AssignToTicket($types)
{
	$types[PLUGIN_EXAMPLE_TYPE] = "Example";
	return $types;
}

?>
