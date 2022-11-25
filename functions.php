<?php

/** 
 * Plugin Name: Tematres WP Integration
 * Plugin URI:  https://github.com/becahp/tematres-wp-integration
 * Description: WordPress and Tematres Integration
 * Version: 1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author: Rebeca Moura e Lucas Rodrigues
 * Author URI: https://github.com/becahp
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tematres-wp-integration
 * Domain Path:       /languages
 */

//Definitions
define('TEMATRES_WP_PATH', plugin_dir_path(__FILE__) . '/');
define('TEMATRES_WP_JS_PATH', plugin_dir_path(__FILE__) . 'js/');
define('TEMATRES_WP_JS_URL', plugin_dir_url(__FILE__) . 'js/');
define('TEMATRES_WP_CSS_PATH', plugin_dir_path(__FILE__) . 'css/');
define('TEMATRES_WP_CSS_URL', plugin_dir_url(__FILE__) . 'css/');

// Include API functions
include "functions-api.php";

/**
 * Register scripts used on common pages
 */
function tmwpi_tematres_wp_style_scripts()
{
    $ver = time();

    wp_register_style('css_tematres_wp', TEMATRES_WP_CSS_URL . 'tematres-wp-integration.css', false, $ver);
    wp_enqueue_style('css_tematres_wp');

    wp_register_style('css_select2', TEMATRES_WP_CSS_URL . 'select2.min.css', false, $ver);
    wp_enqueue_style('css_select2');

    wp_enqueue_script('js_tematres_wp', TEMATRES_WP_JS_URL . 'tematres-wp-integration.js', array('jquery', 'js_select2'), $ver);

    wp_enqueue_script('js_select2', TEMATRES_WP_JS_URL . 'select2.min.js', array(), $ver);

    wp_localize_script(
        'js_tematres_wp',
        'tmwpi_my_ajax_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'home_url' => home_url(),
            'texto_escreva_mais' => __('Please write more', 'tematres-wp-integration'),
            'texto_pesquisa' => __('Searching...', 'tematres-wp-integration'),
        )
    );
}
add_action('wp_enqueue_scripts', 'tmwpi_tematres_wp_style_scripts');

/**
 * Register scripts used on admin pages
 */
function tmwpi_tematres_wp_style_scripts_admin()
{
    $ver = time();

    wp_register_style('css_tematres_wp', TEMATRES_WP_CSS_URL . 'tematres-wp-integration.css', false, $ver);
    wp_enqueue_style('css_tematres_wp');

    wp_enqueue_script('js_tematres_wp', TEMATRES_WP_JS_URL . 'tematres-wp-integration.js', array(), $ver);
    wp_localize_script(
        'js_tematres_wp',
        'tmwpi_my_ajax_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'home_url' => home_url(),
            'texto_escreva_mais' => __('Please write more', 'tematres-wp-integration'),
            'texto_pesquisa' => __('Searching...', 'tematres-wp-integration'),
        )
    );
}
add_action('admin_enqueue_scripts', 'tmwpi_tematres_wp_style_scripts_admin');

/**
 * Adds the new menu to the painel
 */
function tmwpi_tematres_wp_admin_menu()
{
    add_menu_page(
        __('Tematres WP Integration', 'tematres-wp-integration'),
        __('Tematres WP Integration', 'tematres-wp-integration'),
        'manage_options',
        'tematres-wp-integration',
        'tmwpi_tematres_wp_admin_page',
        'dashicons-chart-area',
        26
    );
}
add_action('admin_menu', 'tmwpi_tematres_wp_admin_menu');

function tmwpi_tematres_wp_admin_page()
{
    if (isset($_GET['error_message'])) {
        add_action('admin_notices', array(
            'tmwpi_pagina_config_mensagem_erro'
        ));
        do_action('admin_notices', $_GET['error_message']);
    }
    require_once 'pagina-configuracao-display.php';
}

function tmwpi_pagina_config_mensagem_erro($error_message)
{
    switch ($error_message) {
        case '1':
            $message       = __('There was an error adding this setting. Please try again. If this persists, shoot us an email.', 'tematres-wp-integration');
            $err_code      = esc_attr('pagina_config_tematres_url');
            $setting_field = 'pagina_config_tematres_url';
            break;
    }

    $type = 'error';
    add_settings_error($setting_field, $err_code, $message, $type);
}


