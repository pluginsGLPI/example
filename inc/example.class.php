<?php
/*
 * @version $Id: hook.php 104 2009-12-02 18:37:21Z remi $
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
class PluginExampleExample extends CommonDBTM {

   // From CommonDBTM
   public $table            = 'glpi_plugin_example_examples';
   public $type             = 'PluginExampleExample';

   // Should return the localized name of the type
   static function getTypeName() {
      return 'Example Type';
   }

   static function canCreate() {
      if (isset($_SESSION["glpi_plugin_example_profile"])) {
         return ($_SESSION["glpi_plugin_example_profile"]['example'] == 'w');
      }
      return false;
   }

   static function canView() {
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
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = $LANG['plugin_example']["name"];

      $tab[2]['table']     = 'glpi_plugin_example_dropdowns';
      $tab[2]['field']     = 'name';
      $tab[2]['linkfield'] = 'plugin_example_dropdowns_id';
      $tab[2]['name']      = 'Dropdown';

      $tab[3]['table']     = 'glpi_plugin_example_examples';
      $tab[3]['field']     = 'serial';
      $tab[3]['linkfield'] = 'serial';
      $tab[3]['name']      = 'Serial';
      $tab[3]['usehaving'] = true;

      $tab[30]['table']     = 'glpi_plugin_example_examples';
      $tab[30]['field']     = 'id';
      $tab[30]['linkfield'] = '';
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
         case 'sample2' :
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
   static function cronSample2($task) {

      $task->log("Example log message from class");
      $task->setVolume(mt_rand(0,10));

      return 1;
   }

}

?>
