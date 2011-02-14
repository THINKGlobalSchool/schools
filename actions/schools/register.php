<?php
/**
 * Elgg registration action
 *
 * @package Elgg.Core
 * @subpackage User.Account
 */

global $CONFIG;

// Get variables
$username = get_input('username');
$password = get_input('password');
$password2 = get_input('password2');
$email = get_input('email');
$name = get_input('name');
$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');
$registration_code = get_input('school_registration_code');

if ($CONFIG->allow_registration) {
	try {
		
		// Check for valid registration code
		$ia = elgg_get_ignore_access();
		elgg_set_ignore_access(TRUE);
		if (!$school = get_school_from_registration_code(trim($registration_code))) {
			elgg_set_ignore_access($ia);
			throw new RegistrationException(elgg_echo('schools:error:invalidcode'));
		}
		elgg_set_ignore_access($ia);
		
		if (trim($password) == "" || trim($password2) == "") {
			throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
		}

		if (strcmp($password, $password2) != 0) {
			throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
		}

		$guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);

		if ($guid) {
			$new_user = get_entity($guid);
			
			$ia = elgg_get_ignore_access();
			elgg_set_ignore_access(TRUE);
			// Assign user to school
			if (!assign_user_to_school($new_user, $school)) {
				elgg_set_ignore_access($ia);
				$new_user->delete();
				throw new RegistrationException(elgg_echo('schools:error:schoolregerror'));
				
			}		
			elgg_set_ignore_access($ia);

			// allow plugins to respond to self registration
			// note: To catch all new users, even those created by an admin,
			// register for the create, user event instead.
			// only passing vars that aren't in ElggUser.
			$params = array(
				'user' => $new_user,
				'password' => $password,
				'friend_guid' => $friend_guid,
				'invitecode' => $invitecode
			);

			// @todo should registration be allowed no matter what the plugins return?
			if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
				$new_user->delete();
				// @todo this is a generic messages. We could have plugins
				// throw a RegistrationException, but that is very odd
				// for the plugin hooks system.
				throw new RegistrationException(elgg_echo('registerbad'));
			}

			system_message(elgg_echo("registerok", array($CONFIG->sitename)));
			
			// Notifiy admins
			schools_register_notify_admins($school, $new_user);
			

			// if exception thrown, this probably means there is a validation
			// plugin that has disabled the user
			try {
				login($new_user);
			} catch (LoginException $e) {
				// do nothing
			}

			// Forward on success, assume everything else is an error...
			forward();
		} else {
			register_error(elgg_echo("registerbad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

forward(REFERER);