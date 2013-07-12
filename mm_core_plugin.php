<?php
/*
Plugin Name: MM Core
Plugin URI: http://mediamanifesto.com
Description: Base plugin code for any Media Manifesto plugins (all js, css, php, for easy install of addons)
Version: 0.1
Author: Adam Bissonnette
Author URI: http://www.mediamanifesto.com/
*/

<?php
/**
 * Core Theme Object
 */

include_once('inc/functions.php');

class MM_Core
{
	var $_settings;
    var $_options_pagename = 'MM_Core';
    var $_settings_key = 'MM_Core';
    var $_meta_key = 'MM_Core_meta';
    //var $_setting_prefix = 'MM_Core_';
    var $_save_key = '';
    var $_versionnum = 1.0;
	var $menu_page;
	
	function MM_Core()
	{
		return $this->__construct();
	}
	
    function __construct()
    {
        $this->_settings = get_option($this->_settings_key) ? get_option($this->_settings_key) : array();

        add_action( 'admin_menu', array(&$this, 'create_menu_link') );
		
		//Ajax Posts
		add_action('wp_ajax_nopriv_do_ajax', array(&$this, '_save') );
		add_action('wp_ajax_do_ajax', array(&$this, '_save') );

		//Custom Taxonomies
		add_action( 'init', array(&$this, 'custom_taxonomies'));

		//Page / Post Meta
		add_post_type_support( 'page', 'excerpt' ); //Pages should have this - it's silly not to!

		//add_action("admin_init", array(&$this, "page_metabox") );
		//add_action("admin_init", array(&$this, "post_metabox") );
		
		//Custom Meta
		add_action( 'admin_init', array(&$this, 'custom_metabox'));

		add_action( 'save_post', array(&$this, '_save_post_meta'), 10, 2 );
    }

    static function MM_Core_install() {
    	global $wpdb;
    	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    	
    	//Get default values from the theme data file if there are none
		//$this->_set_standart_values($themeSettings);
		
		add_option($_settings_key . "_versionnum", $_versionnum);
	}

	function custom_metabox(){
		global $taxonomies;

		foreach ($taxonomies as $taxonomy)
		{
			add_meta_box("mm_post_meta", "Meta", array(&$this, "taxonomy_meta"), $taxonomy["slug"], "normal", "low", $taxonomy["options"]);
		}	
	}

	function custom_taxonomies()
	{
		global $taxonomies;

		foreach ($taxonomies as $taxonomy) 
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

		$values = get_post_meta($post->ID, $this->_meta_key, true);
		include_once('ui/meta_post_ui.php');
	}

	function create_menu_link()
    {
        $this->menu_page = add_options_page('Theme Options', 'Theme Options',
        'manage_options',$this->_options_pagename, array(&$this, 'build_settings_page'));
    }
    
    function build_settings_page()
    {
        if (!$this->check_user_capability()) {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        
        wp_enqueue_style('bootstrap', $this->location_folder .  '/css/bootstrap.css', false, null);
        wp_enqueue_script('jquery', $this->location_folder .  '/js/jquery-1.9.1.min.js', false, null);
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/plugins.js', false, null);
        wp_enqueue_style('admin', $this->location_folder .  '/css/MM_Core_admin.css', false, null);
  		wp_enqueue_script('formtools', $this->location_folder .  '/js/formtools.js', false, null);
  		wp_enqueue_script('admin', $this->location_folder .  '/js/MM_Core_admin.js', false, null);
        
		include_once('ui/admin_ui.php');
    }

    function check_user_capability()
    {
        if ( is_super_admin() || current_user_can('manage_options') ) return true;

        return false;
    }
    
    
    function _save()
	{
		$isAdmin = $this->check_user_capability();

		do_callback($isAdmin);
	}
    
	function _save_post_meta( $post_id, $post ){
		global $pagenow;
		global $taxonomies;

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

			update_post_meta( $post_id, $this->_meta_key, $metadata );
	    }
	}

    function get_option($setting)
    {
        return $this->_settings[$setting];
    }
    
    function _save_settings_todb($form_settings = '')
	{
		if ( $form_settings <> '' ) {
			unset($form_settings[$this->_settings_key . '_saved']);

			$this->_settings = $form_settings;

			#set standart values in case we have empty fields
			$this->_set_standart_values($form_settings);
		}
		
		update_option($this->_settings_key, $this->_settings);
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
	
	function get_setting($name)
	{
		$output = "";

		if (isset($this->_settings[$name]))
		{
			$output = stripslashes($this->_settings[$name]);
		}

		return $output;
	}

	/*****
	*	get_post_meta($id, $key)
	*	$id - the post to get the theme meta from
	*	$key (optional) - the optional key if this is the only value you want or need
	*	$single (optional) - if the key is a single value or an array
	*	$ouput - returns either the single value key or the whole meta array
	*/
	function get_post_meta($id, $key=null, $single = true)
	{
		$output = "";
		$post_meta = get_post_meta($id, $this->_meta_key, $single);

		if ($key != null && isset($post_meta[$key]))
		{
			$output = $post_meta[$key];
		}
		
		return $output;
	}

}

register_activation_hook(__FILE__,array('MM_Core', 'MM_Core_install'));

add_action( 'init', 'MM_Core_Init', 5 );
function MM_Core_Init()
{
    global $MM_Core;
    $MM_Core = new MM_Core();
}
?>