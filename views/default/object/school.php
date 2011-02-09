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
	$edit_url = elgg_get_site_url()."pg/school/edit/{$school->getGUID()}/";
	$edit_link = "<span class='entity_edit'><a href=\"$edit_url\">" . elgg_echo('edit') . '</a></span>';

	$delete_url = elgg_get_site_url()."action/school/delete?guid={$school->getGUID()}";
	$delete_link = "<span class='delete_button'>" . elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm')
	)) . "</span>";

	$edit .= "$edit_link $delete_link";
}


if ($vars['full']) {
	echo <<<___END
		<p class="entity_title">$linked_title</p>
___END;
} else {
	echo <<<___END
		<p class="entity_title">$linked_title</p>
___END;
}
?>