/**
 * Function registerAndBuildFields called by tmwpi_pagina_config_registrar_construir_campos
 * This file is where you define what fields you want to include in your settings form and it hooks up to another function that handles saving and pre-population of your form if users have already filled it out.
 * Pay close attention to the wp_data parameter in this function as it determines how you want this field to be treated by WordPress. On settings pages, you want to set this parameter as an option because plugin settings are typically applicable globally to your WordPress site. However, if you used this function in a custom post type, you would want to use post_meta, so that the information was attached to a post
 */
function tmwpi_pagina_config_registrar_construir_campos()
{
    add_settings_section(
        // ID used to identify this section and with which to register options
        'pagina_config_secao',
        // Title to be displayed on the administration page
        __('Tematres URL', 'tematres-wp-integration'),
        // Callback used to render the description of the section
        'tmwpi_pagina_config_mensagem_geral_url',
        // Page on which to add this section of options
        'plugin-options'
    );

    unset($args);

    $args = array(
        'type' => 'input',
        'subtype' => 'url',
        'id' => 'pagina_config_tematres_url',
        'name' => 'pagina_config_tematres_url',
        'required' => 'true',
        'placeholder' => __('Insert URL', 'tematres-wp-integration'),
        'size' => 70,
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
    );

    add_settings_field(
        'pagina_config_tematres_url',
        'Tematres URL:',
        'tmwpi_pagina_config_renderizar_campos',
        'plugin-options',
        'pagina_config_secao',
        $args
    );

    register_setting('settings_all', 'pagina_config_tematres_url');

    unset($args);

    $args = array(
        'type' => 'input',
        'subtype' => 'text',
        'id' => 'tematres_tag_name',
        'name' => 'tematres_tag_name',
        'required' => 'true',
        'pattern' => ".*\S+.*",
        'placeholder' => __('Insert Tag Name', 'tematres-wp-integration'),
        'size' => 70,
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
    );

    add_settings_section(
        "section-tematres-tags-name",
        __("Tag Name", 'tematres-wp-integration'),
        "tmwpi_pagina_config_mensagem_geral_name",
        "plugin-options"
    );

    add_settings_field(
        'tematres_tag_name',
        __("Insert Tag Name:", 'tematres-wp-integration'),
        "tmwpi_pagina_config_renderizar_campos",
        "plugin-options",
        "section-tematres-tags-name",
        $args
    );

    register_setting("settings_all", 'tematres_tag_name');

    add_settings_section(
        "section-posts-types",
        __("Post types where Tematres Tags will be applied", 'tematres-wp-integration'),
        'tmwpi_pagina_config_mensagem_geral_posts',
        "plugin-options"
    );

    add_settings_field(
        'post_types',
        __("Select Post types:", 'tematres-wp-integration'),
        "tmwpi_post_types_checkbox_field_1_render",
        "plugin-options",
        "section-posts-types"
    );

    register_setting("settings_all", 'post_types');
}
add_action('admin_init', 'tmwpi_pagina_config_registrar_construir_campos');

function tmwpi_post_types_checkbox_field_1_render()
{

    $options = get_option('post_types', []);

    $post_types_checkbox_field_1 = isset($options['post_types_checkbox_field_1'])
        ? (array) $options['post_types_checkbox_field_1']
        : [];

    $args = array(
        'public'   => true,
        '_builtin' => false,
    );
    $output = 'objects';
    $operator = 'and';
    $post_types = get_post_types($args, $output, $operator);

    array_unshift($post_types, get_post_types([], 'objects')["post"]);
    foreach ($post_types as $post) {
        $slug = $post->name;
        $name = $post->label;

?>
        <input type='checkbox' id='<?php echo esc_attr($slug) ?>' name='post_types[post_types_checkbox_field_1][]' <?php checked(in_array($slug, $post_types_checkbox_field_1), 1); ?> value='<?php echo esc_attr($slug) ?>'>
        <label for="<?php echo esc_attr($slug) ?>"><?php echo esc_attr($name) ?></label>
        <br>
    <?php
    }
}

