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
$registration_code_input = elgg_view("input/text", array('name' => 'registration_code'));

$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('submit')));	

// Need to grab the session information from the querystring
$session_input = elgg_view('input/hidden', array('name' => 'session', 'value' => get_input('session')));

$form_body = <<<EOT

<div class='margin_top'>
	<div>
		<label>$registration_code_label</label><br />
        $registration_code_input
	</div><br />
	<div>
		$submit_input
		$session_input
	</div>
</div>
	
EOT;
echo elgg_view('input/form', array('action' => "{$vars['url']}action/schools/authorize", 'body' => $form_body, 'id' => 'school-edit-form'));
