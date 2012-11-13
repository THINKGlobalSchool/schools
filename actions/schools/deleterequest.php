<?php
/**
 * Spotx Schools delete a requested school by guid or guids
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$school_guids = get_input('school_guids');
$error = FALSE;

if (!$school_guids) {
	register_error(elgg_echo('schools:error:unknown_schools'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($school_guids as $guid) {
	$school = get_entity($guid);
	if (!elgg_instanceof($school, 'object', 'school')) {
		$error = TRUE;
		continue;
	}

	if ($school->isEnabled() !== FALSE || !$school->delete()) {
		$error = TRUE;
		continue;
	}
}

access_show_hidden_entities($access);

if (count($school_guids) == 1) {
	$message_txt = elgg_echo('schools:success:deleted_request');
	$error_txt = elgg_echo('schools:error:could_not_delete_request');
} else {
	$message_txt = elgg_echo('schools:success:deleted_requests');
	$error_txt = elgg_echo('schools:error:could_not_delete_requests');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);