function tmwpi_pagina_config_mensagem_geral_url()
{
    echo '<p>';
    echo __('This setting applies to all features of the Tematres WP Integration plugin.', 'tematres-wp-integration');
    echo '</p>';

    echo '<p><strong>';
    echo __('URL example:', 'tematres-wp-integration');
    echo ' </strong>';
    echo 'http://example.com/vocab/services.php';
    echo '</p>';

    echo '<p><strong>';
    echo __('Currently, the saved URL is: ', 'tematres-wp-integration');
    echo ' </strong>';
    echo esc_attr(get_option('pagina_config_tematres_url'));
    echo '</p>';
}

function tmwpi_pagina_config_mensagem_geral_name()
{
    echo '<p>';
    echo __("Define the Tag Name (this doesn't affect the term slug)", 'tematres-wp-integration');
    echo '</p>';
}

function tmwpi_pagina_config_mensagem_geral_posts()
{
    echo '<p>';
    echo __("Choose the post types where the Tematres Tags will be applied", 'tematres-wp-integration');
    echo '</p>';
}

function tmwpi_pagina_config_renderizar_campos($args)
{
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
                    echo esc_attr($prependStart) . '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '_disabled" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . esc_attr($args['id']) . '" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '" size="40" value="' . esc_attr($value) . '" />' . esc_attr($prependEnd);
                } else {
                    // The common input is rendered here

                    $pattern = $args['pattern'] ? ' "pattern="' .  $args['pattern'] : '';

                    echo esc_attr($prependStart) . '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" required="' . esc_attr($args['required']) . esc_attr($pattern) . '" ' . esc_attr($step) . ' ' . esc_attr($max) . ' ' . esc_attr($min) . ' name="' . esc_attr($args['name']) . '" size="' . esc_attr($args['size']) . '" placeholder="' . esc_attr($args['placeholder']) . '" value="' . esc_attr($value) . '" />' . esc_attr($prependEnd);
                }
            } else {
                $checked = ($value) ? 'checked' : '';
                echo '<input type="' . esc_attr($args['subtype']) . '" id="' . esc_attr($args['id']) . '" "' . esc_attr($args['required']) . '" name="' . esc_attr($args['name']) . '" size="40" value="1" ' . esc_attr($checked) . ' />';
            }
            break;
        default:
            # code...

            break;
    }
}

//--------------------------------------------------------------------- API do tematres

add_shortcode('tmwpi_shortcode_show_tags_tematres', 'tmwpi_show_tags_tematres');
// Ex: [tmwpi_shortcode_show_tags_tematres] 
function tmwpi_show_tags_tematres()
{
    ?>
    <form action="<?php echo esc_url(get_permalink()) ?>" method="post">
        <div>
            <label for="field-name"> Informe o Termo:</label>
            <?php
            $termo = sanitize_text_field($_POST['termo']);
            if (empty($termo)) {
                echo "<input type=\"text\" name=\"termo\" id=\"termo\" minlength=\"2\" placeholder=\"laranja\" required />";
            } else {
                echo "<input type=\"text\" name=\"termo\" id=\"termo\" minlength=\"2\" value=\"" . esc_attr($termo) . "\" placeholder=\"laranja\" required />";
            }
            ?>
        </div>
        <div>
            <button type="submit">Enviar</button>
        </div>
    </form>
<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of input field
        $termo = sanitize_text_field($_POST['termo']);
        if (empty($termo)) {
            echo "Termo vazio";
        } else {
            $urlTematres = get_option('pagina_config_tematres_url');
            echo "<p>Query de busca é \"" . esc_attr($termo) . "\"</p>";
            $urlBusca = $urlTematres . "?task=search&arg=" . strtolower($termo);
            echo "<p>URL de busca é: " . esc_url($urlBusca) . "</p>";

            $xml = simplexml_load_file($urlBusca)->result;

            //carrega o arquivo XML e retornando um Array
            $termos = array();
            foreach ($xml->term as $item) {
                array_push($termos, $item->string);
            }

            for ($i = 0; $i < count($termos); ++$i) {
                echo "<strong>Termo " . esc_attr($i) . ":</strong> " . esc_attr($termos[$i]) . "<br/>";
            }
        }
    }
}

