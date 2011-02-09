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

// Create Sticky form
elgg_make_sticky_form('school-edit-form');

// Check inputs
if (!$title || !$description) {
	register_error(elgg_echo('schools:error:requiredfields'));
	forward(REFERER);
}

// New School
$school = new ElggObject();
$school->subtype = 'school';
$school->title = $title;
$school->description = $description;
$school->access_id = ACCESS_LOGGED_IN; // @TODO .. what should this be
$school->contact_name = $contact_name;
$school->contact_phone = $contact_phone;
$school->contact_email = $contact_email;
$school->contact_address = $contact_address;
$school->registration_code = ""; // Generate a code

// Try saving
if (!$school->save()) {
	// Error.. say so and forward
	register_error(elgg_echo('schools:error:create'));
	forward(REFERER);
} 

// Clear Sticky form
elgg_clear_sticky_form('school-edit-form');

system_message(elgg_echo('schools:success:create'));
forward(elgg_get_site_url() . 'pg/schools');