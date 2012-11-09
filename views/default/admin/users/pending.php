<?php
/**
 * Spotx Schools Pending Users Admin Page
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

elgg_load_css('elgg.schools');
elgg_load_js('elgg.schools');


echo elgg_view_form('schools/pending_bulk', array(
	'id' => 'schools-pending-users-form',
));
