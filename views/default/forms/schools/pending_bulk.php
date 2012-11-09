<?php
/**
 * Spotx Schools Pending Users Admin Form
 *
 * Based on the uservalidationbyemail plugin bulk_action form
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// can't use elgg_list_entities() and friends because we don't use the default view for users.
$ia = elgg_set_ignore_access(TRUE);
$hidden_entities = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

$options = array(
	'type' => 'user',
	'wheres' => schools_get_pending_users_sql_where(),
	'limit' => $limit,
	'offset' => $offset,
	'count' => TRUE,
);
$count = elgg_get_entities($options);

if (!$count) {
	access_show_hidden_entities($hidden_entities);
	elgg_set_ignore_access($ia);

	echo autop(elgg_echo('schools:label:no_unapproved_users'));
	return TRUE;
}

$options['count']  = FALSE;

$users = elgg_get_entities($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'base_url' => 'admin/users/pending',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$bulk_actions_checkbox = '<label><input type="checkbox" class="schools-checkall" />'
	. elgg_echo('schools:label:check_all') . '</label>';

$approve = elgg_view('output/url', array(
	'href' => 'action/schools/approve/',
	'text' => elgg_echo('schools:label:approve'),
	'title' => elgg_echo('schools:label:confirm_approve_checked'),
	'class' => 'schools-pending-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$delete = elgg_view('output/url', array(
	'href' => 'action/schools/deleteuser/',
	'text' => elgg_echo('schools:label:delete'),
	'title' => elgg_echo('schools:label:confirm_delete_checked'),
	'class' => 'schools-pending-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$bulk_actions = <<<HTML
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$approve</li>
		<li>$delete</li>
	</ul>
	$bulk_actions_checkbox
HTML;

if (is_array($users) && count($users) > 0) {
	$html = '<ul class="elgg-list elgg-list-distinct">';
	foreach ($users as $user) {
		$html .= "<li id=\"unapproved-user-{$user->guid}\" class=\"elgg-item schools-unapproved-user-item\">";
		$html .= elgg_view('schools/unapproved_user', array('user' => $user));
		$html .= '</li>';
	}
	$html .= '</ul>';
}

echo <<<HTML
<div class="elgg-module elgg-module-inline schools-pending-module">
	<div class="elgg-head">
		$bulk_actions
	</div>
	<div class="elgg-body">
		$html
	</div>
</div>
HTML;

if ($count > 5) {
	echo $bulk_actions;
}

echo $pagination;
