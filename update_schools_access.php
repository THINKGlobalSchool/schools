<?php
/** 
 * Set schools to ACCESS_PRIVATE
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
admin_gatekeeper();

$go = get_input('go', FALSE);

echo "<pre>SET SCHOOLS TO PRIVATE<br /><br />";

$options = array(
	'type' => 'object',
	'subtype' => 'school',
	'limit' => 0,
);

$schools = new ElggBatch('elgg_get_entities', $options);

echo "SCHOOLS:<br/>--------------<br />";

// Check each announcement
foreach ($schools as $school) {
	$orig_access = $school->access_id;
	if ($go) {
		$school->access_id = ACCESS_PRIVATE;
		$school->save();
		$updated = " -> ACCESS: {$school->access_id}";
	}
	
	echo "GUID: - {$school->guid} - ACCESS: {$orig_access} {$updated}<br />";
}

echo "</pre>";