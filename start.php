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
	elgg_register_simplecache_view('css/schools/css');	
	elgg_register_css('elgg.schools', $schools_css);	
	
	// Register JS
	$schools_js = elgg_get_simplecache_url('js', 'schools/schools');
	elgg_register_simplecache_view('js/schools/schools');	
	elgg_register_js('elgg.schools', $schools_js);
		
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
	elgg_register_action('schools/register', "$action_base/register.php", 'public');
	elgg_register_action('schools/deleteuser', "$action_base/deleteuser.php", 'admin');
	elgg_register_action('schools/approve', "$action_base/approve.php", 'admin');

	// Extend profile/status (best way I can find at the moment) with a super low priority to display a users school 
	elgg_extend_view('profile/status', 'schools/details_school', 1);
	
	// Extend registration form
	elgg_extend_view('register/extend', 'forms/schools/register');
	
	// Extend user summary view
	elgg_extend_view('user/elements/summary', 'schools/user_school');
	
	// Register a create handler for school entities
	elgg_register_event_handler('create', 'object', 'school_create_event_listener');
	
	// Register a create handler for user entities
	elgg_register_event_handler('create', 'user', 'schools_user_create_listener');
	
	// Add submenus
	elgg_register_event_handler('pagesetup','system','schools_submenus');
	
	// Register URL handler
	elgg_register_entity_url_handler('object', 'school', 'school_url');
	
	// Schools entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'schools_setup_entity_menu', 999);
	
	// User registration plugin hook handler
	elgg_register_plugin_hook_handler('register', 'user', 'schools_user_registration_handler', 1);
	
	// Intercept new facebook users
	elgg_register_plugin_hook_handler('new_facebook_user', 'facebook', 'schools_new_facebook_user_intercept');
	
	// Intercept new google users
	elgg_register_plugin_hook_handler('new_google_user', 'google', 'schools_new_google_user_intercept');
	
	// canEdit override to allow not logged in code to disable a user
	elgg_register_plugin_hook_handler('permissions_check', 'user', 'schools_allow_new_user_can_edit');
	
	// Hook into the register action to duplicate the register sticky form
	elgg_register_plugin_hook_handler('action', 'register', 'schools_register_hook_handler');
	
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
		case 'school_registration':
			// Forwarded here because we need to provide a school code or enter
			// moderation queue
			if (!elgg_is_logged_in() && $_SESSION['schools_require_approval']) {
				schools_get_registration_content();
			} else {
				forward();
			}
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
	elgg_register_admin_menu_item('administer', 'pending', 'users');
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
 * New user created, if we're the valid_school_code context
 * assign this user to the school they are registering for. 
 * This requires the request variable school_guid = xxxx
 */
function schools_user_create_listener($event, $object_type, $object) {
	if (elgg_in_context('valid_school_code')) {
		$school_code = get_input('school_registration_code');
		
		$ia = elgg_get_ignore_access();
		elgg_set_ignore_access(TRUE);
		
		$school = get_school_from_registration_code(trim($school_code));

		// If we have a valid school
		if (elgg_instanceof($school, 'object', 'school') && assign_user_to_school($object, $school)) {
			// Notify admins
			schools_register_notify_admins($school, $object);
			elgg_set_ignore_access($ia);
		} else {
			$object->delete();
			elgg_set_ignore_access($ia);
			throw new RegistrationException(elgg_echo('schools:error:schoolregerror'));
		}
	}
}

/**
 * Hook handler for new facebook users
 */
function schools_new_facebook_user_intercept($hook, $type, $return, $params) {
	if (elgg_in_context('valid_school_code')) {
		// Good to go!
		return TRUE;
	} else if (elgg_in_context('register_moderated')) {
		// Create user (will be deleted if form is bad)
		$user = facebook_create_user_with_data($params['facebook'], $params['account']);
		if (elgg_instanceof($user, 'user')) {
			// Process the registration form data
			try {
				schools_process_registration_form($user);
				forward();
			} catch (RegistrationException $e) {
				register_error($e->getMessage());
				forward(REFERER);
			}
		} else {
			forward(REFERER);
		}
		
	} else {
		$_SESSION['schools_require_approval'] = 1;
		forward('schools/school_registration');
		return FALSE;
	}	
}

