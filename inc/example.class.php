<?php
/*
 * @version $Id: HEADER 15930 2011-10-25 10:47:55Z jmd $
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
class PluginExampleExample extends CommonDBTM {

   static $tags = '[EXAMPLE_ID]';

   // Should return the localized name of the type
   static function getTypeName($nb = 0) {
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


   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Example plugin');
   }


   /**
    * @see CommonGLPI::getAdditionalMenuLinks()
   **/
   static function getAdditionalMenuLinks() {
      global $CFG_GLPI;
      $links = [];

      $links['config'] = '/plugins/example/index.php';
      $links["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".__s('Show all')."' alt='".__s('Show all')."'>"] = '/plugins/example/index.php';
      $links[__s('Test link', 'example')] = '/plugins/example/index.php';

      return $links;
   }

   function defineTabs($options = []) {

      $ong = [];
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('Link', $ong, $options);

      return $ong;
   }

   function showForm($ID, $options = []) {
      global $CFG_GLPI;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";

      echo "<td>" . __('ID') . "</td>";
      echo "<td>";
      echo $ID;
      echo "</td>";

      $this->showFormButtons($options);

      return true;
   }

   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id'                 => 'common',
         'name'               => __('Header Needed')
      ];

      $tab[] = [
         'id'                 => '1',
         'table'              => 'glpi_plugin_example_examples',
         'field'              => 'name',
         'name'               => __('Name'),
      ];

      $tab[] = [
         'id'                 => '2',
         'table'              => 'glpi_plugin_example_dropdowns',
         'field'              => 'name',
         'name'               => __('Dropdown'),
      ];

      $tab[] = [
         'id'                 => '3',
         'table'              => 'glpi_plugin_example_examples',
         'field'              => 'serial',
         'name'               => __('Serial number'),
         'usehaving'          => true,
         'searchtype'         => 'equals',
      ];

      $tab[] = [
         'id'                 => '30',
         'table'              => 'glpi_plugin_example_examples',
         'field'              => 'id',
         'name'               => __('ID'),
         'usehaving'          => true,
         'searchtype'         => 'equals',
      ];

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

      switch ($name) {
         case 'Sample' :
            return ['description' => __('Cron description for example', 'example'),
                    'parameter'   => __('Cron parameter for example', 'example')];
      }
      return [];
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
      $r = mt_rand(0, $task->fields['param']);
      usleep(1000000+$r*1000);
      $task->setVolume($r);

      return 1;
   }


   // Hook done on before add item case (data from form, not altered)
   static function pre_item_add_computer(Computer $item) {
      if (isset($item->input['name']) && empty($item->input['name'])) {
         Session::addMessageAfterRedirect("Pre Add Computer Hook KO (name empty)", true);
         return $item->input = false;
      } else {
         Session::addMessageAfterRedirect("Pre Add Computer Hook OK", true);
      }
   }

   // Hook done on before add item case (data altered by object prepareInputForAdd)
   static function post_prepareadd_computer(Computer $item) {
      Session::addMessageAfterRedirect("Post prepareAdd Computer Hook", true);
   }


   // Hook done on add item case
   static function item_add_computer(Computer $item) {

      Session::addMessageAfterRedirect("Add Computer Hook, ID=".$item->getID(), true);
      return true;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if (!$withtemplate) {
         switch ($item->getType()) {
            case 'Profile' :
               if ($item->getField('central')) {
                  return __('Example', 'example');
               }
               break;

            case 'Phone' :
               if ($_SESSION['glpishow_count_on_tabs']) {
                  return self::createTabEntry(__('Example', 'example'),
                                              countElementsInTable($this->getTable()));
               }
               return __('Example', 'example');

            case 'ComputerDisk' :
            case 'Supplier' :
               return [1 => __("Test Plugin", 'example'),
                       2 => __("Test Plugin 2", 'example')];

            case 'Computer' :
            case 'Central' :
            case 'Preference':
            case 'Notification':
               return [1 => __("Test Plugin", 'example')];

         }
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {

      switch ($item->getType()) {
         case 'Phone' :
            echo __("Plugin Example on Phone", 'example');
            break;

         case 'Central' :
            echo __("Plugin central action", 'example');
            break;

         case 'Preference' :
            // Complete form display
            $data = plugin_version_example();

            echo "<form action='Where to post form'>";
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr><th colspan='3'>".$data['name']." - ".$data['version'];
            echo "</th></tr>";

            echo "<tr class='tab_bg_1'><td>Name of the pref</td>";
            echo "<td>Input to set the pref</td>";

            echo "<td><input class='submit' type='submit' name='submit' value='submit'></td>";
            echo "</tr>";

            echo "</table>";
            echo "</form>";
            break;

         case 'Notification' :
            echo __("Plugin mailing action", 'example');
            break;

         case 'ComputerDisk' :
         case 'Supplier' :
            if ($tabnum==1) {
               echo __('First tab of Plugin example', 'example');
            } else {
               echo __('Second tab of Plugin example', 'example');
            }
            break;

         default :
            //TRANS: %1$s is a class name, %2$d is an item ID
            printf(__('Plugin example CLASS=%1$s id=%2$d', 'example'), $item->getType(), $item->getField('id'));
            break;
      }
      return true;
   }

   static function getSpecificValueToDisplay($field, $values, array $options = []) {

      if (!is_array($values)) {
         $values = [$field => $values];
      }
      switch ($field) {
         case 'serial' :
            return "S/N: ".$values[$field];
      }
      return '';
   }

   // Parm contains begin, end and who
   // Create data to be displayed in the planning of $parm["who"] or $parm["who_group"] between $parm["begin"] and $parm["end"]
   static function populatePlanning($parm) {

      // Add items in the output array
      // Items need to have an unique index beginning by the begin date of the item to display
      // needed to be correcly displayed
      $output = [];
      $key = $parm["begin"]."$$$"."plugin_example1";
      $output[$key]["begin"]  = date("Y-m-d 17:00:00");
      $output[$key]["end"]    = date("Y-m-d 18:00:00");
      $output[$key]["name"]   = __("test planning example 1", 'example');
      // Specify the itemtype to be able to use specific display system
      $output[$key]["itemtype"] = "PluginExampleExample";
      // Set the ID using the ID of the item in the database to have unique ID
      $output[$key][getForeignKeyFieldForItemType('PluginExampleExample')] = 1;
      return $output;
   }

   /**
    * Display a Planning Item
    *
    * @param $val Array of the item to display
    * @param $who ID of the user (0 if all)
    * @param $type position of the item in the time block (in, through, begin or end)
    * @param $complete complete display (more details)
    *
    * @return Nothing (display function)
    **/
   static function displayPlanningItem(array $val, $who, $type = "", $complete = 0) {

      // $parm["type"] say begin end in or from type
      // Add items in the items fields of the parm array
      switch ($type) {
         case "in" :
            //TRANS: %1$s is the start time of a planned item, %2$s is the end
            printf(__('From %1$s to %2$s :'),
                   date("H:i", strtotime($val["begin"])), date("H:i", strtotime($val["end"])));
            break;

         case "through" :
            echo Html::resume_text($val["name"], 80);
            break;

         case "begin" :
            //TRANS: %s is the start time of a planned item
            printf(__('Start at %s:'), date("H:i", strtotime($val["begin"])));
            break;

         case "end" :
            //TRANS: %s is the end time of a planned item
            printf(__('End at %s:'), date("H:i", strtotime($val["end"])));
         break;
      }
      echo "<br>";
      echo Html::resume_text($val["name"], 80);
   }

   /**
    * Get an history entry message
    *
    * @param $data Array from glpi_logs table
    *
    * @since GLPI version 0.84
    *
    * @return string
   **/
   static function getHistoryEntry($data) {

      switch ($data['linked_action'] - Log::HISTORY_PLUGIN) {
         case 0:
            return __('History from plugin example', 'example');
      }

      return '';
   }


   //////////////////////////////
   ////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::getSpecificMassiveActions()
   **/
   function getSpecificMassiveActions($checkitem = null) {

      $actions = parent::getSpecificMassiveActions($checkitem);

      $actions['Document_Item'.MassiveAction::CLASS_ACTION_SEPARATOR.'add']  =
                                        _x('button', 'Add a document');         // GLPI core one
      $actions[__CLASS__.MassiveAction::CLASS_ACTION_SEPARATOR.'do_nothing'] =
                                        __('Do Nothing - just for fun', 'example');  // Specific one

      return $actions;
   }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'DoIt':
            echo "&nbsp;<input type='hidden' name='toto' value='1'>".
                 Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']).
                 " ".__('Write in item history', 'example');
            return true;
         case 'do_nothing' :
            echo "&nbsp;".Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']).
                 " ".__('but do nothing :)', 'example');
            return true;
      }
      return parent::showMassiveActionsSubForm($ma);
   }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      global $DB;

      switch ($ma->getAction()) {
         case 'DoIt' :
            if ($item->getType() == 'Computer') {
               Session::addMessageAfterRedirect(__("Right it is the type I want...", 'example'));
               Session::addMessageAfterRedirect(__('Write in item history', 'example'));
               $changes = [0, 'old value', 'new value'];
               foreach ($ids as $id) {
                  if ($item->getFromDB($id)) {
                     Session::addMessageAfterRedirect("- ".$item->getField("name"));
                     Log::history($id, 'Computer', $changes, 'PluginExampleExample',
                                  Log::HISTORY_PLUGIN);
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                  } else {
                     // Example of ko count
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                  }
               }
            } else {
               // When nothing is possible ...
               $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
            }
            return;

         case 'do_nothing' :
            If ($item->getType() == 'PluginExampleExample') {
               Session::addMessageAfterRedirect(__("Right it is the type I want...", 'example'));
               Session::addMessageAfterRedirect(__("But... I say I will do nothing for:",
                                                   'example'));
               foreach ($ids as $id) {
                  if ($item->getFromDB($id)) {
                     Session::addMessageAfterRedirect("- ".$item->getField("name"));
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                  } else {
                     // Example for noright / Maybe do it with can function is better
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                  }
               }
            } else {
               $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
            }
            Return;
      }
      parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
   }

   static function generateLinkContents($link, CommonDBTM $item) {

      if (strstr($link, "[EXAMPLE_ID]")) {
         $link = str_replace("[EXAMPLE_ID]", $item->getID(), $link);
         return [$link];
      }

      return parent::generateLinkContents($link, $item);
   }


   static function dashboardTypes() {
      return [
         'example' => [
            'label'    => __("Plugin Example", 'example'),
            'function' => "PluginExampleExample::cardWidget",
            'image'    => "https://via.placeholder.com/100x86?text=example",
         ],
         'example_static' => [
            'label'    => __("Plugin Example (static)", 'example'),
            'function' => "PluginExampleExample::cardWidgetWithoutProvider",
            'image'    => "https://via.placeholder.com/100x86?text=example+static",
         ],
      ];
   }


   static function dashboardCards() {
      return [
         'plugin_example_card' => [
            'widgettype'   => ["example"],
            'label'        => __("Plugin Example card"),
            'provider'     => "PluginExampleExample::cardDataProvider",
         ],
         'plugin_example_card_without_provider' => [
            'widgettype'   => ["example_static"],
            'label'        => __("Plugin Example card without provider"),
         ],
         'plugin_example_card_with_core_widget' => [
            'widgettype'   => ["bigNumber"],
            'label'        => __("Plugin Example card with core provider"),
            'provider'     => "PluginExampleExample::cardBigNumberProvider",
         ],
      ];
   }


   static function cardWidget(array $params = []) {
      $default = [
         'data'  => [],
         'title' => '',
         // this property is "pretty" mandatory,
         // as it contains the colors selected when adding widget on the grid send
         // without it, your card will be transparent
         'color' => '',
      ];
      $p = array_merge($default, $params);

      // you need to encapsulate your html in div.card to benefit core style
      $html = "<div class='card' style='background-color: {$p["color"]};'>";
      $html.= "<h2>{$p['title']}</h2>";
      $html.= "<ul>";
      foreach ($p['data'] as $line) {
         $html.= "<li>$line</li>";
      }
      $html.= "</ul>";
      $html.= "</div>";

      return $html;
   }

   static function cardDataProvider(array $params = []) {
      $default_params = [
         'label' => null,
         'icon'  => "fas fa-smile-wink",
      ];
      $params = array_merge($default_params, $params);

      return [
         'title' => $params['label'],
         'icon'  => $params['icon'],
         'data'  => [
            'test1',
            'test2',
            'test3',
         ]
      ];
   }

   static function cardWidgetWithoutProvider(array $params = []) {
      $default = [
         // this property is "pretty" mandatory,
         // as it contains the colors selected when adding widget on the grid send
         // without it, your card will be transparent
         'color' => '',
      ];
      $p = array_merge($default, $params);

      // you need to encapsulate your html in div.card to benefit core style
      $html = "<div class='card' style='background-color: {$p["color"]};'>
                  static html (+optional javascript) as card is not matched with a data provider

                  <img src='https://www.linux.org/images/logo.png'>
               </div>";

      return $html;
   }

   static function cardBigNumberProvider(array $params = []) {
      $default_params = [
         'label' => null,
         'icon'  => null,
      ];
      $params = array_merge($default_params, $params);

      return [
         'number' => rand(),
         'url'    => "https://www.linux.org/",
         'label'  => "plugin example - some text",
         'icon'   => "fab fa-linux", // font awesome icon
      ];
   }
}
