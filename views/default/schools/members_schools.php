<?php
/**
 * Schools browser for school members tab
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$ia = elgg_get_ignore_access();
elgg_set_ignore_access(TRUE);
$schools = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'school',
	'limit' => 0,
));

if ($schools) {
	$schools_content = "<ul class='elgg-list'>";
	
	// Add in hard coded TGS
	$schools_content .= "<li id='tgs' class='elgg-item elgg-item-school school-state-selected'><h3>THINK Global School</h3></li>";
	
	foreach ($schools as $school) {
		$schools_content .= "<li id='{$school->guid}' class='elgg-item elgg-item-school'><h3>{$school->title}</h3></li>";
	}
	
	$schools_content .= "</ul>";

	elgg_set_ignore_access($ia);
	echo elgg_view_module('inline', '', $schools_content, array('id' => 'members-schools-module'));

	echo "<div id='members-school-user-list'></div>";
	
	echo <<<JAVASCRIPT
		<script type='text/javascript'>
			var members_schools_init_default = function() {
				$('li#tgs').trigger('click');				
			}

			// Need to click AFTER elgg is initted
			elgg.register_hook_handler('ready', 'system', members_schools_init_default);

		</script>
JAVASCRIPT;
}