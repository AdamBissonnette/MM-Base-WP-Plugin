<?php
/*
Plugin Name: @plugin_name@
Plugin URI: http://mediamanifesto.com/
Description: @description@
Version: @version@
Author: Adam Bissonnette
Author URI: http://www.mediamanifesto.com/
*/

include_once('lib/functions.php');

class Mmm_Curl_Manager
{
    public static $_options_pagename = 'Mmm_Curl_Manager';
    public static $_settings_key = 'Mmm_Curl_Manager';
    public static $_meta_key = 'Mmm_Curl_Manager_meta';
    public static $_versionnum = "@core_version@";

    var $_settings;
    var $_save_key = '';
    var $location_folder;
    var $menu_page;
    
    function __construct()
    {
        //Building a better tomorrow
        $this->_settings = get_option(Mmm_Curl_Manager::$_settings_key) ? get_option(Mmm_Curl_Manager::$_settings_key) : array();
        $this->location_folder = trailingslashit(WP_PLUGIN_URL) . dirname( plugin_basename(__FILE__) );

        add_action( 'admin_menu', array(&$this, 'create_menu_link') );
        
        //Ajax Posts
        add_action('wp_ajax_nopriv_do_ajax', array(&$this, '_save') );
        add_action('wp_ajax_do_ajax', array(&$this, '_save') );

        //Conditionally enable admin-bar menu
        //if settings set - enable admin-bar
        //Custom CSS for taxonomy icons
        add_action('admin_head', array(&$this, 'custom_dashboard_css'));
    }

    static function Mmm_Curl_Manager_install() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        //Get default values from the theme data file if there are none
        //Mmm_Curl_Manager::_set_standart_values($themeSettings);

        add_option(Mmm_Curl_Manager::$_settings_key . "_versionnum", Mmm_Curl_Manager::$_versionnum);
    }

    function custom_dashboard_css()
    {
        MmmToolsNamespace\load_font_awesome();
        wp_enqueue_style('dashboard', $this->location_folder . '/assets/css/dashboard.css', false, null);
    }

    function create_menu_link()
    {
        $this->menu_page = add_submenu_page( "edit.php?post_type=mm-class", Mmm_Curl_Manager::$_options_pagename . 'Options', 'Classes / Reporting',
            'manage_options', Mmm_Curl_Manager::$_options_pagename . "_Classes", array(&$this, 'build_settings_page') );

        $this->menu_page = add_submenu_page( "edit.php?post_type=mm-class", Mmm_Curl_Manager::$_options_pagename . 'Options', 'Settings',
            'manage_options', Mmm_Curl_Manager::$_options_pagename . "_Admin", array(&$this, 'build_settings_page') );
    }
    
    function build_settings_page()
    {
        if (!$this->check_user_capability()) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        
        if (!has_action( 'wp_default_styles', 'bootstrap_admin_wp_default_styles' ))
        {
            wp_enqueue_style('bootstrap_css', plugins_url('/assets/css/bootstrap.css', __FILE__), false, null);
            wp_enqueue_script('jquery', plugins_url('/assets/js/jquery-1.9.1.min.js', __FILE__), false, null);        
            wp_enqueue_script('bootstrap_js', plugins_url('/assets/js/plugins.js', __FILE__), false, null);
        }
        
        wp_enqueue_style('admin_css', plugins_url('/assets/css/admin.css', __FILE__), false, null);
        wp_enqueue_script('formtools_js', plugins_url('/assets/js/formtools.js', __FILE__), false, null);
        wp_enqueue_script('admin_js', plugins_url('/assets/js/admin.js', __FILE__), false, null);

        MmmToolsNamespace\load_admin_assets();
        
        include_once('lib/data/plugin_data.php');
        include_once('lib/data/admin_data.php');

        include_once('lib/ui/admin_ui.php');
    }

    function check_user_capability()
    {
        if ( is_super_admin() || current_user_can('manage_options') ) return true;

        return false;
    }
    
    function _save()
    {
        if ($this->check_user_capability()) //if isAdmin
        {
            MmmPluginToolsNamespace::admin_ajax();
        }

        MmmPluginToolsNamespace::non_admin_ajax();
    }

    function get_option($setting)
    {
        return $this->_settings[$setting];
    }
    
    function _save_settings_todb($form_settings = '')
    {
        if ( $form_settings <> '' ) {
            unset($form_settings[Mmm_Curl_Manager::$_settings_key . '_saved']);

            $this->_settings = $form_settings;

            #set standart values in case we have empty fields
            $this->_set_standart_values($form_settings);
        }
        
        update_option(Mmm_Curl_Manager::$_settings_key, $this->_settings);
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
}

//if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
register_activation_hook(__FILE__,array('Mmm_Curl_Manager', 'Mmm_Curl_Manager_install'));

add_action( 'init', 'Mmm_Curl_Manager_Init', 5 );
function Mmm_Curl_Manager_Init()
{
    global $MMM_Curl_Manager;
    global $MMM_Data_Library;

    if ($MMM_Data_Library == null)
    {
        $MMM_Data_Library = array();
    }

    $MMM_Curl_Manager = new Mmm_Curl_Manager();
    $MMM_Data_Library[] = $MMM_Curl_Manager;
}


?>