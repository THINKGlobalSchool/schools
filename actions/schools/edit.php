<?php
/**
 * Spotx Schools add/edit school action
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get inputs
$title = get_input('title');
$description = get_input('description');
$contact_name = get_input('contact_name');
$contact_phone = get_input('contact_phone');
$contact_email = get_input('contact_email');
$contact_address = get_input('contact_address');
$contact_website = get_input('contact_website');
$school_guid = get_input('school_guid', NULL);

// Create Sticky form
elgg_make_sticky_form('school-edit-form');

// Check inputs
if (!$title || !$description) {
	register_error(elgg_echo('schools:error:requiredfields'));
	forward(REFERER);
}

// New school
if (!$school_guid) {
	$school = new ElggObject();
	$school->subtype = 'school';
	$school->access_id = ACCESS_PRIVATE;
} else { // Editing
	$school = get_entity($school_guid);
	if (!elgg_instanceof($school, 'object', 'school')) {
		register_error(elgg_echo('schools:error:edit'));
		forward(REFERER);
	}
}

$school->title = $title;
$school->description = $description;
$school->contact_name = $contact_name;
$school->contact_phone = $contact_phone;
$school->contact_email = $contact_email;
$school->contact_address = $contact_address;
$school->contact_website = $contact_website;


// Try saving
if (!$school->save()) {
	// Error.. say so and forward
	register_error(elgg_echo('schools:error:save'));
	forward(REFERER);
} 

// Clear Sticky form
elgg_clear_sticky_form('school-edit-form');

system_message(elgg_echo('schools:success:save'));
forward(elgg_get_site_url() . 'admin/schools/manage');