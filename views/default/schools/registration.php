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
	$toggled = 'more-info-toggled';
	$label_class = 'schools-text-disabled';
	$link_class = 'no-code-toggled-on';
} else {
	$more_info_toggled = 'off';
}
 
// Reg code input
$reg_code_label = elgg_echo('schools:label:schoolregcode');
$reg_code_input = elgg_view('input/text', $reg_code_input_options);

// Link to display extra fields for moderated registration questions
$no_code_link = elgg_view('output/url', array(
	'text' => elgg_echo('schools:label:nocode'),
	'id' => 'schools-no-code-toggler',
	'rel' => 'toggle',
	'href' => '#schools-more-info-container',
	'class' => $link_class,
));

// What do you do? (Don't know what to put here..)
$role_label = elgg_echo('schools:label:regwhichrole');

$other_input = elgg_view('input/text', array(
	'name' => 'reg_school_role_other',
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

// Build form elements
$content = <<<HTML
	<div class="mtm">
		<label for='schools-reg-code' class='$label_class'>$reg_code_label</label><br />
		$reg_code_input
	</div>
	<div>
		<span class='schools-no-code'>
			$no_code_link
		</span>
	</div>
	<div id='schools-more-info-container' class='$toggled'>
		<div>
			<label>$role_label</label><br />
			$role_input
		</div><br />
		<div>
			<label>$school_name_label</label><br />
			$school_name_input
		</div><br />
		<div>
			<label>$school_link_label</label><br />
			$school_link_input
		</div><br />
		<div>
			<label>$about_link_label</label><br />
			$about_link_input
		</div>
		$more_info_hidden
	</div>
HTML;

echo $content;