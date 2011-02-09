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
	$content = elgg_view_title(elgg_echo('schools:title:admin'));
	
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
	if ($type == 'edit') {
		$title = elgg_echo('schools:title:edit');
		$content = elgg_view_title($title);
		$school = get_entity($guid);
		if (elgg_instanceof($school, 'object', 'school')) {
			$content .= elgg_view('forms/schools/edit', array('entity' => $school));
		}
	} else if ($type == 'add') {
		$title = elgg_echo('schools:title:add');
		$content = elgg_view_title($title) . elgg_view('forms/schools/edit');
	}
	
	$body = elgg_view_layout('administration', array('content' => $content));
	echo elgg_view_page($title, $body, 'admin');
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
