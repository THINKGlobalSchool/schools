<?php
/**
 * Spotx Schools Pending School Request View
 *
 * Based on the uservalidationbyemail plugin unvalidated_user view
 * 
 * Formats and list an unapproved school
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$hidden_entities = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

$school = elgg_extract('school', $vars);

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'school_guids[]',
	'value' => $school->guid,
	'default' => false,
));

$created = elgg_echo('schools:label:school_created', array(elgg_view_friendly_time($school->time_created)));

$approve = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('schools:label:confirm_approve_user', array($school->username)),
	'href' => "action/schools/approverequest/?school_guids[]=$school->guid",
	'text' => elgg_echo('schools:label:approve')
));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('schools:label:confirm_delete', array($school->username)),
	'href' => "action/schools/deleterequest/?school_guids[]=$school->guid",
	'text' => elgg_echo('schools:label:delete')
));

$description_label = elgg_echo("description");
$contact_name_label = elgg_echo("schools:label:contact:name");
$contact_phone_label = elgg_echo("schools:label:contact:phone");
$contact_email_label = elgg_echo("schools:label:contact:email");
$contact_address_label = elgg_echo("schools:label:contact:address");

$description = elgg_view('output/longtext', array(
	'value' => $school->description,
));

$contact_name = elgg_view('output/text', array(
	'value' => $school->contact_name,
));

$contact_phone = elgg_view('output/text', array(
	'value' => $school->contact_phone,
));

$contact_email = elgg_view('output/text', array(
	'value' => $school->contact_email,
));

$contact_address = elgg_view('output/text', array(
	'value' => $school->contact_address,
));

$block = <<<HTML
	<label>$school->title (<a href='$school->contact_website'>$school->contact_website</a>)</label>
	<div class="schools-unapproved-user-details">
		<span class='elgg-subtext'>$created</span><br />
		<table class=''>
			<tr>
				<td><label>$description_label</label></td>
				<td>$description</td>
			</tr>
			<tr>
				<td><label>$contact_name_label</label></td>
				<td>$contact_name</td>
			</tr>
			<tr>
				<td><label>$contact_phone_label</label></td>
				<td>$contact_phone</td>
			</tr>
			<tr>
				<td><label>$contact_email_label</label></td>
				<td>$contact_email</td>
			</tr>
			<tr>
				<td><label>$contact_address_label</label></td>
				<td>$contact_address</td>
			</tr>
		</table>
	</div>
HTML;

$menu = <<<HTML
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$approve</li>
		<li>$delete</li>
	</ul>
HTML;
access_show_hidden_entities($hidden_entities);
echo elgg_view_image_block($checkbox, $block, array('image_alt' => $menu));