/**
 * New Google user intercept
 */
function schools_new_google_user_intercept($hook, $type, $return, $params) {
	if (elgg_in_context('valid_school_code')) {
		// Good to go!
		return TRUE;
	} else if (elgg_in_context('register_moderated')) {
		var_dump($params);
		die;
		
		// Process the registration form data
		try {
			schools_process_registration_form($user);
			forward();
		} catch (RegistrationException $e) {
			register_error($e->getMessage());
			forward(REFERER);
		}	
	} else {
		$_SESSION['schools_require_approval'] = 1;
		forward('schools/school_registration');
		return FALSE;
	}	
}

/**
 * User registration hook handler
 * 
 * Note, we need to delete users manually because we're throwing exceptions
 */
function schools_user_registration_handler($hook, $type, $return, $params) {
	$user = elgg_extract('user', $params);

	// Bail if we don't have a proper user
	if (!$user instanceof ElggUser) {
		return;
	}
	
	// Bail if we're cancelled by another plugin
	if (!$return) {
		return $return;
	}

	// Make sure the regular register sticky form isn't cleared on an error here
	copy_sticky_form('schools_register', 'register');

	// Get reg code
	$registration_code = get_input('school_registration_code');

	$ia = elgg_get_ignore_access();
	elgg_set_ignore_access(TRUE);
	
	// Check for valid registration code
	if ($school = get_school_from_registration_code(trim($registration_code))) {
		// Got a valid school, so assign user
		if (assign_user_to_school($user, $school)) {
			// Notify admins
			schools_register_notify_admins($school, $user);
			elgg_set_ignore_access($ia);
			return $return;
		} else {
			// Problem assigning user to school
			$user->delete();
			elgg_set_ignore_access($ia);
			throw new RegistrationException(elgg_echo('schools:error:schoolregerror'));
		}
	} else if (!$registration_code) {
		// Process the registration form data
		schools_process_registration_form($user);
		return $return;
	} else {
		// Invalid code
		$user->delete();
		elgg_set_ignore_access($ia);
		throw new RegistrationException(elgg_echo('schools:error:invalidcode'));
	}

	return $return;
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
		'section' => 'info',
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
		'section' => 'buttons',
		'link_class' => 'elgg-button elgg-button-action',
	);
	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => elgg_get_site_url() . 'admin/schools/edit?guid=' . $entity->guid,
		'section' => 'core',
		'priority' => 2,
	);
	$return[] = ElggMenuItem::factory($options);
	
	$options = array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete:this'),
		'href' => "action/{$params['handler']}/delete?guid={$entity->getGUID()}",
		'confirm' => elgg_echo('deleteconfirm'),
		'section' => 'core',
		'priority' => 3,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Override the canEdit() call for if we're in the context of registering a new user.
 *
 * @param string $hook
 * @param string $type
 * @param bool   $value
 * @param array  $params
 * @return bool|null
 */
function schools_allow_new_user_can_edit($hook, $type, $value, $params) {
	// $params['user'] is the user to check permissions for.
	// we want the entity to check, which is a user.
	$user = elgg_extract('entity', $params);

	if (!($user instanceof ElggUser)) {
		return;
	}

	$context = elgg_get_context();
	if ($context == 'schools_new_pending_user' 
		|| $context == 'register_moderated' 
		|| $context == 'valid_school_code' 
		|| 'schools_disable_user') 
	{
		return TRUE;
	}

	return;
}

// Hook handler for the register action
function schools_register_hook_handler($hook, $type, $return, $params) {
	// Make the register sticky form (action hasn't gotten to it yet at this point)
	elgg_make_sticky_form('register');

	// Copy the registration sticky form so we have something to work
	copy_sticky_form('register', 'schools_register');

	// Carry on
	return $return;
}
