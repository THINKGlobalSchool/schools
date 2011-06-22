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

elgg_register_event_handler('init', 'system', 'schools_init');

// Init
function schools_init() {
	
	// Register and load library
	elgg_register_library('schools', elgg_get_plugins_path() . 'schools/lib/schools.php');
	elgg_load_library('schools');
		
	// Register CSS
	$schools_css = elgg_get_simplecache_url('css', 'schools/css');
	elgg_register_css('elgg.schools', $schools_css);	
		
	// Add members to the main menu
	$item = new ElggMenuItem('members', elgg_echo('Members'), 'members');
	elgg_register_menu_item('site', $item);
	
	// Define school relationship constant
	define('SCHOOL_RELATIONSHIP', 'belongs_to_school');
	
	// Register actions
	$action_base = elgg_get_plugins_path() . 'schools/actions/schools';
	elgg_register_action('schools/edit', "$action_base/edit.php", 'admin');
	elgg_register_action('schools/delete', "$action_base/delete.php", 'admin');
	elgg_register_action('schools/refresh', "$action_base/refresh.php", 'admin');
	
	// Extend profile/status (best way I can find at the moment) with a super low priority to display a users school 
	elgg_extend_view('profile/status', 'schools/details_school', 1);
	
	// Extend registration form
	elgg_extend_view('register/extend', 'schools/regcode');
	
	// Extend user summary view
	elgg_extend_view('user/elements/summary', 'schools/user_school');
	
	// Register a create handler for school entities
	elgg_register_event_handler('create', 'object', 'school_create_event_listener');
	
	// Add submenus
	elgg_register_event_handler('pagesetup','system','schools_submenus');
	
	// Register URL handler
	elgg_register_entity_url_handler('object', 'school', 'school_url');
	
	// Schools entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'schools_setup_entity_menu', 999);
	
	// User registration plugin hook handler
	elgg_register_plugin_hook_handler('register', 'user', 'schools_user_registration_handler');
	
	// Page handler
	elgg_register_page_handler('schools','schools_page_handler');
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
		case 'members':
			schools_get_members_content();
			break;
		default:
			forward('admin/schools/manage');
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
	return elgg_get_site_url() . 'admin/schools/view?guid=' . $entity->guid;
}

/**
 * Setup schools submenus
 */
function schools_submenus() {
	elgg_register_admin_menu_item('administer', 'schools');
	elgg_register_admin_menu_item('administer', 'manage', 'schools');
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

/**
 * User registration hook handler
 */
function schools_user_registration_handler($hook, $type, $return, $params) {
	// Get reg code
	$registration_code = get_input('school_registration_code');
	
	// Check for valid registration code
	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	if (!$school = get_school_from_registration_code(trim($registration_code))) {
		$params['user']->delete();
		elgg_set_ignore_access($ia);
		throw new RegistrationException(elgg_echo('schools:error:invalidcode'));
		return false;
	}
	
	// Assign user to school
	if (!assign_user_to_school($params['user'], $school)) {
		$params['user']->delete();
		elgg_set_ignore_access($ia);
		throw new RegistrationException(elgg_echo('schools:error:schoolregerror'));
		return false;
	}
	
	// Notify admins
	schools_register_notify_admins($school, $params['user']);
	
	elgg_set_ignore_access($ia);
	return true;
}

/**
 * Schools entity plugin hook
 */
function schools_setup_entity_menu($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	
	if (!elgg_instanceof($entity, 'object', 'school')) {
		return $return;
	}

	$return = array();
	
	$options = array(
		'name' => 'code',
		'text' => elgg_echo("schools:label:code", array($entity->registration_code)),
		'href' => FALSE,
		'priority' => 0,
		'item_class' => 'school-code'
	);
	$return[] = ElggMenuItem::factory($options);
	
	$options = array(
		'name' => 'refresh',
		'text' => elgg_echo('schools:label:refresh'),
		'title' => elgg_echo('schools:label:refresh'),
		'href' => elgg_get_site_url() . 'action/schools/refresh?guid=' . $entity->guid,
		'confirm' => elgg_echo('schools:label:refreshconfirm'),
		'priority' => 1,
		'link_class' => 'elgg-button elgg-button-action',
	);
	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => elgg_get_site_url() . 'admin/schools/edit?guid=' . $entity->guid,
		'priority' => 2,
	);
	$return[] = ElggMenuItem::factory($options);
	
	$options = array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete:this'),
		'href' => "action/{$params['handler']}/delete?guid={$entity->getGUID()}",
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 3,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}
