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
	
	<h2>Configurações do plugin Tematres WP</h2>
	
	<!--NEED THE settings_errors below so that the errors/success messages are shown after submission - wasn't working once we started using add_menu_page and stopped using add_options_page so needed this-->
	<?php settings_errors(); ?>  
	<form method="POST" action="options.php">
		<?php 
			settings_fields( 'pagina_config' );
			do_settings_sections( 'pagina_config' ); 
		?>             
		<?php submit_button(); ?>  
	</form> 

	<span>
		A URL salva no momento é:
		<?php echo get_option('pagina_config_tematres_url'); ?>  
	</span>
</div>
