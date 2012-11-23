<?php
/**
 * Spotx Schools admin css
 * 
 * @package SpotxSchools
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org/
 * 
 */
?>

/** School Info Display **/
.school-info-table tr:nth-child(odd) td { 
	background: #eee !important; 
}

.school-info-table tr:nth-child(even) td { 
	background: #fff !important;
}

.school-code {
	width: auto;
	font-size: 16px;
	font-weight: bold;
	color: #444;
}

li.elgg-list-item {
	border-bottom: 1px solid #ddd;
	margin: 0;
}

li.elgg-list-item:hover {
	background: #eee;
}

.elgg-menu-item-refresh {
	padding-bottom: 5px;
}

/** Reg form styles **/
div#schools-more-info-container {
	display: none;
}

div#schools-more-info-container.more-info-toggled {
	display: block;
}

div#schools-more-info-container ul.elgg-input-radios {
	margin: 5px 15px;
}

div#schools-more-info-container ul.elgg-input-radios li {
	margin-top: 5px;
}

div#schools-more-info-container ul.elgg-input-radios li input,
ul#schools-registration-code-radio li input {
	margin-right: 5px;
}

.schools-reg-spacer {
	display: inline-block;
	height: 21px;
}

.schools-input-disabled {
	background-color: #DDD;
}

.schools-text-disabled {
	color: #AAA;
}

#schools-reg-code-container {
	display: none;
}

#schools-reg-code-container.reg-code-toggled {
	display: block;
}

/* Pending Users Styles */
.schools-pending-module > .elgg-head * {
	color: white;
}
.schools-pending-module > .elgg-body * {
	color: #333;
}

.schools-unapproved-user-details {
	font-size: small;
}

.schools-unapproved-user-details label {
	font-size: small !important;
}

.schools-unapproved-user-details p {
	margin-bottom: 0px;
}

.schools-unapproved-user-details table td {
	padding-right: 10px;
	padding-top: 3px;
}

.schools-request-form-required {
	color: red;
	font-weight: bold;
}

.elgg-owner-block .schools-info {
	margin-top: 4px;
}

.elgg-owner-block .schools-info a {
	color: #EEEEEE; !important;
	font-weight: bold;
}

/** Members schools tab **/
#members-schools-module {
	width: 35%;
	float: left;
}


#members-school-user-list {
	float: right;
	width: 64%;
}

#members-schools-module li.elgg-item {
	border-bottom: 0 none;
	margin: 0;
	padding: 2px 1px 2px;
}

#members-schools-module li.elgg-item:hover {
	cursor: pointer;
	background: #eee;
}

#members-schools-module ul.elgg-list {
    border-top: 0 none;
}

#members-schools-module .school-state-selected {
	background: #ccc !important;
}
