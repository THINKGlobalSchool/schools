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

// Check if we've got an entity, if so, we're editing.
if (isset($vars['entity'])) {
	$entity_hidden  = elgg_view('input/hidden', array('internalname' => 'school_guid', 'value' => $vars['entity']->getGUID()));
} 

$style = "<style type='text/css'>" . elgg_view('schools/admin_css') . "</style>";

$action = "schools/edit";

// Prep values
$values = schools_prepare_form_vars($vars['entity']);

// Map values
$title = elgg_get_array_value('title', $values, '');
$description = elgg_get_array_value('description', $values, '');
$contact_name = elgg_get_array_value('contact_name', $values, '');
$contact_phone = elgg_get_array_value('contact_phone', $values, '');
$contact_email = elgg_get_array_value('contact_email', $values, '');
$contact_address = elgg_get_array_value('contact_address', $values, '');
$contact_website = elgg_get_array_value('contact_website', $values, '');

// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array('internalname' => 'title', 'value' => $title));

$description_label = elgg_echo("description");
$description_input = elgg_view("input/longtext", array('internalname' => 'description', 'value' => $description));

$contact_website_label = elgg_echo("schools:label:contact:website");
$contact_website_input = elgg_view("input/text", array('internalname' => 'contact_website', 'value' => $contact_website));

$contact_name_label = elgg_echo("schools:label:contact:name");
$contact_name_input = elgg_view("input/text", array('internalname' => 'contact_name', 'value' => $contact_name));

$contact_phone_label = elgg_echo("schools:label:contact:phone");
$contact_phone_input = elgg_view("input/text", array('internalname' => 'contact_phone', 'value' => $contact_phone));

$contact_email_label = elgg_echo("schools:label:contact:email");
$contact_email_input = elgg_view("input/text", array('internalname' => 'contact_email', 'value' => $contact_email));

$contact_address_label = elgg_echo("schools:label:contact:address");
$contact_address_input = elgg_view("input/plaintext", array('internalname' => 'contact_address', 'value' => $contact_address));

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));	

// Build Form Body
$form_body = <<<EOT

<div class='margin_top'>
	<div>
		<label>$title_label</label><br />
        $title_input
	</div><br />
	<div>
		<label>$description_label</label><br />
        $description_input
	</div><br />
	<div>
		<label>$contact_website_label</label><br />
        $contact_website_input
	</div><br />
	<div>
		<label>$contact_name_label</label><br />
        $contact_name_input
	</div><br />
	<div>
		<label>$contact_phone_label</label><br />
        $contact_phone_input
	</div><br />
	<div>
		<label>$contact_email_label</label><br />
        $contact_email_input
	</div><br />
	<div>
		<label>$contact_address_label</label><br />
        $contact_address_input
	</div><br />
	<div>
		$submit_input
		$entity_hidden
	</div>
</div>
	
EOT;
echo $style . elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'school-edit-form'));
