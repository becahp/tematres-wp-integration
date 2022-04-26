<?php

/** 
 * Plugin Name: Tematres WP
 * Plugin URI: https://github.com/becahp
 * Description: Integração entre WP e Tematres
 * Version: 1.0
 * Author: Rebeca Moura e Lucas Rodrigues
 * Author URI: https://github.com/becahp
 */

//Definicoes
define('TEMATRES_WP_PATH', plugin_dir_path(__FILE__) . '/');
define('TEMATRES_WP_JS_PATH', plugin_dir_path(__FILE__) . 'js/');
define('TEMATRES_WP_JS_URL', plugin_dir_url(__FILE__) . 'js/');
define('TEMATRES_WP_CSS_PATH', plugin_dir_path(__FILE__) . 'css/');
define('TEMATRES_WP_CSS_URL', plugin_dir_url(__FILE__) . 'css/');

/**
 * Registro dos scripts usados
 */
function tematres_wp_style_scripts()
{

    $ver = time();

    wp_register_style('css_tematres_wp', TEMATRES_WP_CSS_URL . 'tematres-wp.css', false, $ver);
    wp_enqueue_style('css_tematres_wp');

    wp_enqueue_script('js_tematres_wp', TEMATRES_WP_JS_URL . 'tematres-wp.js', array('jquery'), $ver);
}
add_action('wp_enqueue_scripts', 'tematres_wp_style_scripts');

/**
 * Função que adiciona o menu ao Painel
 */
function tematres_wp_admin_menu()
{
    add_menu_page(
        __('Tematres WP', 'tematres-wp-plugin'),
        __('Tematres WP', 'tematres-wp-plugin'),
        'manage_options', //'edit_posts', //'administrator'
        'tematres-wp-plugin',
        'tematres_wp_admin_page',
        'dashicons-chart-area',
        26
    );
}
add_action('admin_menu', 'tematres_wp_admin_menu');

function tematres_wp_admin_page()
{
    // set this var to be used in the settings-display view
    // $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
    if (isset($_GET['error_message'])) {
        add_action('admin_notices', array(
            'pagina_config_mensagem_erro'
        ));
        do_action('admin_notices', $_GET['error_message']);
    }
    require_once 'pagina-configuracao-display.php';
}

function pagina_config_mensagem_erro($error_message)
{
    switch ($error_message) {
        case '1':
            $message       = __('There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'tematres-wp-plugin');
            $err_code      = esc_attr('pagina_config_tematres_url');
            $setting_field = 'pagina_config_tematres_url';
            break;
    }

    $type = 'error';
    add_settings_error($setting_field, $err_code, $message, $type);
}


/**
 * Função registerAndBuildFields chamada de registrar_construir_campos
 * https://blog.wplauncher.com/create-wordpress-plugin-settings-page/
 * https://github.com/wplauncher/settings-page
 * This file is where you define what fields you want to include in your settings form and it hooks up to another function that handles saving and pre-population of your form if users have already filled it out.
 * Pay close attention to the wp_data parameter in this function as it determines how you want this field to be treated by WordPress. On settings pages, you want to set this parameter as an option because plugin settings are typically applicable globally to your WordPress site. However, if you used this function in a custom post type, you would want to use post_meta, so that the information was attached to a post
 */
function pagina_config_registrar_construir_campos()
{
    /**
     * First, we add_settings_section. This is necessary since all future settings must belong to one.
     * Second, add_settings_field
     * Third, register_setting
     */

    add_settings_section(
        // ID used to identify this section and with which to register options
        'pagina_config_secao',
        // Title to be displayed on the administration page
        'URL do Tematres',
        // Callback used to render the description of the section
        'pagina_config_mensagem_geral',
        // Page on which to add this section of options
        'pagina_config'
    );

    unset($args);

    $args = array(
        'type' => 'input',
        'subtype' => 'url',
        'id' => 'pagina_config_tematres_url',
        'name' => 'pagina_config_tematres_url',
        'required' => 'true',
        'placeholder' => 'Insira uma URL',
        'size' => 70,
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
    );

    add_settings_field(
        'pagina_config_tematres_url',
        'Tematres URL',
        'pagina_config_renderizar_campos',
        'pagina_config',
        'pagina_config_secao',
        $args
    );

    register_setting('pagina_config', 'pagina_config_tematres_url');
}

function pagina_config_mensagem_geral()
{
    echo '<p>Essa configuração se aplica a todas as funcionalidades do plugin Tematres WP.</p>';
}

function pagina_config_renderizar_campos($args)
{
    /* EXAMPLE INPUT
    'type'      => 'input',
    'subtype'   => '',
    'id'    => $this->plugin_name.'_example_setting',
    'name'      => $this->plugin_name.'_example_setting',
    'required' => 'required="required"',
    'get_option_list' => "",
    'value_type' = serialized OR normal,
    'wp_data'=>(option or post_meta),
    'post_id' =>
    */

    if ($args['wp_data'] == 'option') {

        $wp_data_value = get_option($args['name']);
    } elseif ($args['wp_data'] == 'post_meta') {

        $wp_data_value = get_post_meta($args['post_id'], $args['name'], true);
    }

    switch ($args['type']) {

        case 'input':
            $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;

            if ($args['subtype'] != 'checkbox') {

                $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
                $prependEnd   = (isset($args['prepend_value'])) ? '</div>' : '';
                $step         = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
                $min          = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
                $max          = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';

                if (isset($args['disabled'])) {
                    // hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                    echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                } else {

                    // O CAMPO NORMAL É RENDERIZADO AQUI

                    //echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;

                    echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" required="' . $args['required'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="' . $args['size'] . '" placeholder="' . $args['placeholder'] . '" value="' . esc_attr($value) . '" />' . $prependEnd;
                }
            } else {
                $checked = ($value) ? 'checked' : '';
                echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
            }
            break;
        default:
            # code...

            break;
    }
}
add_action('admin_init', 'pagina_config_registrar_construir_campos');
