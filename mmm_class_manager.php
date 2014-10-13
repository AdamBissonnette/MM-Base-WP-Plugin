<?php
/*
Plugin Name: @plugin_name@
Plugin URI: http://mediamanifesto.com/
Description: Base plugin code for any Media Manifesto plugins (all js, css, php, for easy install of addons)
Version: @version@
Author: Adam Bissonnette
Author URI: http://www.mediamanifesto.com/
*/

include_once('lib/functions.php');

class Mmm_Class_Manager
{
    public static $_options_pagename = 'Mmm_Class_Manager';
    public static $_settings_key = 'Mmm_Class_Manager';
    public static $_meta_key = 'Mmm_Class_Manager_meta';
    public static $_versionnum = "@core_version@";

    var $_settings;
    var $_save_key = '';
    var $location_folder;
    var $menu_page;
    
    function Mmm_Class_Manager()
    {
        $this->_settings = get_option(Mmm_Class_Manager::$_settings_key) ? get_option(Mmm_Class_Manager::$_settings_key) : array();
        $this->location_folder = trailingslashit(WP_PLUGIN_URL) . dirname( plugin_basename(__FILE__) );

        add_action( 'admin_menu', array(&$this, 'create_menu_link') );
        
        //Ajax Posts
        add_action('wp_ajax_nopriv_do_ajax', array(&$this, '_save') );
        add_action('wp_ajax_do_ajax', array(&$this, '_save') );

        //Custom Taxonomies
        add_action( 'init', array(&$this, 'custom_taxonomies'));
        
        //Custom Meta
        add_action( 'admin_init', array(&$this, 'custom_metabox'));
        add_action( 'save_post', array(&$this, '_save_post_meta'), 10, 2 );

        //Conditionally enable admin-bar menu
        //if settings set - enable admin-bar
        //Custom CSS for taxonomy icons
        add_action('admin_head', array(&$this, 'custom_dashboard_css'));
    }

    static function Mmm_Class_Manager_install() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        //Get default values from the theme data file if there are none
        //Mmm_Class_Manager::_set_standart_values($themeSettings);
        
        MmmPluginToolsNamespace\create_tables(Mmm_Class_Manager::$_versionnum);

