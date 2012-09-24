<?php
/**
 * Spotx Schools English language translation
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
$english = array(
	
	'schools:school' => 'School',
	'admin:schools' => 'Schools',
	'admin:schools:manage' => 'Manage Schools',
	'admin:schools:add' => 'Add New School', 
	'admin:schools:edit' => 'Edit School', 
	'admin:schools:view' => 'View School',
	'item:object:school' => 'Schools',
	
	// Labels 
	'schools:label:contact:name' => 'Contact Name',
	'schools:label:contact:phone' => 'Contact Phone Number',
	'schools:label:contact:email' => 'Contact Email Address',
	'schools:label:contact:address' => 'Contact Address',
	'schools:label:contact:website' => 'Website',
	'schools:label:code' => 'Code: %s',
	'schools:label:regcode' => 'Registration Code', 
	'schools:label:schoolregcode' => 'School Registration Code', 
	'schools:label:register' => 'Register with School',
	'schools:label:privatecode' => 'Private Code',
	'schools:label:new' => 'Create New School',
	'schools:label:privatekey' => 'Private Key',
	'schools:label:privatekeydesc' => 'Used to generate school registration codes. Changing this will invalidate any existing code (Can be anything you want)',
	'schools:label:refresh' => 'Refresh Code',
	'schools:label:refreshconfirm' => 'Are you sure you want to refresh the registration code? This will invalidate the existing code!',
	'schools:label:users' => 'Users',
	'schools:label:currentschools' => 'Current Schools',

	// Confirmation
	'schools:success:save' => 'Successfully saved school',
	'schools:success:delete' => 'Successfully Deleted School',
	'schools:success:refresh' => 'Code refreshed',
		
	// Error 
	'schools:error:save' => 'There was an error saving the school',
	'schools:error:requiredfields' => 'One or more required fields are missing',
	'schools:error:notfound' => 'School not found',
	'schools:error:delete' => 'School Deleted',
	'schools:error:edit' => 'There was an error editing the school',
	'schools:error:invalidcode' => 'Invalid registration code',
	'schools:error:schoolregerror' => 'Could not register user with school', 

	// Notifications
	'schools:notifyadmin:subject' => 'Spotx New User Notice',
	'schools:notifyadmin:body' => 'New user registered:
	
	User: %s
	School: %s
	',
);

add_translation('en',$english);