//--------------------------------------------------------------------- // Remover Categories and Tags (FORMA CORRETA)
function tmwpi_register_private_taxonomy()
{
    $args = array(
        'label'        => __('Tags', 'tematres-wp-integration'),
        'public'       => false,
        'rewrite'      => false,
        'hierarchical' => true
    );

    // escondendo a tag padrão do WP caso o Post seja selecionado no plugin
    $post_type = get_option('post_types')["post_types_checkbox_field_1"];

    $flag = False;
    foreach ($post_type as $post) {
        if ($post == "post") $flag = True;
    }
    if ($flag) register_taxonomy('post_tag', 'post', $args);

    $custom_name = get_option("tematres_tag_name");
    $labels = array(
        'name'                       => _x(get_option("tematres_tag_name"), 'taxonomy general name', 'tematres-wp-integration'),
        'singular_name'              => _x(get_option("tematres_tag_name"), 'taxonomy singular name', 'tematres-wp-integration'),
        'search_items'               => sprintf(esc_attr__('Search %s', 'tematres-wp-integration'), $custom_name),
        'popular_items'              => sprintf(esc_attr__('Popular %s', 'tematres-wp-integration'), $custom_name),
        'all_items'                  => sprintf(esc_attr__('All %s', 'tematres-wp-integration'), $custom_name),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => sprintf(esc_attr__('Edit %s', 'tematres-wp-integration'), $custom_name),
        'update_item'                => sprintf(esc_attr__('Update %s', 'tematres-wp-integration'), $custom_name),
        'add_new_item'               => sprintf(esc_attr__('Add New %s', 'tematres-wp-integration'), $custom_name),
        'new_item_name'              => sprintf(esc_attr__('New %s Name', 'tematres-wp-integration'), $custom_name),
        'separate_items_with_commas' => sprintf(esc_attr__('Separate %s with commas', 'tematres-wp-integration'), $custom_name),
        'add_or_remove_items'        => sprintf(esc_attr__('Add or remove %s', 'tematres-wp-integration'), $custom_name),
        'choose_from_most_used'      => sprintf(esc_attr__('Choose from the most used %s', 'tematres-wp-integration'), $custom_name),
        'not_found'                  => sprintf(esc_attr__('No %s found', 'tematres-wp-integration'), $custom_name),
        'all_items'                  => sprintf(esc_attr__('All %s', 'tematres-wp-integration'), $custom_name),
        'menu_name'                  => sprintf(esc_attr__('%s', 'tematres-wp-integration'), $custom_name),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => false,
        //'show_in_nav_menus'     => false,
        //'show_in_menu'          => false,
        'show_in_quick_edit'    => false,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'publicly_queryable'    => true,
        'rewrite'               => array('slug' => 'tematres_wp'),
    );

    register_taxonomy('tematres_wp', $post_type, $args);
}
add_action('init', 'tmwpi_register_private_taxonomy', 0);

//--------------------------------------------------------------------- criando a metabox
/*
 * Add
 */
function tmwpi_add_new_tags_metabox()
{
    $id = 'tematres-wp-integration_tag'; // it should be unique
    $heading = get_option("tematres_tag_name"); // meta box heading
    $callback = 'tmwpi_metabox_content'; // the name of the callback function

    $post_type = get_option('post_types')["post_types_checkbox_field_1"];

    $position = 'side';
    $pri = 'low'; // priority, 'default' is good for us
    add_meta_box($id, $heading, $callback, $post_type, $position, $pri);
}
add_action('admin_menu', 'tmwpi_add_new_tags_metabox');

/*
 * Fill
 */
function tmwpi_metabox_content($post)
{
    $term_obj_list = get_the_terms($post->ID, 'tematres_wp');
    $optionArray =  wp_list_pluck($term_obj_list, 'name');

    echo '<div id="taxonomy-post_tag" class="categorydiv">';
    echo '<select id="escolha_tags" class="tematres-wp-integration-escolhas" name="escolha_tags[]" multiple="multiple">';

    if (empty($optionArray)) {
        echo '<option value="">Selecione as Tags</option>';
    } else {

        for ($i = 0; $i < count($optionArray); $i++) {
            echo '<option value="' . esc_attr($optionArray[$i]) . '" selected="selected">' . esc_attr($optionArray[$i]) . '</option>';
        }
    }

    echo '</select>';
    echo '</div>';
}

