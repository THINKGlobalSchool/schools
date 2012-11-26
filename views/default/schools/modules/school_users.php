<?php
/**
 * Schools user list module
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$guid = elgg_extract('guid', $vars);

set_input('user_gallery_size', 'medium');
elgg_push_context('members');

// Schools options
$options = array(
	'type' => 'user',
	'full_view' => FALSE,
	'limit' => 12,
	'list_type' => 'gallery',
);

// Schools are private, need to ignore access to grab info
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);

$school = get_entity($guid);

if (elgg_instanceof($school, 'object', 'school')) {
	$options['relationship'] = SCHOOL_RELATIONSHIP;
	$options['relationship_guid'] = $school->getGUID();
	$options['inverse_relationship'] = TRUE;
	$content = elgg_list_entities_from_relationship($options);
} else if (strtolower($guid) == 'tgs') {
	// Include only users with tgs domains
	global $CONFIG;
	
	// Join elgg_users_entity table so we can check the email
	$options['joins'] = "JOIN {$CONFIG->dbprefix}users_entity ue on ue.guid = e.guid";
	
	// I think a LIKE comparison is ok here..
	$options['wheres'] = "(ue.email like '%@thinkglobalschool.com' OR ue.email like '%@thinkglobalschool.org')";
	
	$content = elgg_list_entities_from_relationship($options);
} else {
	$content = elgg_echo('schools:error:invalidschool');
}

if (!$content) {
	$content = "<div style='width: 100%; text-align: center; margin: 10px;'><strong>No results</strong></div>";
}

elgg_set_ignore_access($ia);

echo $content;