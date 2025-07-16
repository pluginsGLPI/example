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

use CommonDBTM;

class Computer extends CommonDBTM
{
    public static function showInfo()
    {
        echo '<table class="tab_glpi" width="100%">';
        echo '<tr>';
        echo '<th>' . __('More information') . '</th>';
        echo '</tr>';
        echo '<tr class="tab_bg_1">';
        echo '<td>';
        echo __('Test successful');
        echo '</td>';
        echo '</tr>';
        echo '</table>';
    }

    public static function item_can($item)
    {
        if (($item->getType() == 'Computer')
            && ($item->right == READ)
            && ($item->fields['groups_id'] > 0)
            && !in_array($item->fields['groups_id'], $_SESSION['glpigroups'])) {
            $item->right = 0; // unknown, so denied.
        }
    }

    public static function add_default_where($in)
    {
        list($itemtype, $condition) = $in;
        if ($itemtype == 'Computer') {
            $table = getTableForItemType($itemtype);
            $condition .= ' (' . $table . '.groups_id NOT IN (' . implode(',', $_SESSION['glpigroups']) . '))';
        }

        return [$itemtype, $condition];
    }
}