        add_option(Mmm_Class_Manager::$_settings_key . "_versionnum", Mmm_Class_Manager::$_versionnum);
    }

    function custom_dashboard_css()
    {
        MmmToolsNamespace\load_font_awesome();
        wp_enqueue_style('dashboard', $this->location_folder . '/assets/css/dashboard.css', false, null);
    }

    function custom_metabox(){
        global $mmm_class_taxonomies;

        foreach ($mmm_class_taxonomies as $taxonomy)
        {
            add_meta_box("mmm_post_meta", "Meta", array(&$this, "taxonomy_meta"), $taxonomy["slug"], "normal", "low", $taxonomy["options"]);
        }   
    }

    function custom_taxonomies()
    {
        global $mmm_class_taxonomies;

        foreach ($mmm_class_taxonomies as $taxonomy) 
        {
            if (isset($taxonomy["registration-args"]))
            {
                register_post_type( $taxonomy["slug"], $taxonomy["registration-args"] );
            }
        }
    }

    function taxonomy_meta($post, $data)
    {
        $options = $data["args"];

        $values = get_post_meta($post->ID, Mmm_Class_Manager::$_meta_key, true);

        //Enqueue styles / scripts
        MmmToolsNamespace\load_admin_assets();

        wp_enqueue_style('mm-admin', $this->location_folder . '/assets/css/admin.css', false, null);

        include_once('lib/ui/meta_post_ui.php');
    }

    function create_menu_link()
    {
        $this->menu_page = add_submenu_page( "edit.php?post_type=mm-product", Mmm_Class_Manager::$_options_pagename . 'Options', 'Settings',
            'manage_options', Mmm_Class_Manager::$_options_pagename, array(&$this, 'build_settings_page') );
    }
    
    function build_settings_page()
    {
        if (!$this->check_user_capability()) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        
        if (!has_action( 'wp_default_styles', 'bootstrap_admin_wp_default_styles' ))
        {
            wp_enqueue_style('bootstrap', plugins_url('/assets/css/bootstrap.css', __FILE__), false, null);
            wp_enqueue_script('jquery', plugins_url('/assets/js/jquery-1.9.1.min.js', __FILE__), false, null);        
            wp_enqueue_script('bootstrap', plugins_url('/assets/js/plugins.js', __FILE__), false, null);
        }
        
        wp_enqueue_script('adminjs', plugins_url('/assets/js/formtools.js', __FILE__), false, null);

        MmmToolsNamespace\load_admin_assets();
        
        include_once('lib/ui/admin_ui.php');
    }

    function check_user_capability()
    {
        if ( is_super_admin() || current_user_can('manage_options') ) return true;

        return false;
    }
    
    
    function _save()
    {
        $isAdmin = $this->check_user_capability();
        $this->do_callback($isAdmin);
    }
    
    function do_callback($isAdmin)
    {
        if ($isAdmin)
        {
            $this->do_admin_function();
        }

        $this->do_standard_function();
    }

    function do_admin_function()
    {
        MmmPluginToolsNamespace::admin_ajax();
    }


    function do_standard_function()
    {
        MmmPluginToolsNamespace::non_admin_ajax();
    }

    function _save_post_meta( $post_id, $post ){
        global $pagenow;
        global $mmm_class_taxonomies;

        if ( 'post.php' != $pagenow ) return $post_id;
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
            return $post_id;

        if ( ! isset( $_POST['mm_nonce'] ) || ! wp_verify_nonce( $_POST['mm_nonce'], 'mm_nonce' ) )
            return $post_id;

        $metafields = array();

        $taxonomySlugs = array();

        foreach ($taxonomies as $taxonomy) {
            $taxonomySlugs[] = $taxonomy["slug"];
        }

        if (in_array($post->post_type, $taxonomySlugs))
        {
            $taxonomyKey = array_search($post->post_type, $taxonomySlugs);
            $metafields = GetThemeDataFields($taxonomies[$taxonomyKey]["options"]);

            $metadata = array();

            foreach ($metafields as $field) {
                $fieldID = $field["id"];
                $metadata[$fieldID] = $_POST[$fieldID];
            }

            update_post_meta( $post_id, Mmm_Class_Manager::$_meta_key, $metadata );
        }
    }

    function get_option($setting)
    {
        return $this->_settings[$setting];
    }
    
    function _save_settings_todb($form_settings = '')
    {
        if ( $form_settings <> '' ) {
            unset($form_settings[Mmm_Class_Manager::$_settings_key . '_saved']);

            $this->_settings = $form_settings;

            #set standart values in case we have empty fields
            $this->_set_standart_values($form_settings);
        }
        
        update_option(Mmm_Class_Manager::$_settings_key, $this->_settings);
    }

    function _set_standart_values($standart_values)
    {
        global $shortname;

        foreach ($standart_values as $key => $value){
            if ( !array_key_exists( $key, $this->_settings ) )
                $this->_settings[$key] = '';
        }

        foreach ($this->_settings as $key => $value) {
            if ( $value == '' ) $this->_settings[$key] = $standart_values[$key];
        }
    }
    
    function get_setting($name=null, $defaultValue="")
    {
        $output = $defaultValue;

        if (isset($this->_settings[$name]))
        {
            $output = stripslashes($this->_settings[$name]);
        }

        return $output;
    }

    /*****
    *   get_post_meta($id, $key)
    *   $id - the post to get the theme meta from
    *   $key (optional) - the optional key if this is the only value you want or need
    *   $single (optional) - if the key is a single value or an array
    *   $ouput - returns either the single value key or the whole meta array
    */
    function get_post_meta($id, $key=null, $single = true)
    {
        $output = "";
        $post_meta = get_post_meta($id, Mmm_Class_Manager::$_meta_key, $single);

        if ($key != null && isset($post_meta[$key]))
        {
            $output = $post_meta[$key];
        }
        
        return $output;
    }
}

register_activation_hook(__FILE__,array('Mmm_Class_Manager', 'Mmm_Class_Manager_install'));

add_action( 'init', 'Mmm_Class_Manager_Init', 5 );
function Mmm_Class_Manager_Init()
{
    global $Mmm_Class_Manager;
    $Mmm_Class_Manager = new Mmm_Class_Manager();
}
?>