<?php
/**
 * Spotx Schools registration form extender
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 */
elgg_load_css('elgg.schools');
elgg_load_js('elgg.schools');

// Get the extra sticky values
extract(elgg_get_sticky_values('schools_register'));
elgg_clear_sticky_form('schools_register');

// Reg code input options
$reg_code_input_options = array(
	'name' => 'school_registration_code',
	'value' => $school_registration_code,
	'id' => 'schools-reg-code',
);

// If we're redirected back due to an error, toggle 
// the more info section based on previous state
if ($more_info_toggled == 'on') {
	$reg_code_input_options['disabled'] = 'DISABLED';
	$reg_code_input_options['class'] = 'schools-input-disabled';
	$reg_code_input_options['value'] = NULL;
	$more_info_div_toggled = 'more-info-toggled';
	$label_class = 'schools-text-disabled';
} else {
	$more_info_toggled = 'off';
	if ($reg_code_radio == 1) {
		$reg_code_div_toggled = 'reg-code-toggled';
	}
}

$asterisk = "<span class='schools-request-form-required'>&nbsp;*</span>";
 
// Reg code input
$reg_code_label = elgg_echo('schools:label:schoolregcode');
$reg_code_input = elgg_view('input/text', $reg_code_input_options);

// Reg code radio select
$reg_code_radio = elgg_view('input/radio', array(
	'name' => 'reg_code_radio',
	'value' => $reg_code_radio,
	'id' => 'schools-registration-code-radio',
	'options' => array(
		elgg_echo('schools:label:ihavecode') . $asterisk => 1,
		elgg_echo('schools:label:donthavecode') . $asterisk => 2,
	),
));

// What do you do? (Don't know what to put here..)
$role_label = elgg_echo('schools:label:regwhichrole');

$other_input = elgg_view('input/text', array(
	'name' => 'reg_school_role_other',
	'style' => 'width: 200px;',
	'value' => $reg_school_role_other,
));

$role_input = elgg_view('input/radio', array(
		'name' => 'reg_school_role',
		'value' => $reg_school_role,
		'id' => 'schools-registration-role',
		'options' => array(
			elgg_echo('schools:label:regrole_1') . "<span class='schools-reg-spacer'></span>" => 1, 
			elgg_echo('schools:label:regrole_2') . "<span class='schools-reg-spacer'></span>" => 2,
			elgg_echo('schools:label:regrole_3') . $other_input => 3,
		),
));

// What school?
$school_name_label = elgg_echo('schools:label:regschoolname');
$school_name_input = elgg_view('input/text', array(
	'name' => 'reg_school_name',
	'value' => $reg_school_name,
));

// School URL?
$school_link_label = elgg_echo('schools:label:regschoollink');
$school_link_input = elgg_view('input/url', array(
	'name' => 'reg_school_url',
	'value' => $reg_school_url,
));

// More info about you?
$about_link_label = elgg_echo('schools:label:regaboutlink');
$about_link_input = elgg_view('input/url', array(
	'name' => 'reg_about_url',
	'value' => $reg_about_url,
));

// Hidden more info toggled input
$more_info_hidden = elgg_view('input/hidden', array(
	'name' => 'more_info_toggled',
	'value' => $more_info_toggled
));

// This form might be a 'hook' form. In that case we need some extra fields (ie a submit input)
// @todo this should be a plugin hook as well?
if (elgg_extract('is_hook_form', $vars, FALSE)) {
	// Show required label
	$required = elgg_echo('schools:label:denotesrequired');
	$required = "<br /><br /><span class='schools-request-form-required '>$required</span>";
	// Grab session input from query string
	$extended_content = elgg_view('input/hidden', array('name' => 'session', 'value' => get_input('session')));
	$extended_submit = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('submit')));
	$extended_submit .= $required;
}

// Build form elements
$content = <<<HTML
	<div>
		$reg_code_radio
	</div>
	<div id='schools-reg-code-container' class="mtm $reg_code_div_toggled">
		<label for='schools-reg-code' class='$label_class'>$reg_code_label</label>$asterisk<br />
		$reg_code_input
	</div>
	<div id='schools-more-info-container' class='$more_info_div_toggled'>
		<div>
			<label>$role_label</label>$asterisk<br />
			$role_input
		</div><br />
		<div>
			<label>$school_name_label</label>$asterisk<br />
			$school_name_input
		</div><br />
		<div>
			<label>$school_link_label</label>$asterisk<br />
			$school_link_input
		</div><br />
		<div>
			<label>$about_link_label</label><br />
			$about_link_input
		</div>
		$more_info_hidden
	</div>
	$extended_content
	$extended_submit
HTML;

echo $content;