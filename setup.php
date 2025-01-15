<?php

/**
 * -------------------------------------------------------------------------
 * Example plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Example.
 *
 * Example is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Example is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Example. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2006-2022 by Example plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/example
 * -------------------------------------------------------------------------
 */

use Glpi\Plugin\Hooks;
use GlpiPlugin\Example\Computer;
use GlpiPlugin\Example\Config;
use GlpiPlugin\Example\Dropdown;
use GlpiPlugin\Example\DeviceCamera;
use GlpiPlugin\Example\Example;
use GlpiPlugin\Example\Filters\ComputerModelFilter;
use GlpiPlugin\Example\ItemForm;
use GlpiPlugin\Example\RuleTestCollection;
use GlpiPlugin\Example\Showtabitem;

define('PLUGIN_EXAMPLE_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define('PLUGIN_EXAMPLE_MIN_GLPI', '10.0.0');
// Maximum GLPI version, exclusive
define('PLUGIN_EXAMPLE_MAX_GLPI', '10.0.99');

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
   //Plugin::registerClass(Example::getType(),
   //                      array('classname'              => Example::class,
   //                        ));

   Plugin::registerClass(Config::class, ['addtabon' => 'Config']);

   // Params : plugin name - string type - ID - Array of attributes
   Plugin::registerClass(Dropdown::class);

   $types = ['Central', 'Computer', 'ComputerDisk', 'Notification', 'Phone',
             'Preference', 'Profile', 'Supplier'];
   Plugin::registerClass(Example::class,
                         ['notificationtemplates_types' => true,
                          'addtabon'                    => $types,
                          'link_types' => true]);

   Plugin::registerClass(RuleTestCollection::class,
                         ['rulecollections_types' => true]);

   Plugin::registerClass(DeviceCamera::class,
                         ['device_types' => true]);

   if (version_compare(GLPI_VERSION, '9.1', 'ge')) {
      if (class_exists(Example::class)) {
         Link::registerTag(Example::$tags);
      }
   }
   // Display a menu entry ?
   Plugin::registerClass(\GlpiPlugin\Example\Profile::class, ['addtabon' => ['Profile']]);
   if (Example::canView()) { // Right set in change_profile hook
      $PLUGIN_HOOKS['menu_toadd']['example'] = ['plugins' => Example::class,
                                                'tools'   => Example::class];

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

      $PLUGIN_HOOKS[Hooks::HELPDESK_MENU_ENTRY]['example'] = true;
      $PLUGIN_HOOKS[Hooks::HELPDESK_MENU_ENTRY_ICON]['example'] = 'fas fa-puzzle-piece';
   }

   // Config page
   if (Session::haveRight('config', UPDATE)) {
      $PLUGIN_HOOKS['config_page']['example'] = 'front/config.php';
   }

   // Init session
   //$PLUGIN_HOOKS['init_session']['example'] = 'plugin_init_session_example';
   // Change profile
   $PLUGIN_HOOKS['change_profile']['example'] = 'plugin_change_profile_example';
   // Change entity
   //$PLUGIN_HOOKS['change_entity']['example'] = 'plugin_change_entity_example';

   // Item action event // See define.php for defined ITEM_TYPE
   $PLUGIN_HOOKS[Hooks::PRE_ITEM_UPDATE]['example'] = [Computer::class => 'plugin_pre_item_update_example'];
   $PLUGIN_HOOKS[hooks::ITEM_UPDATE]['example']     = [Computer::class => 'plugin_item_update_example'];

   $PLUGIN_HOOKS[Hooks::ITEM_EMPTY]['example']      = [Computer::class => 'plugin_item_empty_example'];

   // Restrict right
   $PLUGIN_HOOKS[Hooks::ITEM_CAN]['example']          = [Computer::class => [Example::class, 'item_can']];
   $PLUGIN_HOOKS['add_default_where']['example'] = [Computer::class => [Example::class, 'add_default_where']];

   // Example using a method in class
   $PLUGIN_HOOKS[Hooks::PRE_ITEM_ADD]['example']    = [Computer::class => [Example::class,
                                                                 'pre_item_add_computer']];
   $PLUGIN_HOOKS[Hooks::POST_PREPAREADD]['example'] = [Computer::class => [Example::class,
                                                                 'post_prepareadd_computer']];
   $PLUGIN_HOOKS[Hooks::ITEM_ADD]['example']        = [Computer::class => [Example::class,
                                                                 'item_add_computer']];

   $PLUGIN_HOOKS[Hooks::PRE_ITEM_DELETE]['example'] = [Computer::class => 'plugin_pre_item_delete_example'];
   $PLUGIN_HOOKS[Hooks::ITEM_DELETE]['example']     = [Computer::class => 'plugin_item_delete_example'];

   // Example using the same function
   $PLUGIN_HOOKS[Hooks::PRE_ITEM_PURGE]['example'] = [Computer::class => 'plugin_pre_item_purge_example',
                                                 'Phone'    => 'plugin_pre_item_purge_example'];
   $PLUGIN_HOOKS[Hooks::ITEM_PURGE]['example']     = [Computer::class => 'plugin_item_purge_example',
                                                 'Phone'    => 'plugin_item_purge_example'];

   // Example with 2 different functions
   $PLUGIN_HOOKS[Hooks::PRE_ITEM_RESTORE]['example'] = [Computer::class => 'plugin_pre_item_restore_example',
                                                   'Phone'    => 'plugin_pre_item_restore_example2'];
   $PLUGIN_HOOKS[Hooks::ITEM_RESTORE]['example']     = [Computer::class => 'plugin_item_restore_example'];

   // Add event to GLPI core itemtype, event will be raised by the plugin.
   // See plugin_example_uninstall for cleanup of notification
   $PLUGIN_HOOKS[Hooks::ITEM_GET_EVENTS]['example']
                                 = ['NotificationTargetTicket' => 'plugin_example_get_events'];

   // Add datas to GLPI core itemtype for notifications template.
   $PLUGIN_HOOKS[Hooks::ITEM_GET_DATA]['example']
                                 = ['NotificationTargetTicket' => 'plugin_example_get_datas'];

   $PLUGIN_HOOKS[Hooks::ITEM_TRANSFER]['example'] = 'plugin_item_transfer_example';

   // function to populate planning
   // No more used since GLPI 0.84
   // $PLUGIN_HOOKS['planning_populate']['example'] = 'plugin_planning_populate_example';
   // Use instead : add class to planning types and define populatePlanning in class
   $CFG_GLPI['planning_types'][] = Example::class;

   //function to display planning items
   // No more used sinc GLPi 0.84
   // $PLUGIN_HOOKS['display_planning']['example'] = 'plugin_display_planning_example';
   // Use instead : displayPlanningItem of the specific itemtype

   // Massive Action definition
   $PLUGIN_HOOKS['use_massive_action']['example'] = 1;

   $PLUGIN_HOOKS['assign_to_ticket']['example'] = 1;

   // Add specific files to add to the header : javascript or css
   $PLUGIN_HOOKS[Hooks::ADD_JAVASCRIPT]['example'] = 'example.js';
   $PLUGIN_HOOKS[Hooks::ADD_CSS]['example']        = 'example.css';

   // Add specific tags to the header
   $PLUGIN_HOOKS[Hooks::ADD_HEADER_TAG]['example'] = [
      [
         'tag' => 'meta',
         'properties' => [
            'name'    => 'robots',
            'content' => 'noindex, nofollow',
         ]
      ],
      [
         'tag' => 'link',
         'properties' => [
            'rel'   => 'alternate',
            'type'  => 'application/rss+xml',
            'title' => 'The company RSS feed',
            'href'  => 'https://example.org/feed.xml',
         ]
      ],
   ];

   // Add specific files to add to the header into anonymous page : javascript or css
   $PLUGIN_HOOKS[Hooks::ADD_CSS_ANONYMOUS_PAGE]['example']        = 'example_anonymous.css';
   $PLUGIN_HOOKS[Hooks::ADD_JAVASCRIPT_MODULE_ANONYMOUS_PAGE]['example']        = 'mymodule_anonymous.js';
   $PLUGIN_HOOKS[Hooks::ADD_JAVASCRIPT_ANONYMOUS_PAGE]['example']        = 'example_anonymous.js';

   // Add specific tags to the header into anonymous page
   $PLUGIN_HOOKS[Hooks::ADD_HEADER_TAG_ANONYMOUS_PAGE]['example'] = [
      [
         'tag' => 'meta',
         'properties' => [
            'name'    => 'robots',
            'content' => 'noindex, nofollow',
         ]
      ],
      [
         'tag' => 'link',
         'properties' => [
            'rel'   => 'alternate',
            'type'  => 'application/rss+xml',
            'title' => 'The company RSS feed',
            'href'  => 'https://example.org/feed.xml',
         ]
      ],
   ];

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

   $PLUGIN_HOOKS[Hooks::POST_INIT]['example'] = 'plugin_example_postinit';

   $PLUGIN_HOOKS['status']['example'] = 'plugin_example_Status';

   // CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
   $PLUGIN_HOOKS[Hooks::CSRF_COMPLIANT]['example'] = true;

   $PLUGIN_HOOKS[Hooks::DISPLAY_CENTRAL]['example'] = "plugin_example_display_central";
   $PLUGIN_HOOKS[Hooks::DISPLAY_LOGIN]['example'] = "plugin_example_display_login";
   $PLUGIN_HOOKS[Hooks::INFOCOM]['example'] = "plugin_example_infocom_hook";

   // pre_show and post_show for tabs and items,
   // see GlpiPlugin\Example\Showtabitem class for implementation explanations
   $PLUGIN_HOOKS[Hooks::PRE_SHOW_TAB]['example']     = [Showtabitem::class, 'pre_show_tab'];
   $PLUGIN_HOOKS[Hooks::POST_SHOW_TAB]['example']    = [Showtabitem::class, 'post_show_tab'];
   $PLUGIN_HOOKS[Hooks::PRE_SHOW_ITEM]['example']    = [Showtabitem::class, 'pre_show_item'];
   $PLUGIN_HOOKS[Hooks::POST_SHOW_ITEM]['example']   = [Showtabitem::class, 'post_show_item'];

   $PLUGIN_HOOKS[Hooks::PRE_ITEM_FORM]['example']    = [ItemForm::class, 'preItemForm'];
   $PLUGIN_HOOKS[Hooks::POST_ITEM_FORM]['example']   = [ItemForm::class, 'postItemForm'];

   $PLUGIN_HOOKS[Hooks::PRE_ITIL_INFO_SECTION]['example']    = [ItemForm::class, 'preSection'];
   $PLUGIN_HOOKS[Hooks::POST_ITIL_INFO_SECTION]['example']   = [ItemForm::class, 'postSection'];

   // Add new actions to timeline
   $PLUGIN_HOOKS[Hooks::TIMELINE_ACTIONS]['example'] = [
      ItemForm::class, 'timelineActions'
   ];

   // declare this plugin as an import plugin for Computer itemtype
   $PLUGIN_HOOKS['import_item']['example'] = [Computer::class => ['Plugin']];

   // add additional informations on Computer::showForm
   $PLUGIN_HOOKS[Hooks::AUTOINVENTORY_INFORMATION]['example'] =  [
      Computer::class =>  [Computer::class, 'showInfo']
   ];

    $PLUGIN_HOOKS[Hooks::FILTER_ACTORS]['example'] = "plugin_example_filter_actors";

   // add new cards to dashboard grid
   $PLUGIN_HOOKS['dashboard_types']['example'] = [Example::class, 'dashboardTypes'];
   $PLUGIN_HOOKS['dashboard_cards']['example'] = [Example::class, 'dashboardCards'];

   // Dashboard filter
   $PLUGIN_HOOKS[Hooks::DASHBOARD_FILTERS]['example'] = [
      ComputerModelFilter::class
   ];

   // Icon in the impact analysis
   $PLUGIN_HOOKS[Hooks::SET_ITEM_IMPACT_ICON]['example'] = 'plugin_example_set_impact_icon';
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
      'author'         => 'Example plugin team',
      'license'        => 'GPLv2+',
      'homepage'       => 'https://github.com/pluginsGLPI/example',
      'requirements'   => [
         'glpi' => [
            'min' => PLUGIN_EXAMPLE_MIN_GLPI,
            'max' => PLUGIN_EXAMPLE_MAX_GLPI,
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
   if (false) {
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
