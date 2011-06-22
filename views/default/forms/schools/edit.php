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
// Map values
$title = elgg_extract('title', $vars, '');
$guid = elgg_extract('guid', $vars, NULL);
$description = elgg_extract('description', $vars, '');
$contact_name = elgg_extract('contact_name', $vars, '');
$contact_phone = elgg_extract('contact_phone', $vars, '');
$contact_email = elgg_extract('contact_email', $vars, '');
$contact_address = elgg_extract('contact_address', $vars, '');
$contact_website = elgg_extract('contact_website', $vars, '');

// Check if we've got an entity, if so, we're editing.
if ($guid) {
	$entity_hidden  = elgg_view('input/hidden', array(
		'name' => 'school_guid', 
		'value' => $guid,
	));
} 

// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $title
));

$description_label = elgg_echo("description");
$description_input = elgg_view("input/longtext", array(
	'name' => 'description', 
	'value' => $description
));

$contact_website_label = elgg_echo("schools:label:contact:website");
$contact_website_input = elgg_view("input/text", array(
	'name' => 'contact_website', 
	'value' => $contact_website
));

$contact_name_label = elgg_echo("schools:label:contact:name");
$contact_name_input = elgg_view("input/text", array(
	'name' => 'contact_name', 
	'value' => $contact_name
));

$contact_phone_label = elgg_echo("schools:label:contact:phone");
$contact_phone_input = elgg_view("input/text", array(
	'name' => 'contact_phone', 
	'value' => $contact_phone
));

$contact_email_label = elgg_echo("schools:label:contact:email");
$contact_email_input = elgg_view("input/text", array(
	'name' => 'contact_email', 
	'value' => $contact_email
));

$contact_address_label = elgg_echo("schools:label:contact:address");
$contact_address_input = elgg_view("input/plaintext", array(
	'name' => 'contact_address', 
	'value' => $contact_address
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));	

// Build Form Body
$form_body = <<<HTML

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
HTML;

echo $form_body;
