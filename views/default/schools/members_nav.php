<?php
/**
 * Extend the navigtation tabs view for custom schools members tab
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$custom_selected = get_input('members_custom_tab_selected');
// Tack these tabs on the to the tab array if under members context
if (elgg_get_context() == 'members') {
	$vars['tabs']['schools'] = array(
		'title' => elgg_echo('schools'),
		'url' => "members/schools",
		'selected' => $custom_selected == 'schools',
	);
}