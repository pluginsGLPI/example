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

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

namespace GlpiPlugin\Example;

use Central;
use CommonDBTM;
use CommonGLPI;
use Computer;
use Html;
use Item_Disk;
use Log;
use MassiveAction;
use Notification;
use Phone;
use Preference;
use Session;
use Supplier;

use function Safe\strtotime;

// Class of the defined type
class Example extends CommonDBTM
{
    public static $tags      = '[EXAMPLE_ID]';
    public static $rightname = 'plugin_example';

    // Should return the localized name of the type
    public static function getTypeName($nb = 0)
    {
        return 'Example Type';
    }

    public static function getMenuName()
    {
        return __s('Example plugin');
    }

    public static function getAdditionalMenuLinks()
    {
        global $CFG_GLPI;
        $links = [];

        $links['config']                                                                                                                         = '/plugins/example/index.php';
        $links["<img  src='" . $CFG_GLPI['root_doc'] . "/pics/menu_showall.png' title='" . __s('Show all') . "' alt='" . __s('Show all') . "'>"] = '/plugins/example/index.php';
        $links[__s('Test link', 'example')]                                                                                                      = '/plugins/example/index.php';

        return $links;
    }

    public function defineTabs($options = [])
    {
        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab('Link', $ong, $options);

        return $ong;
    }

    public function showForm($ID, array $options = [])
    {
        global $CFG_GLPI;

        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";

        echo '<td>' . __s('ID') . '</td>';
        echo '<td>';
        echo $ID;
        echo '</td>';

        $this->showFormButtons($options);

        return true;
    }

