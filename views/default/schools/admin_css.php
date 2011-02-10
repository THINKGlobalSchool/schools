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

/** Breadcrumbs **/

.breadcrumbs {
	font-size: 80%;
	line-height:1.2em;
	color:#bababa;
	position: relative;
	top:-6px;
	left:0;
}
.breadcrumbs a {
	color:#999999;
	font-weight:bold;
	text-decoration: none;
}
.breadcrumbs a:hover {
	color: #0054a7;
	text-decoration: underline;
}

/** School Info Display **/

.school-info-table {
	margin-top: 10px;
	margin-bottom: 10px;
	border: 1px solid #AAAAAA;
}

.school-info-table tr {

}

.school-info-table td {
	padding: 10px;
}

.school-info-table .label {
	border-right: 1px solid #AAAAAA;
}

.school-info-table tr:nth-child(odd) { 
	background-color:#eee; 
}

.school-info-table tr:nth-child(even) { 
	background-color:#fff;
}

/** School Listing **/

.school-listing {
	width: 100%;
	border-bottom: 1px dotted #AAAAAA;
	height: 20px;
	padding-top: 10px;
	padding-bottom: 10px;
	padding-right: 3px;
}

.school-listing:hover {
	background: #EEEEEE;
}

.school-listing .school-title {
	float: left;
}

.school-controls {
	float: right;
}

/** General **/

.school-header {
	border-bottom: 1px solid #CCCCCC;
}

.school-header-title {
	float:left;
}

.school-header-title h2 {
	border: none;
}

.school-edit a {
	color: #999999;
}

.school-delete-button {
	width:14px;
	height:14px;
	margin:0;
	float:right;
	margin-top:3px;
	padding-left: 10px;
}
.school-delete-button a {
	display:block;
	cursor: pointer;
	width:14px;
	height:14px;
	background: url("<?php echo elgg_get_site_url(); ?>mod/tgstheme/graphics/elgg_sprites.png") no-repeat -200px top;
	text-indent: -9000px;
	text-align: left;
}
.school-delete-button a:hover {
	background-position: -200px -16px;
}

a.school-action-button {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	background-color:#cccccc;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	background-position: 0 0;
	border:1px solid #999999;
	color:#333333;
	padding:2px 15px 2px 15px;
	text-align:center;
	font-weight:bold;
	text-decoration:none;
	text-shadow:0 1px 0 #ffffff;
	cursor:pointer;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}

a.school-action-button:hover,
a.school-action-button:focus {
	background-position:0 -15px;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	color:#111111;
	text-decoration: none;
	background-color:#cccccc;
	border:1px solid #999999;
}
.schoolaction-button:active {
	background-image:none;
}