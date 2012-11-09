<?php
/**
 * Spotx Schools approve a user or users by guid
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
$user_guids = get_input('user_guids');
$error = FALSE;

if (!$user_guids) {
	register_error(elgg_echo('schools:error:unknown_users'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($user_guids as $guid) {
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = TRUE;
		continue;
	}

	// only approve if not approved
	$is_approved = schools_get_user_approved_status($guid);
	$approve_success = schools_set_user_approved_status($guid, TRUE, 'schools_admin_approved');

	if ($is_approved !== FALSE || !($approve_success && $user->enable())) {
		$error = TRUE;
		continue;
	} else {
		// Notify User!
		schools_register_notify_approve($user);
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('schools:success:approved_user');
	$error_txt = elgg_echo('schools:error:could_not_approve_user');
} else {
	$message_txt = elgg_echo('schools:success:approved_users');
	$error_txt = elgg_echo('schools:error:could_not_approve_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);