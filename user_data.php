<?php
// Get user info
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
admin_gatekeeper();

$user = get_user_by_username(get_input('u'));


echo "<pre>";

if (elgg_instanceof($user, 'user')) {
	
	$metadata = elgg_get_metadata(array(
		'guid' => $user->guid,
	));

	echo "Dumping Metadata for user with GUID: {$user->guid} \r\n\r\n";

	foreach ($metadata as $md) {
		echo $md->name . ": " . $md->value . "\r\n";
	}

} else {
	echo "No user";
}

echo "</pre>";