add_shortcode('tmwpi_shortcode_campo_seletor_tags', 'tmwpi_campo_seletor_tags');
// Ex: [tmwpi_shortcode_campo_seletor_tags div_id="taxonomy-post_tag" select_id="escolha_tags" ]
function tmwpi_campo_seletor_tags($params)
{
    $var = shortcode_atts([
        'div_id' => 'taxonomy-post_tag',
        'select_id' => 'escolha_tags',
    ], $params);

    echo '<div id="' . esc_attr($var['div_id']) . '" class="categorydiv">';
    echo '<select id="' . esc_attr($var['select_id']) . '" class="tematres-wp-integration-escolhas" name="' . esc_attr($var['select_id']) . '[]" multiple="multiple">';
    echo '<option value="">Selecione as Tags</option>';
    echo '</select>';
    echo '</div>';
}

/*
* Função que salva a tag inserida no select2 como tag do WP Core se ela ainda não existir
*
* Função chamada pelo Javascript JS
*/
function tmwpi_ajax_criar_tags()
{

    $tag = sanitize_text_field($_POST['tag']);
    $tag_escolhida = (isset($tag)) ? $tag : '';

    if (empty($tag_escolhida))
        return;

    // Checa se o termo existe
    $term = term_exists($tag_escolhida, 'tematres_wp');

    // Caso exista, apenas avisa no console
    if ($term !== 0 && $term !== null) {
        echo esc_attr($tag_escolhida) . ' ' . __('already exists.', 'tematres-wp-integration');
    }

    // Caso contrário, cria o termo
    if ($term == 0 || $term === null) {
        wp_insert_term(
            $tag_escolhida, // the term 
            'tematres_wp', // the taxonomy
            array()
        );

        echo esc_attr($tag_escolhida) . ' ' . __('saved as Tematres Tags', 'tematres-wp-integration');
    }

    wp_die();
}
add_action('wp_ajax_nopriv_tmwpi_ajax_criar_tags', 'tmwpi_ajax_criar_tags');
add_action('wp_ajax_tmwpi_ajax_criar_tags', 'tmwpi_ajax_criar_tags');

add_action('save_post', 'tmwpi_set_post_default_category', 10, 3);
function tmwpi_set_post_default_category($post_id)
{
    // https://wordpress.stackexchange.com/questions/24736/wordpress-sanitize-array
    $escolhas_tag = array_map('sanitize_text_field', $_POST['escolha_tags']);
    wp_set_post_terms($post_id, $escolhas_tag, 'tematres_wp', false);
}

/**
 * Set Localization
 * https://stackoverflow.com/questions/12638547/how-to-translate-a-wordpress-plugin-in-any-language
 */

// Localization
add_action('init', 'tmwpi_localizationsample_init');
function tmwpi_localizationsample_init()
{
    $path = dirname(plugin_basename(__FILE__)) . '/languages/';

    $loaded = load_plugin_textdomain('tematres-wp-integration', false, $path);
    if ($_GET['page'] == basename(__FILE__) && !$loaded) {
        echo '<div class="error">Sample Localization: ' . __('Could not load the localization file: ' . esc_attr($path), 'tematres-wp-integration') . '</div>';
        return;
    }
}

/**
 * Function to render the created tags on the templates
 */
function tmwpi_get_the_tag_list($before = '', $sep = '', $after = '', $post_id = 0)
{
    $tag_list = get_the_term_list($post_id, 'tematres_wp', $before, $sep, $after);

    /**
     * Filters the tags list for a given post.
     *
     * @since 2.3.0
     *
     * @param string $tag_list List of tags.
     * @param string $before   String to use before the tags.
     * @param string $sep      String to use between the tags.
     * @param string $after    String to use after the tags.
     * @param int    $post_id  Post ID.
     */
    return apply_filters('the_tags', $tag_list, $before, $sep, $after, $post_id);
}


function tmwpi_has_tag($tag = '', $post = null)
{
    return has_term($tag, 'tematres_wp', $post);
}
