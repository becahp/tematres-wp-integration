<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       plugin_name.com/team
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	
	<div id="icon-themes" class="icon32"></div>
	
	<h2>
	<?php echo __('Tematres WP Integration plugin settings', 'tematres-wp-integration'); ?> 
	</h2>
	
	<!--NEED THE settings_errors below so that the errors/success messages are shown after submission - wasn't working once we started using add_menu_page and stopped using add_options_page so needed this-->
	<?php settings_errors(); ?>  
	<form method="POST" action="options.php">
		<?php 
			settings_fields("settings_all");
			do_settings_sections("plugin-options");
		?>             
		<?php submit_button(); ?>  
	</form> 

</div>
