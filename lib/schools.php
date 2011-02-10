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
	$random = get_random_string(15);
	$school->random_code = $random; // Used to generate the reg code hash
	// This will be the public reg code
	$school->registration_code = hash("md5", $school->getGUID() . $random);
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
		return $school;
	}
	
	return false;
} 


/**
 * Generate a random string with numbers and letters
 * Modified from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
 * @param int $length
 */
function get_random_string($length = 10) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
