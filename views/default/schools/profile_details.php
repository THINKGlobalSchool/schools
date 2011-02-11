<?php
/**
 * Spotx Schools profile details extension 
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$school_info = get_user_school_info($vars['entity']);

if ($school_info) {
?>
<p class='even'>
	<b><?php echo elgg_echo('schools:school'); ?></b>: 
	<?php echo $school_info; ?>
</p>
<?php
}
?>