<?php
/**
 * Spotx Schools Admin 
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

elgg_load_css('elgg.schools');

$school = get_entity(get_input('guid'));

elgg_push_breadcrumb(elgg_echo('admin:schools'), elgg_get_site_url() . 'admin/schools/manage');

if (elgg_instanceof($school, 'object', 'school')) {
	$content = elgg_view_entity($school, array('full_view' => TRUE));
	elgg_push_breadcrumb($school->title, $school->getURL());
} else {
	$content = elgg_echo('schools:error:notfound');
}

$breadcrumbs = elgg_view('navigation/breadcrumbs');

echo $breadcrumbs . $content;