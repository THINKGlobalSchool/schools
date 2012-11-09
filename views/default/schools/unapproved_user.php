<?php
/**
 * Spotx Schools Pending Users Admin Form
 *
 * Based on the uservalidationbyemail plugin unvalidated_user view
 * 
 * Formats and list an unapproved user.
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

$user = elgg_extract('user', $vars);

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'user_guids[]',
	'value' => $user->guid,
	'default' => false,
));

$created = elgg_echo('schools:label:user_created', array(elgg_view_friendly_time($user->time_created)));

$approve = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('schools:label:confirm_approve_user', array($user->username)),
	'href' => "action/schools/approve/?user_guids[]=$user->guid",
	'text' => elgg_echo('schools:label:approve')
));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('schools:label:confirm_delete', array($user->username)),
	'href' => "action/schools/deleteuser/?user_guids[]=$user->guid",
	'text' => elgg_echo('schools:label:delete')
));

$role_label = elgg_echo('schools:label:adminwhichrole');
$school_name_label = elgg_echo('schools:label:adminschoolname');
$school_link_label = elgg_echo('schools:label:adminschoollink');
$about_link_label = elgg_echo('schools:label:adminaboutlink');

$school_url = elgg_view('output/longtext', array(
	'value' => $user->reg_school_url,
));

$about_url = elgg_view('output/longtext', array(
	'value' => $user->reg_about_url,
));

$block = <<<HTML
	<label>$user->username: "$user->name" &lt;$user->email&gt;</label>
	<div class="schools-unapproved-user-details">
		<span class='elgg-subtext'>$created</span><br />
		<table class=''>
			<tr>
				<td><label>$role_label</label></td>
				<td>$user->reg_school_role</td>
			</tr>
			<tr>
				<td><label>$school_name_label</label></td>
				<td>$user->reg_school_name</td>
			</tr>
			<tr>
				<td><label>$school_link_label</label></td>
				<td>$school_url</td>
			</tr>
			<tr>
				<td><label>$about_link_label</label></td>
				<td>$about_url</td>
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
