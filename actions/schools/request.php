<?php
/**
 * Spotx Schools public school request action
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

// Create Sticky form
elgg_make_sticky_form('schools_request');

// Check inputs
if (!$title || !$description || !$contact_website || !$contact_name || !$contact_phone || !$contact_email) {
	register_error(elgg_echo('schools:error:maxlength'));
	forward(REFERER);
}

$verify_inputs = array(
	$title, $description, $contact_name, $contact_phone, $contact_email, $contact_address, $contact_website 
);

foreach ($verify_inputs as $input) {
	if (strlen($input) > 1500) {
		register_error(elgg_echo('schools:error:requiredfields'));
		forward(REFERER);
	}
}

elgg_push_context('schools_request');

// New school
$school = new ElggObject();
$school->subtype = 'school';
$school->access_id = ACCESS_PUBLIC;


// Set fields
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
	register_error(elgg_echo('schools:error:request'));
	forward(REFERER);
} 

// Notify admins that a school has been requested
schools_request_notify_admins($school);

// Disable the school (non-recursive)
$school->disable('schools_pending_request', FALSE);

elgg_pop_context();

// Clear Sticky form
elgg_clear_sticky_form('schools_request');

// Successful
system_message(elgg_echo('schools:success:request'));
forward();