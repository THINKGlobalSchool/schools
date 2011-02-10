<?php
/**
 * Spotx Schools delete school action
 * 
 * @package SpotxSchool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
	
// Get inputs
$school_guid = get_input('guid');

$school = get_entity($school_guid);

if (elgg_instanceof($school, 'object', 'school')) {
	if ($school->delete()) {
		// Success
		system_message(elgg_echo('schools:success:delete'));
		forward('pg/schools');
		
	} else {
		// Error
		register_error(elgg_echo('schools:error:delete'));
		forward(REFERER);
	}		
}
