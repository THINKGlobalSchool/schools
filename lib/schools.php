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

/** Get further registration content **/
function schools_get_registration_content() {
	$title = elgg_echo('schools:label:register');
	
	$content = elgg_view_form('schools/register', NULL, array(
		'is_hook_form' => TRUE,
	));
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => ' ',
	);
	
	$body = elgg_view_layout('content', $params);
	
	echo elgg_view_page('', $body);
}

function schools_get_request_code_content() {
	$title = elgg_echo('schools:label:requestcode');
	
	$params = array(
		'content' => elgg_view_form('schools/request', array('name' => 'schools_request')),
		'title' => $title,
	);
	
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
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
 * Reusable registration form processing
 */
function schools_process_registration_form($user) {
	// No reg code, so check for more info
	$role        = get_input('reg_school_role');
	$role_other  = get_input('reg_school_role_other');
	$school_name = get_input('reg_school_name');
	$school_url  = get_input('reg_school_url');
	$about_url   = get_input('reg_about_url'); // Not required
	
	// Check required fields (everything except about url)
	if (!$role || ($role == 3 && !$role_other) || !$school_name || !$school_url ) {
		$user->delete();
		throw new RegistrationException(elgg_echo('schools:error:requiredfields'));
	}

	// Good to go with form inputs, now disable the user
	// set context so our canEdit() override works
	elgg_push_context('schools_new_pending_user');
	$hidden_entities = access_get_show_hidden_status();
	access_show_hidden_entities(TRUE);

	// Store provided info as user metadata
	$user->reg_school_role = ($role == '3') ? $role_other : elgg_echo('schools:label:regrole_' . $role);
	$user->reg_school_name = $school_name;
	$user->reg_school_url = $school_url;
	$user->reg_about_url = $about_url;
	
	// Create registration metadata
	create_metadata($user->guid, "reg_school_role", ($role == '3') ? $role_other : elgg_echo('schools:label:regrole_' . $role), 'text', $user->guid);
	create_metadata($user->guid, "reg_school_name", $school_name, 'text', $user->guid);
	create_metadata($user->guid, "reg_school_url", $school_url, 'text', $user->guid);
	create_metadata($user->guid, "reg_about_url", $about_url, 'text', $user->guid);

	// Non-recursive disable
	$user->disable('schools_new_pending_user', FALSE);
	
	// Set user as unnapproved (and unvalidated) and send admin notification
	schools_set_user_approved_status($user->guid, FALSE);
	schools_register_notify_admins_pending($user->guid);
	
	// Reset context and hidden entities
	elgg_pop_context();
	access_show_hidden_entities($hidden_entities);
	
	// Clear the sticky forms
	elgg_clear_sticky_form('schools_register');
	elgg_clear_sticky_form('register');
	
	// Seriously hacky way of preventing the 'registeration success' message
	// without having to return false, or 'die'
	global $CONFIG;
	$CONFIG->translations[get_current_language()]['registerok'] = '';
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
	$ia = elgg_get_ignore_access($ia);
	elgg_set_ignore_access(TRUE);

	$user_school = get_user_school($user);
	
	// If we have a school, great, display it
	if ($user_school) {
		if ($user_school->contact_website) {
			$school_info = "<a href='" . $user_school->contact_website . "'>" . $user_school->title . "</a>";
		} else {
			$school_info = $user_school->title;
		}
		elgg_set_ignore_access($ia);
		return $school_info;
	} else {
		// Check for TGS domain
		if (preg_match("/.*?(thinkglobalschool)/is", $user->email)) {
			elgg_set_ignore_access($ia);
			return "<a href='http://www.thinkglobalschool.org'>THINK Global School</a>";
		}
		// Nothing..
		elgg_set_ignore_access($ia);
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
	$admins = schools_get_admins();
	
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
 * Helper function to grab and notifiy admin users that 
 * a new school has been requested
 * @param ElggEntity 	$school
 * @param ElggUser 		$user
 * @return bool
 */
function schools_request_notify_admins($school) {	
	global $CONFIG;

	$admins = schools_get_admins();
	
	foreach($admins as $admin) {
		if ($admin) {
			notify_user( 
				$admin->getGUID(),
				$CONFIG->site->guid, 
				elgg_echo('schools:notifyadminrequest:subject'), 
				elgg_echo('schools:notifyadminrequest:body', array(
					$school->title,
					$school->description,
					$school->contact_website,
					$school->contact_name,
					$school->contact_phone,
					$school->contact_email,
					$school->contact_address,
					elgg_get_site_url() . 'admin/schools/managerequests',
				))
			);
		}
	}
}

/**
 * Grab admin users
 */
function schools_get_admins() {
	global $CONFIG;

	// Get admins
	$admins = elgg_get_entities_from_metadata(array(
		'type' => 'user',
		'limit' => 0,
		'joins' => array("JOIN {$CONFIG->dbprefix}users_entity ue on ue.guid = e.guid"),
		'wheres' => array('ue.admin = "yes"'),
	));
	
	return $admins;
}

/**
 * Helper to notify admins that a new user has attempted to register
 *
 * @param int   $user_guid The user's GUID
 * @return mixed
 */
function schools_register_notify_admins_pending($user_guid) {
	global $CONFIG;
	$site = elgg_get_site_entity();

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user) && ($user instanceof ElggUser)) {
		// Get admins
		$admins = schools_get_admins();
		
		$result = TRUE;

		foreach($admins as $admin) {
			if ($admin) {
				$user_link = "<a href='{$user->getURL()}'>{$user->name}</a>";

				$result &= notify_user( 
					$admin->getGUID(), $CONFIG->site->guid, 
					elgg_echo('schools:notifyadminpending:subject'), 
					elgg_echo('schools:notifyadminpending:body', array(
						$user->name, 
						$user->email,
						$user->reg_school_role,
						$user->reg_school_name,
						$user->reg_school_url,
						$user->reg_about_url,
						elgg_get_site_url() . 'admin/users/pending',
					))
				);
			}
		}

		if ($result) {
			system_message(elgg_echo('schools:success:pendingnotify'));
		} else {
			register_error(elgg_echo('schools:error:pendingnotify'));
		}

		return $result;
	}

	return FALSE;
}

/** 
 * Helper function to grab and notifiy admin users that 
 * a new user has registered with a school
 *
 * @param ElggUser 		$user
 * @return bool
 */
function schools_register_notify_approve($user) {
	global $CONFIG;
	
	notify_user( 
		$user->guid, elgg_get_site_entity()->guid, 
		elgg_echo('schools:notifyuserapproved:subject'), 
		elgg_echo('schools:notifyuserapproved:body', array(
			elgg_get_site_url(),
			$user->getURL(),
		)),
		NULL,
		'email' // Force notification to email (messages are kind of useless in this cases)
	);
}

/**
 * Get where clause for pending school users
 *
 * "Unvalidated" means metadata of  is not set or not truthy.
 * We can't use elgg_get_entities_from_metadata() because you can't say
 * "where the entity has metadata set OR it's not equal to 1".
 *
 * @return array
 */
function schools_get_pending_users_sql_where() {
	global $CONFIG;

	$approved_id = get_metastring_id('schools_approved');
	if ($approved_id === false) {
		$approved_id = add_metastring('schools_approved');
	}

	$one_id = get_metastring_id('1');
	if ($one_id === false) {
		$one_id = add_metastring('1');
	}

	// thanks to daveb@freenode for the SQL tips!
	$wheres = array();
	$wheres[] = "e.enabled='no'";
	$wheres[] = "NOT EXISTS (
			SELECT 1 FROM {$CONFIG->dbprefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $approved_id
				AND md.value_id = $one_id)";

	return $wheres;
}

/**
 * Gets the approved status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool|null Null means status was not set for this user.
 */
function schools_get_user_approved_status($user_guid) {
	$md = elgg_get_metadata(array(
		'guid' => $user_guid,
		'metadata_name' => 'schools_approved'
	));
	if ($md == false) {
		return;
	}

	if ($md[0]->value) {
		return true;
	}

	return false;
}

/**
 * Set the approved and validated status for a user.
 * 
 * Setting validated and validated_method as well for compatibility
 *
 * @param int    $user_guid The user's GUID
 * @param bool   $status    Validated (true) or unvalidated (false)
 * @param string $method    Optional method to say how a user was validated
 * @return bool
 * @since 1.8.0
 */
function schools_set_user_approved_status($user_guid, $status, $method = '') {
	$result1 = create_metadata($user_guid, 'validated', $status, '', 0, ACCESS_PUBLIC, false);
	$result2 = create_metadata($user_guid, 'validated_method', $method, '', 0, ACCESS_PUBLIC, false);
	$result3 = create_metadata($user_guid, 'schools_approved', $method, '', 0, ACCESS_PUBLIC, false);
	if ($result1 && $result2 && $result3) {
		return true;
	} else {
		return false;
	}
}

/**
 * Copy an elgg sticky form
 *
 * @param string $form_name The name of the form to copy
 * @param string $form_copy The name of the copied form
 */
function copy_sticky_form($form_name, $form_copy) {
	// Clear any existing forms with the name of the copied form
	elgg_clear_sticky_form($form_copy);
	
	// Copy the form
	$_SESSION['sticky_forms'][$form_copy] = $_SESSION['sticky_forms'][$form_name];
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
