<?php
/**
 * Schools user list for members page
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */


$guid = elgg_extract('guid', $vars, NULL);

// Groups Module
$ajaxmodule = elgg_view('modules/genericmodule', array(
	'view' => 'schools/modules/school_users',
	'module_id' => 'members-school-user-list-ajaxmoduke',
	'view_vars' => array('guid' => $guid),
));

echo $ajaxmodule;

echo "<script>elgg.modules.genericmodule.init();</script>";