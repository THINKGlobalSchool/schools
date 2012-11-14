<?php
/**
 * Extend the welcome plugin's checklist module
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

// Display a close link
$close_url = elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/welcome/dismiss?name=checklist');
$close_link = elgg_view('output/confirmlink', array(
	'href' => $close_url,
	'text' => elgg_echo('schools:label:closechecklist'),
	'confirm' => elgg_echo('welcome:checklist:dismissconfirm'),
	'class' => 'small',
));

echo $close_link;

echo elgg_view('output/url', array(
	'href' => elgg_get_site_url() . 'welcome_popup/loadpopup',
	'text' => elgg_echo('welcome:checklist:step1'),
	'class' => 'welcome-lightbox elgg-lightbox hidden',
));