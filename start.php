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
	
	// Register actions
	$action_base = elgg_get_plugin_path() . 'schools/actions/schools';
	elgg_register_action('schools/edit', "$action_base/edit.php", 'admin');
	elgg_register_action('schools/delete', "$action_base/delete.php", 'admin');
	elgg_register_action('schools/refresh', "$action_base/refresh.php", 'admin');
	elgg_register_action('schools/register', "$action_base/register.php", 'public');
	elgg_register_action('schools/authorize', "$action_base/authorize.php", 'public');
	
	// Register a create handler for school entities
	elgg_register_event_handler('create', 'object', 'school_create_event_listener');
	
	// Add submenus
	register_elgg_event_handler('pagesetup','system','schools_submenus');
	
	// Page handler
	register_page_handler('schools','schools_page_handler');
	
	// Register URL handler
	register_entity_url_handler('school_url','object', 'school');
}

/**
* Schools Page Handler
* 
* @param array $page From the page_handler function
* @return true|false Depending on success
*
*/
function schools_page_handler($page) {	

	$page_type = $page[0];
	switch($page_type) {
		case 'edit':
			set_context('admin');
			admin_gatekeeper();
			schools_get_edit_content($page_type, $page[1]);
			break;
		case 'add': 
			set_context('admin');
			admin_gatekeeper();
			schools_get_edit_content($page_type, $page[1]);
			break;
		case 'view':
			set_context('admin');
			admin_gatekeeper();
			schools_get_view_content($page_type, $page[1]);
			break;
		case 'facebookauthorize':
			schools_authorize();
			break;
		default:
			set_context('admin');
			admin_gatekeeper();
			schools_get_admin_content();
			break;
	}
	
	return true;
}

/**
 * Populates the ->getUrl() method for school entities
 *
 * @param ElggEntity entity
 * @return string request url
 */
function school_url($entity) {
	return elgg_get_site_url() . "pg/schools/view/{$entity->guid}/";
}


/**
 * Setup schools submenus
 */
function schools_submenus() {
	$item = array(
		'text' => elgg_echo('schools:menu'),
		'href' => elgg_get_site_url() . "pg/schools",
	);

	elgg_add_submenu_item($item, 'admin');
}

/**
 * School created, generate registration code
 */
function school_create_event_listener($event, $object_type, $object) {
	if ($object->getSubtype() == 'school') {
		school_generate_registration_code($object);
	}
	return true;
}