    public function rawSearchOptions()
    {
        $tab = [];

        $tab[] = [
            'id'   => 'common',
            'name' => __s('Header Needed'),
        ];

        $tab[] = [
            'id'    => '1',
            'table' => 'glpi_plugin_example_examples',
            'field' => 'name',
            'name'  => __s('Name'),
        ];

        $tab[] = [
            'id'    => '2',
            'table' => 'glpi_plugin_example_dropdowns',
            'field' => 'name',
            'name'  => __s('Dropdown'),
        ];

        $tab[] = [
            'id'         => '3',
            'table'      => 'glpi_plugin_example_examples',
            'field'      => 'serial',
            'name'       => __s('Serial number'),
            'usehaving'  => true,
            'searchtype' => 'equals',
        ];

        $tab[] = [
            'id'         => '30',
            'table'      => 'glpi_plugin_example_examples',
            'field'      => 'id',
            'name'       => __s('ID'),
            'usehaving'  => true,
            'searchtype' => 'equals',
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
    public static function cronInfo($name)
    {
        switch ($name) {
            case 'Sample':
                return ['description' => __s('Cron description for example', 'example'),
                    'parameter'       => __s('Cron parameter for example', 'example')];
        }

        return [];
    }

    /**
     * Execute 1 task manage by the plugin
     *
     * @param $task Object of CronTask class for log / stat
     *
     * @return int
     *    >0 : done
     *    <0 : to be run again (not finished)
     *     0 : nothing to do
     */
    public static function cronSample($task)
    {
        $task->log('Example log message from class');
        $r = mt_rand(0, $task->fields['param']);
        usleep(1000000 + $r * 1000);
        $task->setVolume($r);

        return 1;
    }

    // Hook done on before add item case (data from form, not altered)
    public static function pre_item_add_computer(Computer $item)
    {
        if (isset($item->input['name']) && empty($item->input['name'])) {
            Session::addMessageAfterRedirect('Pre Add Computer Hook KO (name empty)', true);

            return $item->input = false;
        } else {
            Session::addMessageAfterRedirect('Pre Add Computer Hook OK', true);
        }
    }

    // Hook done on before add item case (data altered by object prepareInputForAdd)
    public static function post_prepareadd_computer(Computer $item)
    {
        Session::addMessageAfterRedirect('Post prepareAdd Computer Hook', true);
    }

    // Hook done on add item case
    public static function item_add_computer(Computer $item)
    {
        Session::addMessageAfterRedirect('Add Computer Hook, ID=' . $item->getID(), true);

        return true;
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if (!$withtemplate) {
            if ($item instanceof Profile) {
                if ($item->getField('central')) {
                    return __s('Example', 'example');
                }

            } elseif ($item instanceof Phone) {
                if ($_SESSION['glpishow_count_on_tabs']) {
                    return self::createTabEntry(
                        __s('Example', 'example'),
                        countElementsInTable($this->getTable()),
                    );
                }

                return __s('Example', 'example');

            } elseif ($item instanceof Item_Disk || $item instanceof Supplier) {
                return [
                    1 => __s('Test Plugin', 'example'),
                    2 => __s('Test Plugin 2', 'example'),
                ];

            } elseif ($item instanceof Computer
                    || $item instanceof Central
                    || $item instanceof Preference
                    || $item instanceof Notification) {
                return [
                    1 => __s('Test Plugin', 'example'),
                ];
            }
        }

        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item instanceof Phone) {
            echo __s('Plugin Example on Phone', 'example');

        } elseif ($item instanceof Central) {
            echo __s('Plugin central action', 'example');

        } elseif ($item instanceof Preference) {
            // Complete form display
            $data = plugin_version_example();

            echo "<form action='Where to post form'>";
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr><th colspan='3'>" . $data['name'] . ' - ' . $data['version'];
            echo '</th></tr>';

            echo "<tr class='tab_bg_1'><td>Name of the pref</td>";
            echo '<td>Input to set the pref</td>';

            echo "<td><input class='submit' type='submit' name='submit' value='submit'></td>";
            echo '</tr>';

            echo '</table>';
            echo '</form>';

        } elseif ($item instanceof Notification) {
            echo __s('Plugin mailing action', 'example');

        } elseif ($item instanceof Item_Disk || $item instanceof Supplier) {
            if ($tabnum == 1) {
                echo __s('First tab of Plugin example', 'example');
            } else {
                echo __s('Second tab of Plugin example', 'example');
            }

        } else {
            //TRANS: %1$s is a class name, %2$d is an item ID
            printf(__s('Plugin example CLASS=%1$s', 'example'), get_class($item));
        }


        return true;
    }

    public static function getSpecificValueToDisplay($field, $values, array $options = [])
    {
        if (!is_array($values)) {
            $values = [$field => $values];
        }
        switch ($field) {
            case 'serial':
                return 'S/N: ' . $values[$field];
        }

        return '';
    }

    // Parm contains begin, end and who
    // Create data to be displayed in the planning of $parm["who"] or $parm["who_group"] between $parm["begin"] and $parm["end"]
    public static function populatePlanning($parm)
    {
        // Add items in the output array
        // Items need to have an unique index beginning by the begin date of the item to display
        // needed to be correcly displayed
        $output                = [];
        $key                   = $parm['begin'] . '$$$' . 'plugin_example1';
        $output[$key]['begin'] = date('Y-m-d 17:00:00');
        $output[$key]['end']   = date('Y-m-d 18:00:00');
        $output[$key]['name']  = __s('test planning example 1', 'example');
        // Specify the itemtype to be able to use specific display system
        $output[$key]['itemtype'] = Example::class;
        // Set the ID using the ID of the item in the database to have unique ID
        $output[$key][getForeignKeyFieldForItemType(Example::class)] = 1;

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
     * @return void (display function)
     **/
    public static function displayPlanningItem(array $val, $who, $type = '', $complete = 0)
    {
        // $parm["type"] say begin end in or from type
        // Add items in the items fields of the parm array
        switch ($type) {
            case 'in':
                //TRANS: %1$s is the start time of a planned item, %2$s is the end
                printf(
                    __s('From %1$s to %2$s :'),
                    date('H:i', strtotime($val['begin'])),
                    date('H:i', strtotime($val['end'])),
                );
                break;

            case 'through':
                echo Html::resume_text($val['name'], 80);
                break;

            case 'begin':
                //TRANS: %s is the start time of a planned item
                printf(__s('Start at %s:'), date('H:i', strtotime($val['begin'])));
                break;

            case 'end':
                //TRANS: %s is the end time of a planned item
                printf(__s('End at %s:'), date('H:i', strtotime($val['end'])));
                break;
        }
        echo '<br>';
        echo Html::resume_text($val['name'], 80);
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
    public static function getHistoryEntry($data)
    {
        switch ($data['linked_action'] - Log::HISTORY_PLUGIN) {
            case 0:
                return __s('History from plugin example', 'example');
        }

        return '';
    }

    //////////////////////////////
    ////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////
    public function getSpecificMassiveActions($checkitem = null)
    {
        $actions = parent::getSpecificMassiveActions($checkitem);

        $actions['Document_Item' . MassiveAction::CLASS_ACTION_SEPARATOR . 'add']  = _x('button', 'Add a document');         // GLPI core one
        $actions[self::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'do_nothing'] = __s('Do Nothing - just for fun', 'example');  // Specific one

        return $actions;
    }

    public static function showMassiveActionsSubForm(MassiveAction $ma)
    {
        switch ($ma->getAction()) {
            case 'DoIt':
                echo "&nbsp;<input type='hidden' name='toto' value='1'>" .
                     Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']) .
                     ' ' . __s('Write in item history', 'example');

                return true;
            case 'do_nothing':
                echo '&nbsp;' . Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']) .
                     ' ' . __s('but do nothing :)', 'example');

                return true;
        }

        return parent::showMassiveActionsSubForm($ma);
    }

    /**
     * @since version 0.85
     *
     * @see CommonDBTM::processMassiveActionsForOneItemtype()
    **/
    public static function processMassiveActionsForOneItemtype(
        MassiveAction $ma,
        CommonDBTM $item,
        array $ids
    ) {
        global $DB;

        switch ($ma->getAction()) {
            case 'DoIt':
                if ($item->getType() == 'Computer') {
                    Session::addMessageAfterRedirect(__s('Right it is the type I want...', 'example'));
                    Session::addMessageAfterRedirect(__s('Write in item history', 'example'));
                    $changes = [0, 'old value', 'new value'];
                    foreach ($ids as $id) {
                        if ($item->getFromDB($id)) {
                            Session::addMessageAfterRedirect('- ' . $item->getField('name'));
                            Log::history(
                                $id,
                                'Computer',
                                $changes,
                                Example::class,
                                Log::HISTORY_PLUGIN,
                            );
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

            case 'do_nothing':
                if ($item->getType() == Example::class) {
                    Session::addMessageAfterRedirect(__s('Right it is the type I want...', 'example'));
                    Session::addMessageAfterRedirect(__s(
                        'But... I say I will do nothing for:',
                        'example',
                    ));
                    foreach ($ids as $id) {
                        if ($item->getFromDB($id)) {
                            Session::addMessageAfterRedirect('- ' . $item->getField('name'));
                            $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                        } else {
                            // Example for noright / Maybe do it with can function is better
                            $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                        }
                    }
                } else {
                    $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
                }

                return;
        }
        parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
    }

    public static function generateLinkContents($link, CommonDBTM $item, bool $safe_url = true)
    {
        if (strstr($link, '[EXAMPLE_ID]')) {
            $link = str_replace('[EXAMPLE_ID]', (string) $item->getID(), $link);

            return [$link];
        }

        return parent::generateLinkContents($link, $item, $safe_url);
    }

    public static function dashboardTypes()
    {
        return [
            'example' => [
                'label'    => __s('Plugin Example', 'example'),
                'function' => Example::class . '::cardWidget',
                'image'    => 'https://via.placeholder.com/100x86?text=example',
            ],
            'example_static' => [
                'label'    => __s('Plugin Example (static)', 'example'),
                'function' => Example::class . '::cardWidgetWithoutProvider',
                'image'    => 'https://via.placeholder.com/100x86?text=example+static',
            ],
        ];
    }

    public static function dashboardCards($cards = [])
    {
        if (is_null($cards)) {
            $cards = [];
        }
        $new_cards = [
            'plugin_example_card' => [
                'widgettype' => ['example'],
                'label'      => __s('Plugin Example card'),
                'provider'   => Example::class . '::cardDataProvider',
            ],
            'plugin_example_card_without_provider' => [
                'widgettype' => ['example_static'],
                'label'      => __s('Plugin Example card without provider'),
            ],
            'plugin_example_card_with_core_widget' => [
                'widgettype' => ['bigNumber'],
                'label'      => __s('Plugin Example card with core provider'),
                'provider'   => Example::class . '::cardBigNumberProvider',
            ],
        ];

        return array_merge($cards, $new_cards);
    }

    public static function cardWidget(array $params = [])
    {
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
        $html = "<div class='card' style='background-color: {$p['color']};'>";
        $html .= "<h2>{$p['title']}</h2>";
        $html .= '<ul>';
        foreach ($p['data'] as $line) {
            $html .= "<li>$line</li>";
        }
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    public static function cardDataProvider(array $params = [])
    {
        $default_params = [
            'label' => null,
            'icon'  => 'fas fa-smile-wink',
        ];
        $params = array_merge($default_params, $params);

        return [
            'title' => $params['label'],
            'icon'  => $params['icon'],
            'data'  => [
                'test1',
                'test2',
                'test3',
            ],
        ];
    }

    public static function cardWidgetWithoutProvider(array $params = [])
    {
        $default = [
            // this property is "pretty" mandatory,
            // as it contains the colors selected when adding widget on the grid send
            // without it, your card will be transparent
            'color' => '',
        ];
        $p = array_merge($default, $params);

        // you need to encapsulate your html in div.card to benefit core style
        $html = "<div class='card' style='background-color: {$p['color']};'>
                  static html (+optional javascript) as card is not matched with a data provider

                  <img src='https://www.linux.org/images/logo.png'>
               </div>";

        return $html;
    }

    public static function cardBigNumberProvider(array $params = [])
    {
        return [
            'number' => random_int(0, mt_getrandmax()),
            'url'    => 'https://www.linux.org/',
            'label'  => 'plugin example - some text',
            'icon'   => 'fab fa-linux', // font awesome icon
        ];
    }
}
