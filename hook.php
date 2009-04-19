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
	function pluginExample () {
		$this->table="glpi_plugin_example";
		$this->type=PLUGIN_EXAMPLE_TYPE;
	}
};

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
	global $LANG;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_EXAMPLE_TYPE]['common']="Header Needed";

	$sopt[PLUGIN_EXAMPLE_TYPE][1]['table']='glpi_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['field']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][1]['name']=$LANG['plugin_example']["name"];

	$sopt[PLUGIN_EXAMPLE_TYPE][2]['table']='glpi_dropdown_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['field']='name';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['linkfield']='FK_dropdown';
	$sopt[PLUGIN_EXAMPLE_TYPE][2]['name']='Dropdown';

	$sopt[PLUGIN_EXAMPLE_TYPE][3]['table']='glpi_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][3]['field']='serial';
	$sopt[PLUGIN_EXAMPLE_TYPE][3]['linkfield']='serial';
	$sopt[PLUGIN_EXAMPLE_TYPE][3]['name']='Serial';
	$sopt[PLUGIN_EXAMPLE_TYPE][3]['usehaving']=true;
	
	$sopt[PLUGIN_EXAMPLE_TYPE][30]['table']='glpi_plugin_example';
	$sopt[PLUGIN_EXAMPLE_TYPE][30]['field']='ID';
	$sopt[PLUGIN_EXAMPLE_TYPE][30]['linkfield']='';
	$sopt[PLUGIN_EXAMPLE_TYPE][30]['name']=$LANG["common"][2];
	
	return $sopt;
}

function plugin_example_giveItem($type,$ID,$data,$num){
	global $CFG_GLPI, $INFOFORM_PAGES,$SEARCH_OPTION;

	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];

	switch ($table.'.'.$field){
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

function plugin_example_addLeftJoin($type,$ref_table,$new_table,$linkfield){

	// Example of standard LEFT JOIN  clause but use it ONLY for specific LEFT JOIN
	// No need of the function if you do not have specific cases
	switch ($new_table){
		case "glpi_dropdown_plugin_example" :
			return " LEFT JOIN $new_table ON ($ref_table.$linkfield = $new_table.ID) ";
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
			case "glpi_dropdown_plugin_example.name" :
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
	
	addMessageAfterRedirect("Transfer Computer Hook ".$parm['type']." ".$parm['ID']." -> ".$parm['newID']);
	
	return false;
}

// Parm contains begin, end and who
// Create data to be displayed in the planning of $parm["who"] or $parm["who_group"] between $parm["begin"] and $parm["end"] 
function plugin_planning_populate_example($parm){

	// Add items in the items fields of the parm array
	// Items need to have an unique index beginning by the begin date of the item to display
	// needed to be correcly displayed

	list($date,$time)=explode(" ",$parm["begin"]);
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
		case ENTERPRISE_TYPE :
			return array(
					1 => "Test PLugin",
					2 => "Test PLugin 2",
				    );
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


// Cron function : name= cron_plugin_PLUGINNAME
function cron_plugin_example(){
	echo "tttt";
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

function plugin_example_AssignToTicket($types)
{
	$types[PLUGIN_EXAMPLE_TYPE] = "Example";
	return $types;
}

function plugin_example_AssignToTicketList()
{
	
}
?>
