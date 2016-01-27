<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

class PluginExampleConfig extends CommonDBTM {

   static protected $notable = true;

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate) {
         if ($item->getType() == 'Config') {
            return __('Example plugin');
         }
      }
      return '';
   }

   static function configUpdate($input) {
      $input['configuration'] = 1 - $input['configuration'];
      return $input;
   }

   function showFormExample() {
      global $CFG_GLPI;

      if (!Session::haveRight("config", UPDATE)) {
         return false;
      }

      $my_config = Config::getConfigurationValues('plugin:Example');

      echo "<form name='form' action=\"".Toolbox::getItemTypeFormURL('Config')."\" method='post'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>" . __('Example setup') . "</th></tr>";
      echo "<td >" . __('My boolean choice :') . "</td>";
      echo "<td colspan='3'>";
      echo "<input type='hidden' name='config_class' value='".__CLASS__."'>";
      echo "<input type='hidden' name='config_context' value='plugin:Example'>";
      Dropdown::showYesNo("configuration", $my_config['configuration']);
      echo "</td></tr>";


      echo "<tr class='tab_bg_2'>";
      echo "<td colspan='4' class='center'>";
      echo "<input type='submit' name='update' class='submit' value=\""._sx('button','Save')."\">";
      echo "</td></tr>";

      echo "</table></div>";
      Html::closeForm();
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == 'Config') {
         $config = new self();
         $config->showFormExample();
      }
   }

}
?>