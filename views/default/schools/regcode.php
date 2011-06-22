<?php
/**
 * Spotx Schools registration form extender
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */
?>
<div class="mtm">
	<label><?php echo elgg_echo('schools:label:schoolregcode'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'school_registration_code',
		'value' => get_input('code'),
	));
	?>
</div>