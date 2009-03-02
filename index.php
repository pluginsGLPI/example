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

$NEEDED_ITEMS=array("search");

define('GLPI_ROOT', '../..'); 
include (GLPI_ROOT . "/inc/includes.php"); 

if ($_SESSION["glpiactiveprofile"]["interface"] == "central"){
	commonHeader("TITRE", $_SERVER['PHP_SELF'],"plugins","example");
} else {
	helpHeader("TITRE", $_SERVER['PHP_SELF']);
}

checkTypeRight(PLUGIN_EXAMPLE_TYPE,"r");

manageGetValuesInSearch(PLUGIN_EXAMPLE_TYPE);

searchForm(PLUGIN_EXAMPLE_TYPE,$_GET);

showList(PLUGIN_EXAMPLE_TYPE,$_GET);

commonFooter();
?>

