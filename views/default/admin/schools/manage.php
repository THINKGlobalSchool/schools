<?php
/**
 * Spotx Schools Admin 
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

elgg_load_css('elgg.schools');

$content = "<a href='". elgg_get_site_url() . "admin/schools/add' class='elgg-button elgg-button-action'>" . elgg_echo('schools:label:new') . "</a>";


$schools_label = elgg_echo('schools:label:currentschools');
$schools .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'school', 
	'full_view' => FALSE,
	'limit' => 10,
));

$content .= elgg_view_module('inline', $schools_label, $schools);

echo $content;