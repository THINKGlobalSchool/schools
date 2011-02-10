<?php
/**
 * Spotx Schools controls view
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$style = "<style type='text/css'>" . elgg_view('schools/admin_css') . "</style>";

$content = "<div class='school-header'>
				<div class='school-header-title'>" . elgg_view_title(elgg_echo('schools:title:admin')) . "</div>";
$content .= "<div class='school-controls'><a href='". elgg_get_site_url() . "pg/schools/add' class='school-action-button'>" . elgg_echo('schools:label:new') . "</a></div><div style='clear:both;'></div></div>";

echo $style . $content;

