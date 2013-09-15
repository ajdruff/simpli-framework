<?php
/**
 * Checkbox Shortcode Template
 *
 * Template defining the layout for the shortcode's output
 *
 * @author Andrew Druffner <andrew@nomstock.com>
 * @copyright  2012-2013 Nomstock, LLC
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    SVN: $Id$
 * @package SimpliFramework
 * @subpackage Shortcodes
 * @filesource
 */

?>
<div id="container-{name}-{form_counter}-id" class="{control-group-class}">

    <label for="{name}-{form_counter}-id" class="{control-label-class}" style="{control-label-style}">{helptip_html}&nbsp;{label}</label>




    <div class="{controls-class}">


        <input type="checkbox" name="{name}" id="{name}-{form_counter}-id"  class="{class}" style="{style}" value="{value}" {checked_html}/>




               <span class="help-inline"><!--Validation Errors will appear here--></span>
    </div>
</div>
{javascript_depends_on}