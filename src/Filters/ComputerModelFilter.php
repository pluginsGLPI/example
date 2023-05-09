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

namespace GlpiPlugin\Example\Filters;

use ComputerModel;
use DBmysql;
use Glpi\Dashboard\Filters\AbstractFilter;

class ComputerModelFilter extends AbstractFilter
{
    public static function getName(): string
    {
        return __("Computer model");
    }

    public static function getId(): string
    {
        return "plugin_example_computer_model";
    }

    public static function getHtml(string $values = ""): string
    {
        return self::displayList(
            self::getName(),
            $values,
            self::getId(),
            ComputerModel::class
        );
    }

    public static function getCriteria(
        DBmysql $DB,
        string $table = "",
        array $apply_filters = []
    ): array {
        $field = ComputerModel::getForeignKeyField();

        if (
            $DB->fieldExists($table, $field)
            && isset($apply_filters[self::getId()])
        ) {
            return [
                "WHERE" => [
                    "$table.$field" => $apply_filters[self::getId()]
                ]
            ];
        }

        return [];
    }

    public static function getSearchCriteria(
        DBmysql $DB,
        string $table = "",
        array $apply_filters = []
    ): array {
        $criteria = [];
        $field = ComputerModel::getForeignKeyField();

        if (
            $DB->fieldExists($table, ComputerModel::getForeignKeyField())
            && isset($apply_filters[self::getId()])
        ) {
            $criteria[] = [
                'link'       => 'AND',
                'searchtype' => 'equals',
                'value'      => (int) $apply_filters[self::getId()],
                'field'      => self::getSearchOptionID(
                    $table,
                    $field,
                    ComputerModel::getTable()
                ),
            ];
        }


        return $criteria;
    }
}
