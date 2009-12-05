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
   public $table            = 'glpi_plugin_example_example';
   public $type             = 'PluginExampleExample';

   // Should return the localized name of the type
   static function getTypeName() {
      global $LANG;

      return 'Example Type';
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

?>
