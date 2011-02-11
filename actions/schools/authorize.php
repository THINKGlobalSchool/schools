<?php
/**
 * Spotx Schools authorize registration code action
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$registration_code = get_input('registration_code');

elgg_set_ignore_access(TRUE);
if ($school = get_school_from_registration_code(trim($registration_code))) {	
	// Call the facebookservice login
	facebookservice_login();
} else {
	elgg_set_ignore_access(FALSE);
	register_error(elgg_echo('schools:error:invalidcode'));
	forward(REFERER);
}
