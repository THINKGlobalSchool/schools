<?php
/**
 * Spotx Schools entity view
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

if (!elgg_is_admin_logged_in()) {
	forward();
}
$full = elgg_extract('full_view', $vars, FALSE);

$school = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$school) {
	return '';
}

$linked_title = "<h3 style='padding-top: 14px;'><a href=\"{$school->getURL()}\" title=\"" . htmlentities($school->title) . "\">{$school->title}</a></h3>";

$metadata = elgg_view_menu('entity', array(
	'entity' => $school,
	'handler' => 'schools',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


// brief view
$params = array(
	'title' => FALSE,
	'entity' => $school,
	'metadata' => $metadata,
);
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($linked_title, $list_body);

if ($full) {
	$description_label = elgg_echo('description');
	$contact_name_label = elgg_echo("schools:label:contact:name");
	$contact_phone_label = elgg_echo("schools:label:contact:phone");
	$contact_email_label = elgg_echo("schools:label:contact:email");
	$contact_address_label = elgg_echo("schools:label:contact:address");
	$contact_website_label = elgg_echo("schools:label:contact:website");
	$registration_code_label = elgg_echo("schools:label:regcode");
	$private_code_label = elgg_echo("schools:label:privatecode");
	
	$school_users = elgg_list_entities_from_relationship(array(
		'relationship' => SCHOOL_RELATIONSHIP,
		'relationship_guid' => $school->getGUID(),
		'inverse_relationship' => TRUE,
		'types' => array('user'),
		'limit' => 0,
		'offset' => 0,
		'count' => FALSE,
		'full_view' => FALSE,
	));
	
	$users_label = elgg_echo('schools:label:users');
	
	$users_module = elgg_view_module('inline', $users_label, $school_users);
		
	echo <<<HTML
		<table class='school-info-table elgg-table'>
			<tbody>
				<tr>
					<td class='label'><label>$description_label</label></td>
					<td class='content'>$school->description</td>
				</tr>
				<tr>
					<td class='label'><label>$contact_website_label</label></td>
					<td class='content'>$school->contact_website</td>
				</tr>
				<tr>
					<td class='label'><label>$contact_name_label</label></td>
					<td class='content'>$school->contact_name</td>
				</tr>
				<tr>
					<td class='label'><label>$contact_phone_label</label></td>
					<td class='content'>$school->contact_phone</td>
				</tr>
				<tr>
					<td class='label'><label>$contact_email_label</label></td>
					<td class='content'>$school->contact_email</td>
				</tr>
				<tr>
					<td class='label'><label>$contact_address_label</label></td>
					<td class='content'>$school->contact_address</td>
				</tr>
				<tr>
					<td class='label'><label>$registration_code_label</label></td>
					<td class='content'><strong>$school->registration_code</strong></td>
				</tr>
				<tr>
					<td class='label'><label>$private_code_label</label></td>
					<td class='content'><strong>$school->private_code</strong></td>
				</tr>
			</tbody>
		</table>		
		<br />	
		$users_module
HTML;
} 
?>