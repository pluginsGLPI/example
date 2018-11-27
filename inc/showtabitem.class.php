<?php

/*
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

/**
 * Summary of PluginExampleShowtabitem
 * Example of pre_show_xxx and post_show_xxx implementation
 *
 *
 * pre_show_item will be fired before an item is shown
 *    ex: when viewing a ticket, change, computer,...
 *
 *    will be fired at each sub-item
 *    ex: for each TicketTask, ITILFollowup, ...
 *
 * post_show_item will be fired after the item show
 *
 *
 * pre_show_tab will be fired before a tab is shown
 *    when tabs are loaded,
 *    ex: when viewing the Followup tab
 *
 * post_show_tab will be fired after the tab show
 *
 * */
class PluginExampleShowtabitem {

   /**
    * Summary of pre_show_tab
    * @param array $params is an array like following
    *                array( 'item', 'options')
    *                where 'item' is the parent object (like 'Ticket'),
    *                and 'options' are options like following
    *                  array( 'tabnum', 'itemtype')
    *                  where 'tabnum' is the internal name of the tab that will be shown
    *                  and 'itemtype' is the type of the tab (ex: 'ITILFollowup' when showing followup tab in a ticket)
    * Note: you may pass datas to post_show_tab using the $param['options'] array (see example below)
    */
   static function pre_show_tab($params) {
      switch ($params['item']->getType()) {
         case 'Ticket':
            if ($params['options']['itemtype']=='Ticket' && $params['options']['tabnum']==2) {
               // if tasks are not all done
               // then prevent solution div to show
               // this is an example to prevent solving of ticket
               if (true) { // here you should test if some tasks are in todo status.
                  $params['options']['prevent_solution'] = true; // this will be passed to the post_show hook
                  echo "<div id='toHideSolution' style='display: none;'>"; // in order to hide the default solution div
               }
            }
      }
   }

   /**
    * Summary of post_show_tab
    * @param array $params is identical to pre_show_tab parameter
    * Note: you may get datas from pre_show_tab in $param['options'] array (see example below)
    */
   static function post_show_tab($params) {
      switch ($params['item']->getType()) {
         case 'Ticket':
            if (isset($params['options']['prevent_solution'])) {
               echo "</div>";
               echo "<div style='margin-bottom: 20px;' class='box'>
                                <div class='box-tleft'>
                                    <div class='box-tright'>
                                        <div class='box-tcenter'>
                                        </div>
                                    </div>
                                </div>
                                <div class='box-mleft'>
                                    <div class='box-mright'>
                                        <div class='box-mcenter'>
                                            <h3>
                                                <span class='red'>"."Can't solve ticket"."
                                                    <br>
                                                </span>
                                            </h3>
                                            <h3>
                                            <span >"."Tasks are waiting to be done"."
                                                </span>
                                            </h3>
                                        </div>
                                     </div>
                                 </div>
                                 <div class='box-bleft'>
                                    <div class='box-bright'>
                                        <div class='box-bcenter'>
                                        </div>
                                    </div>
                                 </div>
                              </div>  ";
            }
            break;

         case 'Computer':
            break;
      }
   }

   /**
    * Summary of pre_show_item
    * @param array $params is an array like following
    *                array( 'item', 'options')
    *                where 'item' is the object to show (like 'Ticket', 'TicketTask', ...),
    *                BEWARE that sometimes it can be an array of data and not an object (ex: for solution item)
    *                and 'options' are options like following
    *                if item is a main object like a ticket, change, problem, ... then it contains
    *                  array( 'id' )
    *                  where 'id' is the id of object that will be shown (same than $param['item']->fields['id'])
    *                or if item contains a sub-object like followup, task, ... then it contains
    *                  array( 'parent', 'rand', 'showprivate')
    *                  where 'parent' is the main object related to the current item (ex: if 'item' is ITILFollowup then it will be the related Ticket)
    *                  and 'rand' contains the random number that will be used to render the item
    *                  and 'showprivate' is the right to show private items
    * Note: you may pass datas to post_show_item using the $param['options'] array
    */
   static function pre_show_item($params) {
      if (!is_array($params['item'])) {
         switch ($params['item']->getType()) {
            case 'Ticket':
               //echo 'test' ;
               break;
            case 'TicketTask' :
               //echo 'test' ;
               break;
            case 'ITILFollowup' :
               //echo 'test' ;
               break;
         }
      } else {
         // here we are going to view a Solution
         return;
      }
   }

   /**
    * Summary of post_show_item
    * @param array $params is an array like following
    *                array( 'item', 'options')
    *                where 'item' is the object to show (like 'Ticket', 'TicketTask', ...),
    *                and 'options' are options like following
    *                if item is a main object like a ticket, change, problem, ... then it contains
    *                  array( 'id' )
    *                  where 'id' is the id of object that will be shown (same than $param['item']->fields['id'])
    *                or if item contains a sub-object like followup, task, ... then it contains
    *                  array( 'parent', 'rand', 'showprivate')
    *                  where 'parent' is the main object related to the current item (ex: if 'item' is ITILFollowup then it will be the related Ticket)
    *                  and 'rand' contains the random number that will be used to render the item
    *                  and 'showprivate' is the right to show private items
    * Note: you may get datas from pre_show_item using the $param['options'] array
    */
   static function post_show_item($params) {
      if (!is_array($params['item'])) {
         switch ($params['item']->getType()) {
            case 'Ticket':
               //echo 'test' ;
               break;
            case 'TicketTask' :
               //echo 'test' ;
               break;
            case 'ITILFollowup' :
               //echo 'test' ;
               break;
         }
      } else {
         // here we are going to view a Solution
         return;
      }
   }

}
