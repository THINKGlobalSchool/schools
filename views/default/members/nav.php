<?php
/**
 * SCHOOLS OVERRIDE! Includes school tabs
 */

$tabs = array(
	'newest' => array(
		'title' => elgg_echo('members:label:newest'),
		'url' => "members/newest",
		'selected' => $vars['selected'] == 'newest',
	),
	'popular' => array(
		'title' => elgg_echo('members:label:popular'),
		'url' => "members/popular",
		'selected' => $vars['selected'] == 'popular',
	),
	'online' => array(
		'title' => elgg_echo('members:label:online'),
		'url' => "members/online",
		'selected' => $vars['selected'] == 'online',
	),
);

$schools = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'school', 
	'limit' => 0,
));	
	
foreach($schools as $school) {
	// Bit hacky, but I'd like to allow for a test school
	if ($school->title != 'Test School') {
		$tabs[$school->guid] = array(
			'title' => $school->title,
			'url' => elgg_get_site_url() . "schools/members?filter={$school->guid}",
			'selected' => $vars['selected'] == $school->guid,
		);
	}
}

// Hard coded TGS tab
$tabs['tgs'] = array(
	'title' => 'TGS',
	'url' => elgg_get_site_url() . "schools/members?filter=tgs",
	'selected' => $vars['selected'] == 'tgs',
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
