<?php
/**
 * Spotx Schools user list
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$users = get_school_users($vars['entity']);
												
echo "<h2>" . elgg_echo('schools:label:users') . "</h2>";

foreach ($users as $user) {
	$linked_name = "<h4><a href='" . $user->getURL() . "'>" . $user->name . "</a></h4>";
	echo "<div class='school-listing'>
			<div class='school-title'>$linked_name</div>
		</div>";
}