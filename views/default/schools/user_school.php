<?php
/**
 * Spotx Schools user details extender
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */
elgg_load_css('elgg.schools');
echo "<div class='elgg-subtext schools-info'>" . get_user_school_info($vars['entity']) . "</div>";