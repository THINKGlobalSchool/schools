<?php
/**
 * Spotx Schools JS
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */
?>
//<script>
elgg.provide('elgg.schools');

// Init function
elgg.schools.init = function () {	
	// Register change handler for reg code radio field
	$(document).delegate('input[name=reg_code_radio]', 'change', elgg.schools.codeRadioChange);
	
	// Admin form check all click handler
	$('.schools-checkall').click(function() {
		var checked = $(this).attr('checked') == 'checked';
		$('#schools-pending-users-form .elgg-body, #schools-pending-requests-form .elgg-body').find('input[type=checkbox]').attr('checked', checked);
	});

	// Pending users bulk form submit
	$('.schools-pending-submit').click(function(event) {
		var $form = $('#schools-pending-users-form');
		event.preventDefault();

		// check if there are selected users
		if ($('#schools-pending-users-form .elgg-body').find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm($(this).attr('title'))) {
			return false;
		}

		$form.attr('action', $(this).attr('href')).submit();
	});
	
	// Pending users bulk form submit
	$('.schools-request-submit').click(function(event) {
		var $form = $('#schools-pending-requests-form');
		event.preventDefault();

		// check if there are selected users
		if ($('#schools-pending-requests-form .elgg-body').find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm($(this).attr('title'))) {
			return false;
		}

		$form.attr('action', $(this).attr('href')).submit();
	});
}

// Click handler for reg code radio field on reg form
elgg.schools.codeRadioChange = function(event) {

	if ($(this).val() == 1) {
		// Enable code input
		$('input[name=school_registration_code]')
			.removeAttr('disabled')
			.removeClass('schools-input-disabled');
		
		// Remove disabled class from reg code label
		$('label[for=schools-reg-code]')
			.removeClass('schools-text-disabled');

		// Slide in code container
		$('#schools-reg-code-container').slideToggle();
		
		// Slide up more info container
		$('#schools-more-info-container').slideUp();
	
		// Set hidden toggled state
		$('input[name=more_info_toggled]').val('off');
	} else if ($(this).val() == 2) {
		// Clear and disable code input
		$('input[name=school_registration_code]')
			.val('')
			.attr('disabled', 'DISABLED')
			.addClass('schools-input-disabled');
		
		// Add disabled class to reg code label
		$('label[for=schools-reg-code]')
			.addClass('schools-text-disabled');

		// Slide in more info container
		$('#schools-more-info-container').slideToggle();

		// Slide up code container
		$('#schools-reg-code-container').slideUp();

		// Set hidden toggled state
		$('input[name=more_info_toggled]').val('on');
	}
}


elgg.register_hook_handler('init', 'system', elgg.schools.init);