<?php
/**
 * Spotx Schools Add User to School popup
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$guid = get_input('guid');

$user = get_entity($guid);

if (elgg_instanceof($user, 'user')) {
	$content = elgg_view_form('schools/adduser', array(), array('user' => $user));
} else {
	$content = elgg_echo('schools:error:invaliduser');
}

echo "<div class='schools-add-user-popup-content'>{$content}</div>";