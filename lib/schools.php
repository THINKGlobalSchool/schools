<?php
/**
 * Spotx Schools helper functions
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

/** Get admin page content **/
function schools_get_admin_content() {
	$title = elgg_echo('schools:menu');
	
	$content .= elgg_view("schools/controls");
		
	$content .= elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'school', 
		'full_view' => FALSE,
		'limit' => 10,
	));
	
	$body = elgg_view_layout('administration', array('content' => $content));
	
	echo elgg_view_page($title, $body, 'admin');
}

/** Get schools edit/add form **/
function schools_get_edit_content($type, $guid) {
	elgg_push_breadcrumb(elgg_echo('schools:label:main'), elgg_get_site_url() . 'pg/schools');
	
	if ($type == 'edit') {
		$title = elgg_echo('schools:title:edit');
		$school = get_entity($guid);
		elgg_push_breadcrumb($school->title, $school->getURL());
		elgg_push_breadcrumb(elgg_echo('schools:label:edit'));
		$content = elgg_view_title($title);
		if (elgg_instanceof($school, 'object', 'school')) {
			$content .= elgg_view('forms/schools/edit', array('entity' => $school));
		}
	} else if ($type == 'add') {
		elgg_push_breadcrumb(elgg_echo('schools:title:add'));
		$title = elgg_echo('schools:title:add');
		$content = elgg_view_title($title) . elgg_view('forms/schools/edit');
	}
	
	$body = elgg_view_layout('administration', array('content' => elgg_view("navigation/breadcrumbs") . $content));
	echo elgg_view_page($title, $body, 'admin');
}

function schools_get_view_content($type, $guid) {
	$school = get_entity($guid);
	
	elgg_push_breadcrumb(elgg_echo('schools:label:main'), elgg_get_site_url() . 'pg/schools');
		
	if (elgg_instanceof($school, 'object', 'school')) {
		$title = $school->title;
		elgg_push_breadcrumb($title, $school->getURL());
		$content = elgg_view_entity($school, true);
	} else {
		$content = elgg_echo('schools:error:notfound');
	}
	
	$content = elgg_view('navigation/breadcrumbs') . $content;
	
	$body = elgg_view_layout('administration', array('content' => $content));
	
	echo elgg_view_page($title, $body, 'admin');
}

function schools_get_authorize_content() {
	$title = elgg_echo('schools:label:register');
	
	$content = elgg_view('forms/schools/authorize') . $content;
	
	$body = elgg_view_layout('one_column_with_sidebar', $content);
	
	echo elgg_view_page('', $body);
}

/**
 * Prepare the add/edit form variables
 *
 * @param ElggEntity $school
 * @return array
 */
function schools_prepare_form_vars($school = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'contact_name' => '',
		'contact_phone' => '',
		'contact_email' => '',
		'contact_address' => '',
	);

	if ($school) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $school->$field;
		}
	}

	if (elgg_is_sticky_form('school-edit-form')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('school-edit-form', $field);
		}
	}

	elgg_clear_sticky_form('school-edit-form');

	return $values;
}

/** 
 * Generate a registration code for a school 
 * @param ElggEntity	$school
 * @return bool 
 */
function school_generate_registration_code($school) {
	$random = get_random_string(6);
	// This will be the public reg code
	$school->registration_code = $random;
	// This will be the private code to lookup
	$school->private_code = hash("md5", get_plugin_setting('schools_private_key', 'schools') . $school->registration_code);
	return true;
}

/** 
 * Return a school with given registration code 
 * @param string  $reg_code
 * @return mixed	
 */
function get_school_from_registration_code($reg_code) {
	$lookup_code = hash("md5", get_plugin_setting('schools_private_key', 'schools') . $reg_code);
	
	$school = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'school',
		'limit' => 1, 	// Only should be one..
		'metadata_name' => 'private_code', 
		'metadata_value' => $lookup_code
	));
	
	if ($school) {
		return $school[0];
	}
	
	return false;
} 

/** 
 * Helper function using facebookservice code to route authorization 
 * when attempting to login with facebook 
 * - This is a little messy but it works.. 
 * - Relies on valid session data from facebook ($_REQUEST['session'])
 */
function schools_authorize() {
	// Check to make sure we're setup with the facebookservice plugin
	if (!facebookservice_use_fbconnect()) {
	        forward();
	}
	
	// Init facebook with current $_REQUEST date
	$facebook = facebookservice_api();
	if (!$session = $facebook->getSession()) {
	        forward();
	}
	
	// Determine if we have a user already (same logic from facebookservice_lib)
	$values = array(
	   'plugin:settings:facebookservice:access_token' => $session['access_token'],
	   'plugin:settings:facebookservice:uid' => $session['uid'],
	);

	$users = get_entities_from_private_setting_multi($values, 'user', '', 0, '', 0);

	if ($users && count($users) == 1) {
		// Got a user.. do the usual
		facebookservice_login();
	} else {
		// New user registering, get in the middle and ask for a reg code
		schools_get_authorize_content();
	}
}

/** 
 * Assign a user to a school 
 * @param ElggUser 		$user
 * @param ElggEntity 	$school
 * @return bool
 */
function assign_user_to_school($user, $school) {
	$result = add_entity_relationship($user->getGUID(), SCHOOL_RELATIONSHIP, $school->getGUID());
	return $result;
}

/** 
 * Return an array of a schools related users
 * @param ElggEntity $school
 * @return array
 */
function get_school_users($school) {
	if (elgg_instanceof($school, 'object', 'school')) {
		return elgg_get_entities_from_relationship(array(
															'relationship' => SCHOOL_RELATIONSHIP,
															'relationship_guid' => $school->getGUID(),
															'inverse_relationship' => TRUE,
															'types' => array('user'),
															'limit' => 0,
															'offset' => 0,
															'count' => false,
														));
	}
}


/**
 * Generate a random string with numbers and letters
 * Modified from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
 * @param int $length
 */
function get_random_string($length = 10) {
    $characters = "abcdefghijklmnopqrstuvwxyz";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

