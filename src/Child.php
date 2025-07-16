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

use CommonDBChild;
use Session;

// Sample of class that inherit from CommonDBChild. The behaviour of CommonRelation is similar.
// The main evolution introduced by 0.84 version of GLPI is a stronger control and log of
// interactions.  We suggest you to refer to the header of CommonDBConnexity class to see these
// enhancements.
// For CommonDBRelation, the variable are quiet equivalent, but they use _1 and _2 for each side
// parent
class Child extends CommonDBChild
{
    // A child rely on an item. If $itemtype=='itemtype', then that is a variable item.
    public static $itemtype = 'itemtype';
    public static $items_id = 'items_id';

    // With 0.84, you have to specify each right (create, view, update and delete), because
    // CommonDBChild(s) and CommonDBRelation(s) mainly depend on the rights on the parent item
    // All these methods rely on parent:can*. Two attributs are usefull :
    // * $checkParentRights: define what to check regarding the parent :
    //         - CommonDBConnexity::DONT_CHECK_ITEM_RIGHTS  don't eaven relly on parents rights
    //         - CommonDBConnexity::HAVE_VIEW_RIGHT_ON_ITEM view right on the item is enough
    //         - CommonDBConnexity::HAVE_SAME_RIGHT_ON_ITEM we must have at least update right
    //                                                      on the item
    // * $mustBeAttached: some CommonDBChild can be free, without any parent.
    public static function canCreate(): bool
    {
        return (Session::haveRight('internet', UPDATE)
                && parent::canCreate());
    }

    public static function canView(): bool
    {
        return (Session::haveRight('internet', READ)
                && parent::canView());
    }

    public static function canUpdate(): bool
    {
        return (Session::haveRight('internet', UPDATE)
                && parent::canUpdate());
    }

    public static function canDelete(): bool
    {
        return (Session::haveRight('internet', DELETE)
                && parent::canDelete());
    }

    // By default, post_addItem, post_updateItem and post_deleteFromDB are defined.
    // They define the history to add to the parents
    // This method define the name to set inside the history of the parent.
    // All these methods use $log_history_add, $log_history_update and $log_history_delete to
    // define the level of log (Log::HISTORY_ADD_DEVICE, Log::HISTORY_UPDATE_DEVICE ...)
    public function getHistoryName_for_item($case) {}

    // CommonDBChild also check if we can add or updatethe item regarding the new item
    // ($input[static::$itemtype] and $input[static::$items_id]).
    // But don't forget to call parent::prepareInputForAdd()
    public function prepareInputForAdd($input)
    {
        // My preparation on $input
        return parent::prepareInputForAdd($input);
    }
}
