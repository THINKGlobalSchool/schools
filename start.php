<?php
/**
 * Spotx Schools
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

register_elgg_event_handler('init', 'system', 'schools_init');

// Init
function schools_init() {
	include_once('lib/schools.php');
	
	// Actions	
	
	// Add submenus
	register_elgg_event_handler('pagesetup','system','schools_submenus');
	
	// Page handler
	register_page_handler('schools','schools_page_handler');
}

/**
* Schools Page Handler
* 
* @param array $page From the page_handler function
* @return true|false Depending on success
*
*/
function schools_page_handler($page) {	
	set_context('admin');
	elgg_admin_add_plugin_settings_sidemenu();
	
	$title = elgg_echo('assign:menu');
	$content = schools_get_admin_content();
	$body = elgg_view_layout('administration', array('content' => $content));
	
	echo elgg_view_page($title, $body, 'admin');
}

/**
 * Setup assign submenus
 */
function schools_submenus() {
	$item = array(
		'text' => elgg_echo('schools:menu'),
		'href' => elgg_get_site_url() . "pg/schools",
	);

	elgg_add_submenu_item($item, 'admin');
}
