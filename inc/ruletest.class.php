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
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


/**
* Rule class store all informations about a GLPI rule :
*   - description
*   - criterias
*   - actions
*
**/
class PluginExampleRuleTest extends Rule {

   // From Rule
   static public $right     = 'rule_import';
   public $can_sort  = true;


   function getTitle() {
      return 'test';
   }


   function maxActionsCount() {
      return 1;
   }


   function getCriterias() {

      $criterias = [];
      $criterias['name']['field'] = 'name';
      $criterias['name']['name']  = __('Software');
      $criterias['name']['table'] = 'glpi_softwares';

      return $criterias;
   }


   function getActions() {

      $actions = [];
      $actions['softwarecategories_id']['name']  = __('Category (class)', 'example');
      $actions['softwarecategories_id']['type']  = 'dropdown';
      $actions['softwarecategories_id']['table'] = 'glpi_softwarecategories';
      return $actions;
   }
}
