<?php
/**
 * 
 */


$facebook_link = facebookservice_get_authorize_url(elgg_get_site_url() . "pg/schools/facebookauthorize");

$login = <<<__HTML
<div id="facebook_connect">
	<a href="$facebook_link">
		<img src="{$vars['url']}mod/facebookservice/graphics/login-button.png" />
	</a>
</div>
__HTML;

echo $login;
