<?php
/*
 -------------------------------------------------------------------------
 Example plugin for GLPI
 Copyright (C) {YEAR} by the {NAME} Development Team.

 https://github.com/pluginsGLPI/example
 -------------------------------------------------------------------------

 LICENSE

 This file is part of Example.

 Example is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Example is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Example. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

define ('PLUGIN_EXAMPLE_VERSION', '7.1');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_example() {
   global $PLUGIN_HOOKS,$CFG_GLPI;

   // Params : plugin name - string type - ID - Array of attributes
   // No specific information passed so not needed
   //Plugin::registerClass('PluginExampleExample',
   //                      array('classname'              => 'PluginExampleExample',
   //                        ));

   Plugin::registerClass('PluginExampleConfig', ['addtabon' => 'Config']);

   // Params : plugin name - string type - ID - Array of attributes
   Plugin::registerClass('PluginExampleDropdown');

   $types = ['Central', 'Computer', 'ComputerDisk', 'Notification', 'Phone',
             'Preference', 'Profile', 'Supplier'];
   Plugin::registerClass('PluginExampleExample',
                         ['notificationtemplates_types' => true,
                          'addtabon'                    => $types,
                          'link_types' => true]);

   Plugin::registerClass('PluginExampleRuleTestCollection',
                         ['rulecollections_types' => true]);

   Plugin::registerClass('PluginExampleDeviceCamera',
                         ['device_types' => true]);

   if (version_compare(GLPI_VERSION, '9.1', 'ge')) {
      if (class_exists('PluginExampleExample')) {
         Link::registerTag(PluginExampleExample::$tags);
      }
   }
   // Display a menu entry ?
   $_SESSION["glpi_plugin_example_profile"]['example'] = 'w';
   if (isset($_SESSION["glpi_plugin_example_profile"])) { // Right set in change_profile hook
      $PLUGIN_HOOKS['menu_toadd']['example'] = ['plugins' => 'PluginExampleExample',
                                                'tools'   => 'PluginExampleExample'];

      // Old menu style
      //       $PLUGIN_HOOKS['menu_entry']['example'] = 'front/example.php';
      //
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['title'] = "Search";
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['page']  = '/plugins/example/front/example.php';
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['links']['search'] = '/plugins/example/front/example.php';
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['links']['add']    = '/plugins/example/front/example.form.php';
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['links']['config'] = '/plugins/example/index.php';
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['links']["<img  src='".$CFG_GLPI["root_doc"]."/pics/menu_showall.png' title='".__s('Show all')."' alt='".__s('Show all')."'>"] = '/plugins/example/index.php';
      //       $PLUGIN_HOOKS['submenu_entry']['example']['options']['optionname']['links'][__s('Test link', 'example')] = '/plugins/example/index.php';

      $PLUGIN_HOOKS["helpdesk_menu_entry"]['example'] = true;
   }

   // Config page
   if (Session::haveRight('config', UPDATE)) {
      $PLUGIN_HOOKS['config_page']['example'] = 'config.php';
   }

   // Init session
   //$PLUGIN_HOOKS['init_session']['example'] = 'plugin_init_session_example';
   // Change profile
   $PLUGIN_HOOKS['change_profile']['example'] = 'plugin_change_profile_example';
   // Change entity
   //$PLUGIN_HOOKS['change_entity']['example'] = 'plugin_change_entity_example';

   // Item action event // See define.php for defined ITEM_TYPE
   $PLUGIN_HOOKS['pre_item_update']['example'] = ['Computer' => 'plugin_pre_item_update_example'];
   $PLUGIN_HOOKS['item_update']['example']     = ['Computer' => 'plugin_item_update_example'];

   $PLUGIN_HOOKS['item_empty']['example']      = ['Computer' => 'plugin_item_empty_example'];

   // Restrict right
   $PLUGIN_HOOKS['item_can']['example']          = ['Computer' => ['PluginExampleComputer', 'item_can']];
   $PLUGIN_HOOKS['add_default_where']['example'] = ['Computer' => ['PluginExampleComputer', 'add_default_where']];

   // Example using a method in class
   $PLUGIN_HOOKS['pre_item_add']['example']    = ['Computer' => ['PluginExampleExample',
                                                                 'pre_item_add_computer']];
   $PLUGIN_HOOKS['post_prepareadd']['example'] = ['Computer' => ['PluginExampleExample',
                                                                 'post_prepareadd_computer']];
   $PLUGIN_HOOKS['item_add']['example']        = ['Computer' => ['PluginExampleExample',
                                                                 'item_add_computer']];

   $PLUGIN_HOOKS['pre_item_delete']['example'] = ['Computer' => 'plugin_pre_item_delete_example'];
   $PLUGIN_HOOKS['item_delete']['example']     = ['Computer' => 'plugin_item_delete_example'];

   // Example using the same function
   $PLUGIN_HOOKS['pre_item_purge']['example'] = ['Computer' => 'plugin_pre_item_purge_example',
                                                 'Phone'    => 'plugin_pre_item_purge_example'];
   $PLUGIN_HOOKS['item_purge']['example']     = ['Computer' => 'plugin_item_purge_example',
                                                 'Phone'    => 'plugin_item_purge_example'];

   // Example with 2 different functions
   $PLUGIN_HOOKS['pre_item_restore']['example'] = ['Computer' => 'plugin_pre_item_restore_example',
                                                   'Phone'    => 'plugin_pre_item_restore_example2'];
   $PLUGIN_HOOKS['item_restore']['example']     = ['Computer' => 'plugin_item_restore_example'];

   // Add event to GLPI core itemtype, event will be raised by the plugin.
   // See plugin_example_uninstall for cleanup of notification
   $PLUGIN_HOOKS['item_get_events']['example']
                                 = ['NotificationTargetTicket' => 'plugin_example_get_events'];

   // Add datas to GLPI core itemtype for notifications template.
   $PLUGIN_HOOKS['item_get_datas']['example']
                                 = ['NotificationTargetTicket' => 'plugin_example_get_datas'];

   $PLUGIN_HOOKS['item_transfer']['example'] = 'plugin_item_transfer_example';

   // function to populate planning
   // No more used since GLPI 0.84
   // $PLUGIN_HOOKS['planning_populate']['example'] = 'plugin_planning_populate_example';
   // Use instead : add class to planning types and define populatePlanning in class
   $CFG_GLPI['planning_types'][] = 'PluginExampleExample';

   //function to display planning items
   // No more used sinc GLPi 0.84
   // $PLUGIN_HOOKS['display_planning']['example'] = 'plugin_display_planning_example';
   // Use instead : displayPlanningItem of the specific itemtype

   // Massive Action definition
   $PLUGIN_HOOKS['use_massive_action']['example'] = 1;

   $PLUGIN_HOOKS['assign_to_ticket']['example'] = 1;

   // Add specific files to add to the header : javascript or css
   $PLUGIN_HOOKS['add_javascript']['example'] = 'example.js';
   $PLUGIN_HOOKS['add_css']['example']        = 'example.css';

   // request more attributes from ldap
   //$PLUGIN_HOOKS['retrieve_more_field_from_ldap']['example']="plugin_retrieve_more_field_from_ldap_example";

   // Retrieve others datas from LDAP
   //$PLUGIN_HOOKS['retrieve_more_data_from_ldap']['example']="plugin_retrieve_more_data_from_ldap_example";

   // Reports
   $PLUGIN_HOOKS['reports']['example'] = ['report.php'       => 'New Report',
                                          'report.php?other' => 'New Report 2'];

   // Stats
   $PLUGIN_HOOKS['stats']['example'] = ['stat.php'       => 'New stat',
                                        'stat.php?other' => 'New stats 2',];

   $PLUGIN_HOOKS['post_init']['example'] = 'plugin_example_postinit';

   $PLUGIN_HOOKS['status']['example'] = 'plugin_example_Status';

   // CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
   $PLUGIN_HOOKS['csrf_compliant']['example'] = true;

   $PLUGIN_HOOKS['display_central']['example'] = "plugin_example_display_central";
   $PLUGIN_HOOKS['display_login']['example'] = "plugin_example_display_login";
   $PLUGIN_HOOKS['infocom']['example'] = "plugin_example_infocom_hook";

   // pre_show and post_show for tabs and items,
   // see PluginExampleShowtabitem class for implementation explanations
   $PLUGIN_HOOKS['pre_show_tab']['example']     = ['PluginExampleShowtabitem', 'pre_show_tab'];
   $PLUGIN_HOOKS['post_show_tab']['example']    = ['PluginExampleShowtabitem', 'post_show_tab'];
   $PLUGIN_HOOKS['pre_show_item']['example']    = ['PluginExampleShowtabitem', 'pre_show_item'];
   $PLUGIN_HOOKS['post_show_item']['example']   = ['PluginExampleShowtabitem', 'post_show_item'];

   $PLUGIN_HOOKS['pre_item_form']['example']    = ['PluginExampleItemForm', 'preItemForm'];
   $PLUGIN_HOOKS['post_item_form']['example']   = ['PluginExampleItemForm', 'postItemForm'];

   // Add new actions to timeline
   $PLUGIN_HOOKS['timeline_actions']['example'] = [
      'PluginExampleItemForm', 'timelineActions'
   ];

   // declare this plugin as an import plugin for Computer itemtype
   $PLUGIN_HOOKS['import_item']['example'] = ['Computer' => ['Plugin']];

   // add additional informations on Computer::showForm
   $PLUGIN_HOOKS['autoinventory_information']['example'] =  [
      'Computer' =>  ['PluginExampleComputer', 'showInfo']
   ];

   // add new cards to dashboard grid
   $PLUGIN_HOOKS['dashboard_types']['example'] = ['PluginExampleExample', 'dashboardTypes'];
   $PLUGIN_HOOKS['dashboard_cards']['example'] = ['PluginExampleExample', 'dashboardCards'];
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_example() {
   return [
      'name'           => 'Plugin Example',
      'version'        => PLUGIN_EXAMPLE_VERSION,
      'author'         => 'GLPI developer team',
      'license'        => 'GPLv2+',
      'homepage'       => 'https://github.com/pluginsGLPI/example',
      'requirements'   => [
         'glpi' => [
            'min' => '9.4',
         ]
      ]
   ];
}


/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_example_check_prerequisites() {

   //Version check is not done by core in GLPI < 9.2 but has to be delegated to core in GLPI >= 9.2.
   $version = preg_replace('/^((\d+\.?)+).*$/', '$1', GLPI_VERSION);
   if (version_compare($version, '9.2', '<')) {
      echo "This plugin requires GLPI >= 9.4";
      return false;
   }
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_example_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      echo __('Installed / not configured', 'example');
   }
   return false;
}
