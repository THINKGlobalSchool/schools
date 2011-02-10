<?php
/**
 * Spotx Schools settings
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */
?>
<p>
	<label><?php echo elgg_echo('schools:label:privatekey'); ?></label><br />
	<?php echo elgg_echo('schools:label:privatekeydesc'); ?><br />
	<?php 
	echo elgg_view('input/text', array(
										'internalname' => 'params[schools_private_key]', 
										'value' => $vars['entity']->schools_private_key)
										); 
	?>
</p>