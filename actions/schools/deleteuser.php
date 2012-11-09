<?php
/**
 * Spotx Schools delete a user or users by guid
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

	// don't delete approved users
	$is_approved = schools_get_user_approved_status($guid);
	if ($is_approved !== FALSE || !$user->delete()) {
		$error = TRUE;
		continue;
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('schools:success:deleted_user');
	$error_txt = elgg_echo('schools:error:could_not_delete_user');
} else {
	$message_txt = elgg_echo('schools:success:deleted_users');
	$error_txt = elgg_echo('schools:error:could_not_delete_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);