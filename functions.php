<?php

/** 
 * Plugin Name: Tematres WP
 * Plugin URI: https://github.com/becahp
 * Description: WordPress and Tematres Integration
 * Version: 1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author: Rebeca Moura e Lucas Rodrigues
 * Author URI: https://github.com/becahp
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
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
function tematres_wp_style_scripts()
{
    $ver = time();

    wp_register_style('css_tematres_wp', TEMATRES_WP_CSS_URL . 'tematres-wp.css', false, $ver);
    wp_enqueue_style('css_tematres_wp');

    wp_register_style('css_select2', TEMATRES_WP_CSS_URL . 'select2.min.css', false, $ver);
    wp_enqueue_style('css_select2');

    wp_enqueue_script('js_tematres_wp', TEMATRES_WP_JS_URL . 'tematres-wp.js', array('jquery', 'js_select2'), $ver);
    wp_localize_script('js_tematres_wp', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    wp_enqueue_script('js_select2', TEMATRES_WP_JS_URL . 'select2.min.js', array(), $ver);
}
add_action('wp_enqueue_scripts', 'tematres_wp_style_scripts');

/**
 * Register scripts used on admin pages
 */
function tematres_wp_style_scripts_admin()
{
    $ver = time();

    wp_register_style('css_tematres_wp', TEMATRES_WP_CSS_URL . 'tematres-wp.css', false, $ver);
    wp_enqueue_style('css_tematres_wp');

    wp_enqueue_script('js_tematres_wp', TEMATRES_WP_JS_URL . 'tematres-wp.js', array(), $ver);
    wp_localize_script('js_tematres_wp', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    //wp_enqueue_script('js_select2', TEMATRES_WP_JS_URL . 'select2.min.js', array(), $ver);
}
add_action('admin_enqueue_scripts', 'tematres_wp_style_scripts_admin');

/**
 * Adds the new menu to the painel
 */
function tematres_wp_admin_menu()
{
    add_menu_page(
        __('Tematres WP', 'tematres-wp-integration'),
        __('Tematres WP', 'tematres-wp-integration'),
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
            $message       = __('There was an error adding this setting. Please try again. If this persists, shoot us an email.', 'tematres-wp-integration');
            $err_code      = esc_attr('pagina_config_tematres_url');
            $setting_field = 'pagina_config_tematres_url';
            break;
    }

    $type = 'error';
    add_settings_error($setting_field, $err_code, $message, $type);
}


/**
 * Function registerAndBuildFields called by registrar_construir_campos
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
        __('Tematres URL','tematres-wp-integration'),
        // Callback used to render the description of the section
        'pagina_config_mensagem_geral_url',
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
        'placeholder' => 'Insira uma URL',
        'size' => 70,
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
    );

    add_settings_field(
        'pagina_config_tematres_url',
        'Tematres URL:',
        'pagina_config_renderizar_campos',
        'plugin-options',
        'pagina_config_secao',
        $args
    );
    
    register_setting('settings_all', 'pagina_config_tematres_url');

    #Adicionando nome da Tag
    //unset( $args );
    $args = array(
        'type' => 'input',
        'subtype' => 'text',
        'id' => 'tematres_tag_name',
        'name' => 'tematres_tag_name',
        'required' => 'true',
        'placeholder' => 'Insira o nome da Tag',
        'size' => 70,
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
    );
    
    add_settings_section(
        "section-tematres-tags-name",
        __("Tag Name",'tematres-wp-integration'),
        "pagina_config_mensagem_geral_name",
        "plugin-options");
    
    add_settings_field(
        'tematres_tag_name',
        __("Insert Tag Name:",'tematres-wp-integration'),
        "pagina_config_renderizar_campos",
        "plugin-options",
        "section-tematres-tags-name",
        $args);  

    register_setting("settings_all", 'tematres_tag_name');  

    #adicionando checkbox
    #https://wordpress.stackexchange.com/questions/328648/saving-multiple-checkboxes-with-wordpress-settings-api
    #https://wordpress.stackexchange.com/questions/110503/how-to-use-checkbox-in-custom-option-page-using-the-setting-api
    #http://qnimate.com/add-checkbox-using-wordpress-settings-api/

    add_settings_section(
        "section-posts-types",
        __("Post types where Tematres Tags will be applied",'tematres-wp-integration'),
        'pagina_config_mensagem_geral_posts',
        "plugin-options");

    add_settings_field(
        'post_types',
        __("Select Post types:",'tematres-wp-integration'),
        "post_types_checkbox_field_1_render",
        "plugin-options",
        "section-posts-types");

    register_setting("settings_all", 'post_types');   
}

function post_types_checkbox_field_1_render() {

    $options = get_option( 'post_types', [] );

    $post_types_checkbox_field_1 = isset( $options['post_types_checkbox_field_1'] )
        ? (array) $options['post_types_checkbox_field_1'] 
        : [];
    
    // Função que retorna todos os custom post types
    $args = array(
        'public'   => true,
        '_builtin' => false,
    );
    $output = 'objects'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types($args, $output, $operator);

    // Adiciona o post comum também no inicio
    array_unshift($post_types, get_post_types( [], 'objects' )["post"]);
    foreach ($post_types as $post) {
        $slug = $post->name;
        $name = $post->label;
    
    ?>
    <input type='checkbox' id='<?php echo $slug?>' name='post_types[post_types_checkbox_field_1][]' <?php checked( in_array( $slug, $post_types_checkbox_field_1 ), 1 ); ?> value='<?php echo $slug?>'>
        <label for="<?php echo $slug?>"><?php echo $name?></label>
        <br>
    <?php
    }
}

function pagina_config_mensagem_geral_url($args)
{
    echo '<p>';
    echo __('This setting applies to all features of the Tematres WP plugin.','tematres-wp-integration');
    echo '</p>';

    echo '<p><strong>';
    echo __('URL example:','tematres-wp-integration');
    echo '</strong>';
    echo 'http://mystematres/vocab/services.php';
    echo '</p>';
    
    echo '<p><strong>';
    echo __('Currently, the saved URL is: ','tematres-wp-integration');
    echo '</strong>';
    echo get_option('pagina_config_tematres_url');
    echo '</p>';

    // <p>Essa configuração se aplica a todas as funcionalidades do plugin Tematres WP</p>
    // <p><strong>Exemplo de url:</strong> http://mystematres/vocab/services.php</p>
    // <p><strong>A URL salva no momento é:</strong> echo get_option('pagina_config_tematres_url'); </p>    
}

function pagina_config_mensagem_geral_name($args)
{
    echo '<p>';
    echo __("Define the Tag Name (this doesn't affect the term slug)",'tematres-wp-integration');
    echo '</p>';
}

function pagina_config_mensagem_geral_posts($args)
{
    echo '<p>';
    echo __("Choose the post types where the Tematres Tags will be applied",'tematres-wp-integration');
    echo '</p>';
}

function pagina_config_renderizar_campos($args)
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
                    echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
                } else {

                    // O CAMPO NORMAL É RENDERIZADO AQUI
                    // The common input is rendered here

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

//--------------------------------------------------------------------- API do tematres

/**
 * DELETAR
 */
add_shortcode('shortcode_show_tags_tematres', 'show_tags_tematres');
#Ex: [shortcode_show_tags_tematres] 
function show_tags_tematres()
{
?>
    <form action="<?php echo get_permalink() ?>" method="post">
        <div>
            <label for="field-name"> Informe o Termo:</label>
            <?php
            $termo = $_POST['termo'];
            if (empty($termo)) {
                echo "<input type=\"text\" name=\"termo\" id=\"termo\" minlength=\"2\" placeholder=\"laranja\" required />";
            } else {
                echo "<input type=\"text\" name=\"termo\" id=\"termo\" minlength=\"2\" value=\"$termo\" placeholder=\"laranja\" required />";
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
        $termo = $_POST['termo'];
        if (empty($termo)) {
            echo "Termo vazio";
        } else {
            $urlTematres = get_option('pagina_config_tematres_url');
            echo "<p>Query de busca é \"$termo\"</p>";
            $urlBusca = $urlTematres . "?task=search&arg=" . strtolower($termo);
            echo "<p>URL de busca é: $urlBusca</p>";

            $xml = simplexml_load_file($urlBusca)->result;
            #var_dump($xml);
            //carrega o arquivo XML e retornando um Array
            $termos = array();
            foreach ($xml->term as $item) {
                array_push($termos, $item->string);
            }

            for ($i = 0; $i < count($termos); ++$i) {
                echo "<strong>Termo $i:</strong> " . $termos[$i] . "<br/>";
            }
        }
    }
}

//--------------------------------------------------------------------- Editando as Tags

// Remove Categories and Tags
// add_action('init', 'myprefix_remove_tax');
// function myprefix_remove_tax()
// {
//     //register_taxonomy('category', array());
//     //register_taxonomy('post_tag', array());
// }

//--------------------------------------------------------------------- // Remover Categories and Tags (FORMA CORRETA)
function wpdocs_register_private_taxonomy() {
    $args = array(
        'label'        => __( 'Tags', 'tematres-wp-integration' ),
        'public'       => false,
        'rewrite'      => false,
        'hierarchical' => true
    );
    
    // escondendo a tag padrão do WP caso o Post seja selecionado no plugin
    $post_type = get_option('post_types')["post_types_checkbox_field_1"];
    
    $flag = False;
    foreach ($post_type as $post){
        if ($post == "post") $flag = True;
    }
    if ($flag) register_taxonomy( 'post_tag', 'post', $args );

    $custom_name = get_option("tematres_tag_name");
    $labels = array(
        'name'                       => _x( get_option("tematres_tag_name"), 'taxonomy general name', 'tematres-wp-integration' ),
        'singular_name'              => _x( get_option("tematres_tag_name"), 'taxonomy singular name', 'tematres-wp-integration' ),
        'search_items'               => sprintf( esc_attr__( 'Search %s', 'tematres-wp-integration' ), $custom_name),
        'popular_items'              => sprintf( esc_attr__( 'Popular %s', 'tematres-wp-integration' ), $custom_name),
        'all_items'                  => sprintf( esc_attr__( 'All %s', 'tematres-wp-integration' ), $custom_name),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => sprintf( esc_attr__( 'Edit %s', 'tematres-wp-integration' ), $custom_name),
        'update_item'                => sprintf( esc_attr__( 'Update %s', 'tematres-wp-integration' ), $custom_name),
        'add_new_item'               => sprintf( esc_attr__( 'Add New %s', 'tematres-wp-integration' ), $custom_name),
        'new_item_name'              => sprintf( esc_attr__( 'New %s Name', 'tematres-wp-integration' ), $custom_name),
        'separate_items_with_commas' => sprintf( esc_attr__( 'Separate %s with commas', 'tematres-wp-integration' ), $custom_name),
        'add_or_remove_items'        => sprintf( esc_attr__( 'Add or remove %s', 'tematres-wp-integration' ), $custom_name),
        'choose_from_most_used'      => sprintf( esc_attr__( 'Choose from the most used %s', 'tematres-wp-integration' ), $custom_name),
        'not_found'                  => sprintf( esc_attr__( 'No %s found', 'tematres-wp-integration' ), $custom_name),
        'all_items'                  => sprintf( esc_attr__( 'All %s', 'tematres-wp-integration' ), $custom_name),
        'menu_name'                  => sprintf( esc_attr__( '%s', 'tematres-wp-integration' ), $custom_name),
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
        'rewrite'               => array( 'slug' => 'tematres_wp' ),
    );
 
    register_taxonomy( 'tematres_wp', $post_type, $args );

}
add_action( 'init', 'wpdocs_register_private_taxonomy', 0 );


//REMOVE MENUS
function my_remove_sub_menus() {
    //remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    //remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=tematres_wp');
}
add_action('admin_menu', 'my_remove_sub_menus');

//--------------------------------------------------------------------- criando a metabox
/*
 * Add
 */
function rudr_add_new_tags_metabox()
{
    $id = 'tematres-wp-integration_tag'; // it should be unique
    $heading = 'Tematres Tags'; // meta box heading ---> alterar nome?
    $callback = 'rudr_metabox_content'; // the name of the callback function

    // $args = array(
    //     'public'   => true,
    //     '_builtin' => false,
    // );

    // $output = 'names'; // names or objects, note names is the default
    // $operator = 'and'; // 'and' or 'or'

    // // Função que retorna todos os custom post types
    // $post_type = get_post_types($args, $output, $operator);

    // // Adiciona o post comum também
    // array_push($post_type, 'post');

    $post_type = get_option('post_types')["post_types_checkbox_field_1"]; 

    $position = 'side';
    $pri = 'low'; // priority, 'default' is good for us
    add_meta_box($id, $heading, $callback, $post_type, $position, $pri);
}
add_action('admin_menu', 'rudr_add_new_tags_metabox');

/*
 * Fill
 */
function rudr_metabox_content($post)
{
    $term_obj_list = get_the_terms( $post->ID, 'tematres_wp' );
    $optionArray =  wp_list_pluck($term_obj_list, 'name');

    echo '<div id="taxonomy-post_tag" class="categorydiv">';
    echo '<select id="escolha_tags" class="tematres-wp-integration-escolhas" name="escolha_tags[]" multiple="multiple">';

    if (empty($optionArray)) {
        echo '<option value="">Selecione as Tags</option>';
    } else {

        for ($i = 0; $i < count($optionArray); $i++) {
            //echo $optionArray[$i]."<br>";
            echo '<option value="'. $optionArray[$i] .'" selected="selected">'.$optionArray[$i].'</option>';
        }
    }

    echo '</select>';
    echo '</div>';
}

add_shortcode('shortcode_teste_select', 'teste_select');
#Ex: [shortcode_teste_select] 
function teste_select()
{
    echo '<div id="taxonomy-post_tag" class="categorydiv" style="height: 75px;">';
    echo '<p>';
    echo '<select id="escolha_tags" class="tematres-wp-integration-escolhas" name="escolha_tags[]" multiple="multiple">';
    echo '<option value="">Selecione as Tags</option>';
    echo '</select>';
    echo '</p>';
    echo '</div>';
}

/*
* Função que salva a tag inserida no select2 como tag do WP Core se ela ainda não existir
*
* Função chamada pelo Javascript JS
*/
function ajax_criar_tags(){

    $tag_escolhida = ( isset( $_POST['tag'] ) ) ? $_POST['tag'] : '';

    if( empty( $tag_escolhida ) )
        return;
    
    // Checa se o termo existe
    $term = term_exists( $tag_escolhida, 'tematres_wp' );
    
    // Caso exista, apenas avisa no console
    if ( $term !== 0 && $term !== null ) {
        //echo __( $tag_escolhida . " post_tag exists!", "tematres-wp-integration" );
        //echo ($tag_escolhida. ' já existe');
        echo __($tag_escolhida. ' already exists.', 'tematres-wp-integration');
    } 

    // Caso contrário, cria o termo
    if ($term == 0 || $term === null){
        wp_insert_term(
            $tag_escolhida,   // the term 
            'tematres_wp', // the taxonomy
            array()
        );

        //echo ($tag_escolhida. ' inserida no Tags (Tematres-WP)');
        echo __($tag_escolhida. ' saved as Tematres Tags', 'tematres-wp-integration');
    }
    
    wp_die();
}
add_action( 'wp_ajax_nopriv_criar_tags', 'ajax_criar_tags' );
add_action( 'wp_ajax_criar_tags', 'ajax_criar_tags' );

add_action( 'save_post', 'set_post_default_category', 10, 3 ); 
function set_post_default_category( $post_id, $post, $update ) {

    if (isset($_POST["escolha_tags"])) {
        $optionArray = $_POST["escolha_tags"];

        // wp_set_post_terms can receive an array of strings separated by commas
        // the false at the end replace all existing post terms for the specific tag (in this case 'tematres_wp' )
        wp_set_post_terms( $post_id, $optionArray, 'tematres_wp', false );
        
    }
}