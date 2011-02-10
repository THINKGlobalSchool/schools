<?php
/**
 * Spotx Schools edit form
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */

$registration_code_label = elgg_echo("schools:label:schoolregcode");
$registration_code_input = elgg_view("input/text", array('internalname' => 'registration_code'));


$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('submit')));	

$form_body = <<<EOT

<div class='margin_top'>
	<div>
		<label>$registration_code_label</label><br />
        $registration_code_input
	</div><br />
	<div>
		$submit_input
	</div>
</div>
	
EOT;
echo elgg_view('input/form', array('action' => "{$vars['url']}action/schools/authorize", 'body' => $form_body, 'internalid' => 'school-edit-form'));
