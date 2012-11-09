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
	'admin:users:pending' => 'Pending',
	'item:object:school' => 'Schools',
	
	// Labels 
	'schools:label:contact:name' => 'Contact Name',
	'schools:label:contact:phone' => 'Contact Phone Number',
	'schools:label:contact:email' => 'Contact Email Address',
	'schools:label:contact:address' => 'Contact Address',
	'schools:label:contact:website' => 'Website',
	'schools:label:code' => 'Code: %s',
	'schools:label:regcode' => 'Registration Code', 
	'schools:label:nocode' => 'No registration code?',
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
	'schools:label:regwhichrole' => 'I am a:',
	'schools:label:regrole_1' => 'Teacher',
	'schools:label:regrole_2' => 'Student',
	'schools:label:regrole_3' => 'Other',
	'schools:label:regschoolname' => 'What\'s the name of your School?', 
	'schools:label:regschoollink' => 'What\'s your school\'s website?', 
	'schools:label:regaboutlink' => 'Provide us with a link that tells us more about you (i.e. twitter, personal blog, about.me)', 
	'schools:label:adminwhichrole' => 'Role',
	'schools:label:adminschoolname' => 'School Name', 
	'schools:label:adminschoollink' => 'School URL', 
	'schools:label:adminaboutlink' => 'Info URL',
	'schools:label:no_unapproved_users' => 'No unapproved users.',
	'schools:label:check_all' => 'All',
	'schools:label:approve' => 'Approve',
	'schools:label:delete' => 'Delete',
	'schools:label:confirm_approve_checked' => 'Approve checked users?',
	'schools:label:confirm_delete_checked' => 'Delete checked users?',
	'schools:label:user_created' => 'Registered %s',
	'schools:label:confirm_approve_user' => 'Approve %s?',
	'schools:label:confirm_delete' => 'Delete %s?',

	// Confirmation
	'schools:success:save' => 'Successfully saved school',
	'schools:success:delete' => 'Successfully Deleted School',
	'schools:success:refresh' => 'Code refreshed',
	'schools:success:pendingnotify' => 'Your registration request is pending approval. You will be contacted when your registration is approved',
	'schools:success:approved_user' => 'User approved.',
	'schools:success:approved_users' => 'All checked users approved.',
	'schools:success:deleted_user' => 'User deleted.',
	'schools:success:deleted_users' => 'All checked users deleted.',
		
	// Error 
	'schools:error:save' => 'There was an error saving the school',
	'schools:error:requiredfields' => 'One or more required fields are missing',
	'schools:error:notfound' => 'School not found',
	'schools:error:delete' => 'School Deleted',
	'schools:error:edit' => 'There was an error editing the school',
	'schools:error:invalidcode' => 'Invalid registration code',
	'schools:error:schoolregerror' => 'Could not register user with school', 
	'schools:error:pendingnotify' => 'There was an error sending pending notification. Please contact site administrator',
	'schools:error:unknown_users' => 'Unknown users',
	'schools:error:could_not_approve_user' => 'Could not approve user.',
	'schools:error:could_not_approve_users' => 'Could not approve all checked users.',
	'schools:error:could_not_delete_user' => 'Could not delete user.',
	'schools:error:could_not_delete_users' => 'Could not delete all checked users.',

	// Notifications
	'schools:notifyadmin:subject' => 'Spotx New User Notice',
	'schools:notifyadmin:body' => 'New user registered:
	
	User: %s
	School: %s
	',

	'schools:notifyadminpending:subject' => 'Spotx New Pending User Notice',
	'schools:notifyadminpending:body' => 'New pending user:
	
	Name: %s
	Email Address: %s
	Role: %s
	School Name: %s
	School URL: %s
	About URL: %s
	',
	
	'schools:notifyuserapproved:subject' => 'Welcome to SpotX!',
	'schools:notifyuserapproved:body' => 'Your SpotX account has been approved. Welcome Aboard!

To log in, visit the following URL:

%s

You can view and edit your profile here:

%s
	',
);

add_translation('en',$english);
