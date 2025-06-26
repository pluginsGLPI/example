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
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

namespace GlpiPlugin\Example;

use Rule;

/**
* Rule class store all informations about a GLPI rule :
*   - description
*   - criterias
*   - actions
*
**/
class RuleTest extends Rule
{
    // From Rule
    public static $rightname = 'rule_import';
    public $can_sort         = true;

    public function getTitle()
    {
        return 'test';
    }

    public function maxActionsCount()
    {
        return 1;
    }

    public function getCriterias()
    {
        $criterias                  = [];
        $criterias['name']['field'] = 'name';
        $criterias['name']['name']  = __('Software');
        $criterias['name']['table'] = 'glpi_softwares';

        return $criterias;
    }

    public function getActions()
    {
        $actions                                   = [];
        $actions['softwarecategories_id']['name']  = __('Category (class)', 'example');
        $actions['softwarecategories_id']['type']  = 'dropdown';
        $actions['softwarecategories_id']['table'] = 'glpi_softwarecategories';

        return $actions;
    }
}
