<?php
/**
 * Spotx Schools Add User to School Action
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$user_guid = get_input('user_guid');
$school_guid = get_input('school_guid');

$user = get_entity($user_guid);
$school = get_entity($school_guid);

if (!elgg_instanceof($user, 'user')) {
	register_error(elgg_echo('schools:error:invaliduser'));
}

if (!elgg_instanceof($school, 'object', 'school')) {
	register_error(elgg_echo('schools:error:invalidschool'));
}

// We have a school already, so remove user
if ($old_school = get_user_school($user)) {
	remove_entity_relationship($user_guid, SCHOOL_RELATIONSHIP, $old_school->guid);
}

if (assign_user_to_school($user, $school)) {
	system_message(elgg_echo('schools:success:assignuser'));
} else {
	register_error(elgg_echo('schools:error:assignuser'));
}

forward(REFERER);