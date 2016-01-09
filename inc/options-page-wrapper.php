<div class="wrap">
	<h2>matchHeight</h2>
	<form method="post" action="options.php">
<?php 
	settings_fields('mh_settings-group'); //passing in the settings group as defined register settings
	do_settings_sections('matchheight'); //page that it appears on
	submit_button('Update');
?>
</form></div>



 
 