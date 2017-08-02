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

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// Class of the defined type

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginExampleComputer extends CommonDBTM {

   static function showInfo() {

      echo '<table class="tab_glpi" width="100%">';
      echo '<tr>';
      echo '<th>'.__('More information').'</th>';
      echo '</tr>';
      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Test successful');
      echo '</td>';
      echo '</tr>';
      echo '</table>';
   }


   static function item_can($item) {

      if (($item-getType() == 'Computer')
          && ($item->right == READ)
          && ($item->fields['groups_id'] > 0)
          && !in_array($item->fields['groups_id'], $_SESSION["glpigroups"])) {
         $item->right = 0; // unknown, so denied.
      }
   }


   static function add_default_where($in) {

      list($itemtype, $condition) = $in;
      if ($itemtype == 'Computer') {
         $table = getTableForItemType($itemtype);
         $condition .= " (".$table.".groups_id NOT IN (".implode(',', $_SESSION["glpigroups"])."))";
      }
      return [$itemtype, $condition];
   }

}

