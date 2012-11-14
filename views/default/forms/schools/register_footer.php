<?php
/**
 * Spotx Schools registration form footer (for any content we want to appear AFTER the form)
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 */

$required = elgg_echo('schools:label:denotesrequired');
?>
<span class='schools-request-form-required '><?php echo $required; ?></span>
<script type='text/javascript'>
	$(document).ready(function() {
		var core_registration_fields = ['name', 'email', 'username', 'password', 'password2'];
		
		$(document).find('input').each(function(){
			if ($.inArray($(this).attr('name'), core_registration_fields) != -1) {
				$(this).parent().find('label').after("<span class='schools-request-form-required'>&nbsp;*</span>");
			}
		});
	});
</script>