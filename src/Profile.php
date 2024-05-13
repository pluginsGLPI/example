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

namespace GlpiPlugin\Example;

use CommonGLPI;
use Html;
use Session;

final class Profile extends \Profile
{
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        return __('Example plugin');
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        $profile = new self();
        $profile->showFormExample($item->getID());
    }

    public function showFormExample(int $profiles_id): void
    {
        if (!$this->can($profiles_id, READ)) {
            return;
        }

        echo "<div class='spaced'>";

        $can_edit = Session::haveRight(self::$rightname, UPDATE);
        if ($can_edit) {
            echo "<form method='post' action='" . htmlspecialchars(self::getFormURL()) . "'>";
        }

        $matrix_options = [
            'canedit' => $can_edit,
        ];
        $rights = [
            [
                'itemtype' => Example::class,
                'label' => Example::getTypeName(Session::getPluralNumber()),
                'field' => Example::$rightname
            ]
        ];
        $matrix_options['title'] = self::getTypeName(1);
        $this->displayRightsChoiceMatrix($rights, $matrix_options);

        if ($can_edit) {
            echo "<div class='text-center'>";
            echo Html::hidden('id', ['value' => $profiles_id]);
            echo Html::submit(_sx('button', 'Save'), ['name' => 'update']);
            echo "</div>\n";
            Html::closeForm();
        }
        echo '</div>';
    }
}
