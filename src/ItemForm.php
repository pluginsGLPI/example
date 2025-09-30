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

use Glpi\Application\View\TemplateRenderer;
use Html;
use Ticket;

/**
 * Summary of GlpiPlugin\Example\ItemForm
 * Example of *_item_form implementation
 * @see http://glpi-developer-documentation.rtfd.io/en/master/plugins/hooks.html#items-display-related
 * */
class ItemForm
{
    /**
     * Display contents at the begining of ITILObject section (right panel).
     *
     * @param array $params Array with "item" and "options" keys
     *
     * @return void
     */
    public static function preSection($params)
    {
        echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
      <section class="accordion-item" aria-label="a label">
      <h2 class="accordion-header" id="example-heading" title="example-heading-id" data-bs-toggle="tooltip">
         <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#example-pre-content" aria-expanded="true" aria-controls="example-pre-content">
            <i class="ti ti-world me-1"></i>
            <span class="item-title">
               Example pre section
            </span>
         </button>
      </h2>
      <div id="example-pre-content" class="accordion-collapse collapse" aria-labelledby="example-pre-content-heading">
         <div class="accordion-body">
            Example pre section
         </div>
      </div>
   </section>
TWIG, []);
    }

    /**
     * Display contents at the end of ITILObject section (right panel).
     *
     * @param array $params Array with "item" and "options" keys
     *
     * @return void
     */
    public static function postSection($params)
    {
        echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
      <section class="accordion-item" aria-label="a label">
      <h2 class="accordion-header" id="example-heading" title="example-heading-id" data-bs-toggle="tooltip">
         <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#example-post-content" aria-expanded="true" aria-controls="example-post-content">
            <i class="ti ti-world me-1"></i>
            <span class="item-title">
               Example post section
            </span>
         </button>
      </h2>
      <div id="example-post-content" class="accordion-collapse collapse" aria-labelledby="example-post-content-heading">
         <div class="accordion-body">
            Example post section
         </div>
      </div>
   </section>
TWIG, []);
    }

    /**
     * Display contents at the begining of item forms.
     *
     * @param array $params Array with "item" and "options" keys
     *
     * @return void
     */
    public static function preItemForm($params)
    {
        $item    = $params['item'];
        $options = $params['options'];

        $firstelt = ($item::getType() == Ticket::class ? 'th' : 'td');

        $out = '<tr><th colspan="' . (isset($options['colspan']) ? $options['colspan'] * 2 : '4') . '">';
        $out .= sprintf(
            __s('Start %1$s hook call for %2$s type'),
            'pre_item_form',
            $item::getType(),
        );
        $out .= '</th></tr>';

        $out .= "<tr><$firstelt>";
        $out .= '<label for="example_pre_form_hook">' . __s('First pre form hook') . '</label>';
        $out .= "</$firstelt><td>";
        $out .= '<input type="text" name="example_pre_form_hook" id="example_pre_form_hook"/>';
        $out .= "</td><$firstelt>";
        $out .= '<label for="example_pre_form_hook2">' . __s('Second pre form hook') . '</label>';
        $out .= "</$firstelt><td>";
        $out .= '<input type="text" name="example_pre_form_hook2" id="example_pre_form_hook2"/>';
        $out .= '</td></tr>';

        $out .= '<tr><th colspan="' . (isset($options['colspan']) ? $options['colspan'] * 2 : '4') . '">';
        $out .= sprintf(
            __s('End %1$s hook call for %2$s type'),
            'pre_item_form',
            $item::getType(),
        );
        $out .= '</th></tr>';

        echo $out;
    }

    /**
     * Display contents at the begining of item forms.
     *
     * @param array $params Array with "item" and "options" keys
     *
     * @return void
     */
    public static function postItemForm($params)
    {
        $item    = $params['item'];
        $options = $params['options'];

        $firstelt = ($item::getType() == Ticket::class ? 'th' : 'td');

        $out = '<tr><th colspan="' . (isset($options['colspan']) ? $options['colspan'] * 2 : '4') . '">';
        $out .= sprintf(
            __s('Start %1$s hook call for %2$s type'),
            'post_item_form',
            $item::getType(),
        );
        $out .= '</th></tr>';

        $out .= "<tr><$firstelt>";
        $out .= '<label for="example_post_form_hook">' . __s('First post form hook') . '</label>';
        $out .= "</$firstelt><td>";
        $out .= '<input type="text" name="example_post_form_hook" id="example_post_form_hook"/>';
        $out .= "</td><$firstelt>";
        $out .= '<label for="example_post_form_hook2">' . __s('Second post form hook') . '</label>';
        $out .= "</$firstelt><td>";
        $out .= '<input type="text" name="example_post_form_hook2" id="example_post_form_hook2"/>';
        $out .= '</td></tr>';

        $out .= '<tr><th colspan="' . (isset($options['colspan']) ? $options['colspan'] * 2 : '4') . '">';
        $out .= sprintf(
            __s('End %1$s hook call for %2$s type'),
            'post_item_form',
            $item::getType(),
        );
        $out .= '</th></tr>';

        echo $out;
    }

    public static function timelineActions($params = [])
    {
        $rand   = $params['rand'];
        $ticket = $params['item'];

        if (get_class($ticket) !== 'Ticket') {
            return false;
        }

        $edit_panel = 'viewitem' . $ticket->fields['id'] . $rand;
        $JS         = <<<JAVASCRIPT
      $(function() {
         $(document).on('click', '#email_transfer_{$rand}', function(event) {
            $('#{$edit_panel}').html('email send');
         });
      });
JAVASCRIPT;

        echo "<li class='followup' id='email_transfer_$rand'>
            <i class='far fa-envelope'></i>" .
              __s('Send a notification') .
              Html::scriptBlock($JS) . '
        </li>';
    }
}
