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

$users = elgg_get_entities_from_relationship(array(
													'relationship' => SCHOOL_RELATIONSHIP,
													'relationship_guid' => $vars['entity']->getGUID(),
													'inverse_relationship' => TRUE,
													'types' => array('user'),
													'limit' => 0,
													'offset' => 0,
													'count' => false,
												));
												
echo "<h2>" . elgg_echo('schools:label:users') . "</h2>";

foreach ($users as $user) {
	$linked_name = "<h4><a href='" . $user->getURL() . "'>" . $user->name . "</a></h4>";
	echo "<div class='school-listing'>
			<div class='school-title'>$linked_name</div>
		</div>";
}