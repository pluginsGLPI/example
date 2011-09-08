<?php
/*
 * @version $Id: HEADER 10411 2010-02-09 07:58:26Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
class PluginExampleExample extends CommonDBTM {


   // Should return the localized name of the type
   static function getTypeName() {
      return 'Example Type';
   }


   function canCreate() {

      if (isset($_SESSION["glpi_plugin_example_profile"])) {
         return ($_SESSION["glpi_plugin_example_profile"]['example'] == 'w');
      }
      return false;
   }


   function canView() {

      if (isset($_SESSION["glpi_plugin_example_profile"])) {
         return ($_SESSION["glpi_plugin_example_profile"]['example'] == 'w'
                 || $_SESSION["glpi_plugin_example_profile"]['example'] == 'r');
      }
      return false;
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = "Header Needed";

      $tab[1]['table']     = 'glpi_plugin_example_examples';
      $tab[1]['field']     = 'name';
      $tab[1]['name']      = $LANG['plugin_example']["name"];

      $tab[2]['table']     = 'glpi_plugin_example_dropdowns';
      $tab[2]['field']     = 'name';
      $tab[2]['name']      = 'Dropdown';

      $tab[3]['table']     = 'glpi_plugin_example_examples';
      $tab[3]['field']     = 'serial';
      $tab[3]['name']      = 'Serial';
      $tab[3]['usehaving'] = true;
      $tab[3]['searchtype'] = 'equals';

      $tab[30]['table']     = 'glpi_plugin_example_examples';
      $tab[30]['field']     = 'id';
      $tab[30]['name']      = $LANG["common"][2];

      return $tab;
   }


   /**
    * Give localized information about 1 task
    *
    * @param $name of the task
    *
    * @return array of strings
    */
   static function cronInfo($name) {
      global $LANG;

      switch ($name) {
         case 'Sample' :
            return array('description' => $LANG['plugin_example']['test']." (class)",
                         'parameter'   => $LANG['plugin_example']['test']);
      }
      return array();
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
   static function cronSample($task) {

      $task->log("Example log message from class");
      $task->setVolume(mt_rand(0,$task->fields['param']));

      return 1;
   }


   // Hook done on before add item case
   static function pre_item_add_example($item) {
      Session::addMessageAfterRedirect("Pre Add Computer Hook", true);
   }


   // Hook done on add item case
   static function item_add_example($item) {

      Session::addMessageAfterRedirect("Add Computer Hook, ID=".$item->getField('id'), true);
      return true;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if (!$withtemplate) {
         switch ($item->getType()) {
            case 'Phone' :
               if ($_SESSION['glpishow_count_on_tabs']) {
                  return self::createTabEntry('Example',
                                              countElementsInTable($this->getTable()));
               }
               return 'Example';
         }
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='Phone') {
         echo "Plugin Example on Phone";
      }
      return true;
   }
}

?>
