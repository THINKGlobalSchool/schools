<?php
/**
 * Spotx Schools Extended Register Action
 *
 * @todo make this work with Google Apps
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$registration_code = get_input('school_registration_code');

elgg_make_sticky_form('register');
copy_sticky_form('register', 'schools_register');

$ia = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);
if ($school = get_school_from_registration_code(trim($registration_code))) { // Valid Code Here	
	// Call facebook login, with valid code context
	elgg_set_ignore_access($ia);
	// Set context for event listeners 
	elgg_push_context('valid_school_code');
	facebook_login(); // Do facebook login
} else if (!$registration_code) {
	elgg_set_ignore_access($ia);
	// No reg code, registering with extra info
	elgg_push_context('register_moderated');
	facebook_login(); // Do facebook login
} else {
	elgg_set_ignore_access($ia);
	register_error(elgg_echo('schools:error:invalidcode'));
	forward(REFERER);
}