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

namespace GlpiPlugin\Example\RuleCriteria;

class RandomCriteria
{
    public static function getRuleCriteria(): array
    {
        $criteria = [];

        $criteria['_plugin_example_random']['name'] = __('Random (50% success)');
        $criteria['_plugin_example_random']['type'] = 'yesno';

        return $criteria;
    }

    public static function ruleCollectionPrepareInputDataForProcess($options)
    {
        $results = [];
        $results['_plugin_example_random'] = mt_rand(0, 1);

        return $results;
    }
}
