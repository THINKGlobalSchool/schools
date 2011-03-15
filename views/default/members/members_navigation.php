<?php
	/**
	 * SCHOOLS OVERRIDE! Includes school tabs
	 * A simple view to provide the user with group filters and the number of group on the site
	 **/
	 
	 $members = $vars['count'];
	 if(!$num_groups)
	 	$num_groups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = elgg_get_site_url() . "mod/members/index.php";

?>
<div class="elgg_horizontal_tabbed_nav margin_top">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('members:label:newest'); ?></a></li>
	<li <?php if($filter == "pop") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=pop"><?php echo elgg_echo('members:label:popular'); ?></a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('members:label:active'); ?></a></li>
	<?php
		$schools = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'school', 
			'limit' => 0,
		));	
			
		foreach($schools as $school) {
			// Bit hacky, but I'd like to allow for a test school
			if ($school->title != 'Test School') {
				echo "<li class='" . ($filter == $school->getGUID() ? 'selected ' : '') . " edt_tab_nav'>" 
						. elgg_view('output/url', array('href' => elgg_get_site_url() . "pg/schools/members?filter={$school->getGUID()}", 
														'text' => $school->title, 
														'class' => 'schools')) . 
					 "</li>";
			}
		}
	?>
	<li <?php if($filter == "tgs") echo "class='selected'"; ?>><a href="<?php echo elgg_get_site_url() . 'pg/schools/members'; ?>?filter=tgs">TGS</a></li>
</ul>
</div>