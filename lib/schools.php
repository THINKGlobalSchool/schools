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

/** Get schools edit/add form **/
function schools_get_edit_content($type, $guid = NULL) {	
	elgg_push_breadcrumb(elgg_echo('admin:schools'), elgg_get_site_url() . 'admin/schools/manage');
	if ($type == 'edit') {
		$school = get_entity($guid);
		elgg_push_breadcrumb($school->title, $school->getURL());
		elgg_push_breadcrumb(elgg_echo('edit'));
		if (!elgg_instanceof($school, 'object', 'school')) {
			forward(REFERER);
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('Add'));
		$school = null;
	}
	
	$form_vars = schools_prepare_form_vars($school);
	
	$content = elgg_view('navigation/breadcrumbs');
	
	$content .= elgg_view_form('schools/edit', array('name' => 'school-edit-form', 'id' => 'school-edit-form'), $form_vars);
	
	echo $content;
}

/** Get view schools members  **/
function schools_get_members_content() {	
	$title = elgg_echo('members');
	
	$filter = get_input('filter', null);
	
	if (!$filter) {
		// If no filter, get out of here and display regular members page
		forward(elgg_get_site_url() . 'members');
	}
	
	// count members
	$num_members = get_number_users();
	
	$options = array(
		'type' => 'user',
		'full_view' => FALSE
	);
	
	if (elgg_instanceof($school = get_entity($filter), 'object', 'school')) {
		$options['relationship'] = SCHOOL_RELATIONSHIP;
		$options['relationship_guid'] = $school->getGUID();
		$options['inverse_relationship'] = TRUE;
		
	} else if (strtolower($filter) == 'tgs') { // Special TGS filter
		// Include only users with tgs domains
		global $CONFIG;
		
		// Join elgg_users_entity table so we can check the email
		$options['joins'] = "JOIN {$CONFIG->dbprefix}users_entity ue on ue.guid = e.guid";
		
		// I think a LIKE comparison is ok here..
		$options['wheres'] = "(ue.email like '%@thinkglobalschool.com' OR ue.email like '%@thinkglobalschool.org')";
	}
	
	$content = elgg_list_entities_from_relationship($options);		
	
	$params = array(
		'content' => $content,
		'sidebar' => elgg_view('members/sidebar'),
		'title' => $title . " ($num_members)",
		'filter_override' => elgg_view('members/nav', array('selected' => $filter)),
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/** Get authorize content **/
function schools_get_authorize_content() {
	$title = elgg_echo('schools:label:register');
	
	$content = elgg_view_form('schools/authorize');
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => ' ',
	);
	
	$body = elgg_view_layout('content', $params);
	
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
		'contact_website' => '',
		'guid' => NULL,
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
	$school->private_code = hash("md5", elgg_get_plugin_setting('schools_private_key', 'schools') . $school->registration_code);
	return true;
}

/** 
 * Return a school with given registration code 
 * @param string  $reg_code
 * @return mixed	
 */
function get_school_from_registration_code($reg_code) {
	$lookup_code = hash("md5", elgg_get_plugin_setting('schools_private_key', 'schools') . $reg_code);
	
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
 * Return an array of a schools related users
 * @param ElggUser $school
 * @return array
 */
function get_user_school($user) {
	if ($user) {
	
		
		$school = elgg_get_entities_from_relationship(array(
															'relationship' => SCHOOL_RELATIONSHIP,
															'relationship_guid' => $user->getGUID(),
															'inverse_relationship' => FALSE,
															'types' => array('object'),
															'subtype' => 'school',
 															'limit' => 1,
															'offset' => 0,
															'count' => false,
														));
		return $school[0];
	}
}

/**
 * Helper function to grab a link, or a title
 * of a users school. If there is no school, check 
 * users email, if TGS, then return TGS info
 * @param ElggUser $user
 * @return string
 */
function get_user_school_info($user) {
	$user_school = get_user_school($user);
	
	// If we have a school, great, display it
	if ($user_school) {
		if ($user_school->contact_website) {
			$school_info = "<a href='" . $user_school->contact_website . "'>" . $user_school->title . "</a>";
		} else {
			$school_info = $user_school->title;
		}
		return $school_info;
	} else {
		// Check for TGS domain
		if (preg_match("/.*?(thinkglobalschool)/is", $user->email)) {
			return "<a href='http://www.thinkglobalschool.org'>THINK Global School</a>";
		}
		// Nothing..
		return false;
	}
}

/** 
 * Helper function to grab and notifiy admin users that 
 * a new user has registered with a school
 * @param ElggEntity 	$school
 * @param ElggUser 		$user
 * @return bool
 */
function schools_register_notify_admins($school, $user) {
	global $CONFIG;
	
	// Get admins
	$admins = elgg_get_entities_from_metadata(array(
		'type' => 'user',
		'limit' => 0,
		'joins' => array("JOIN {$CONFIG->dbprefix}users_entity ue on ue.guid = e.guid"),
		'wheres' => array('ue.admin = "yes"'),
	));
	
	foreach($admins as $admin) {
		if ($admin) {
			$user_link = "<a href='{$user->getURL()}'>{$user->name}</a>";
			$school_link = "<a href='{$school->getURL()}'>{$school->title}</a>";
		
			notify_user( 
				$admin->getGUID(), $CONFIG->site->guid, 
				elgg_echo('schools:notifyadmin:subject'), 
				elgg_echo('schools:notifyadmin:body', array($user_link, $school_link))
			);
		}
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
