<?php
/**
 * Spotx Schools Add User Form
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

// Admins only
if (!elgg_is_admin_logged_in()) {
	return false;
}

$user = elgg_extract('user', $vars);

$school_options = array();

$schools = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'school',
	'limit' => 0,
));

foreach ($schools as $school) {
	$school_options[$school->guid] = $school->title;
}

$select_school_label = elgg_echo('schools:label:selectschool');
$select_school_input = elgg_view('input/dropdown', array(
	'name' => 'school_guid',
	'options_values' => $school_options,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('schools:label:addtoschool'),
	'class' => 'elgg-button elgg-button-action schools-add-user-submit',
	'id' => 'schools-add-user-submit-' . $user->guid,
));

$user_hidden = elgg_view('input/hidden', array(
	'name' => 'user_guid',
	'value' => $user->guid,
));

$content = <<<HTML
	<div>
		<label>$select_school_label</label><br />
		$select_school_input
	</div>
	<div class='elgg-foot'>
		$submit_input
		$user_hidden
	</div>
HTML;

echo $content;