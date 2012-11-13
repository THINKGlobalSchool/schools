<?php
/**
 * Spotx Schools request school code form
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.org/
 * 
 */
elgg_load_css('elgg.schools');

// Map values
extract(elgg_get_sticky_values('schools_request'));

// Labels/Input
$title_label = elgg_echo('schools:label:schoolname');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $title,
	'maxlength' => 1500,
));

$description_label = elgg_echo("description");
$description_input = elgg_view("input/plaintext", array(
	'name' => 'description', 
	'value' => $description,
	'maxlength' => 1500,
));

$contact_website_label = elgg_echo("schools:label:contact:website");
$contact_website_input = elgg_view("input/text", array(
	'name' => 'contact_website', 
	'value' => $contact_website,
	'maxlength' => 1500,
));

$contact_name_label = elgg_echo("schools:label:contact:name");
$contact_name_input = elgg_view("input/text", array(
	'name' => 'contact_name', 
	'value' => $contact_name,
	'maxlength' => 1500,
));

$contact_phone_label = elgg_echo("schools:label:contact:phone");
$contact_phone_input = elgg_view("input/text", array(
	'name' => 'contact_phone', 
	'value' => $contact_phone,
	'maxlength' => 1500,
));

$contact_email_label = elgg_echo("schools:label:contact:email");
$contact_email_input = elgg_view("input/text", array(
	'name' => 'contact_email', 
	'value' => $contact_email,
	'maxlength' => 1500,
));

$contact_address_label = elgg_echo("schools:label:contact:address");
$contact_address_input = elgg_view("input/plaintext", array(
	'name' => 'contact_address', 
	'value' => $contact_address,
	'maxlength' => 1500,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('submit')
));	

$request_description = elgg_echo('schools:label:requestdescription');

$asterisk = "&nbsp;<span class='schools-request-form-required'>*</span>";
$required = elgg_echo('schools:label:denotesrequired');

// Build Form Body
$form_body = <<<HTML
	<br />
	<div>
		$request_description
	</div>
	<div>
		<label>$title_label</label>$asterisk<br />
        $title_input
	</div><br />
	<div>
		<label>$description_label</label>$asterisk<br />
        $description_input
	</div><br />
	<div>
		<label>$contact_website_label</label>$asterisk<br />
        $contact_website_input
	</div><br />
	<div>
		<label>$contact_name_label</label>$asterisk<br />
        $contact_name_input
	</div><br />
	<div>
		<label>$contact_phone_label</label>$asterisk<br />
        $contact_phone_input
	</div><br />
	<div>
		<label>$contact_email_label</label>$asterisk<br />
        $contact_email_input
	</div><br />
	<div>
		<label>$contact_address_label</label><br />
        $contact_address_input
	</div><br />
	<div>
		$submit_input
		<br /><br />
		<span class='schools-request-form-required '>$required</span>
	</div>
HTML;

echo $form_body;
