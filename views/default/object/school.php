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

$style = "<style type='text/css'>" . elgg_view('schools/admin_css') . "</style>";

$school = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$school) {
	return '';
}

$owner = get_entity($school->owner_guid);
$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
$owner_link = "<a href=\"{$owner->getURL()}\">{$owner->name}</a>";
$author_text = sprintf(elgg_echo('school:author_by_line'), $owner_link);
$linked_title = "<a href=\"{$school->getURL()}\" title=\"" . htmlentities($school->title) . "\">{$school->title}</a>";
$date = elgg_view_friendly_time($school->time_updated);

if ($school->canEdit()) {
	$edit_url = elgg_get_site_url()."pg/schools/edit/{$school->getGUID()}/";
	$edit_link = "<span class='school-edit'><a href=\"$edit_url\">" . elgg_echo('edit') . '</a></span>';

	$delete_url = elgg_get_site_url()."action/schools/delete?guid={$school->getGUID()}";
	$delete_link = "<span class='school-delete-button'>" . elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm')
	)) . "</span>";

	$refresh_url = elgg_get_site_url()."action/schools/refresh?guid={$school->getGUID()}";
	$refresh_link = elgg_view('output/confirmlink', array(
		'class' => 'school-action-button',
		'href' => $refresh_url,
		'text' => elgg_echo('schools:label:refresh'),
		'confirm' => elgg_echo('schools:label:refreshconfirm')
	));

	$edit .= "$refresh_link &nbsp;&nbsp; $edit_link $delete_link";
}

echo $style;
if ($vars['full']) {
	$description_label = elgg_echo('description');
	$contact_name_label = elgg_echo("schools:label:contact:name");
	$contact_phone_label = elgg_echo("schools:label:contact:phone");
	$contact_email_label = elgg_echo("schools:label:contact:email");
	$contact_address_label = elgg_echo("schools:label:contact:address");
	$registration_code_label = elgg_echo("schools:label:regcode");
	$private_code_label = elgg_echo("schools:label:privatecode");
	
	$school_users = elgg_view('schools/users', $vars);
		
	echo <<<___END
		<div class='school-header'>
			<div class='school-header-title'><h2>$linked_title</h2></div>
			<div class='school-controls'>$edit</div>
			<div style='clear: both;'></div>
		</div>
		<table class='school-info-table'>
			<tr>
				<td class='label'><label>$description_label</label></td>
				<td class='content'>$school->description</td>
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
		</table>			
		
		$school_users
___END;
} else {
	$registration_code_label = elgg_echo("schools:label:code");
	echo <<<___END
		<div class='school-listing'>
			<div class='school-title'>$linked_title</div>
			<div class='school-controls'>$edit</div>
			<div class='school-code'>$registration_code_label: $school->registration_code</div>
		</div>
___END;
}
?>