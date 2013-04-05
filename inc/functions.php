<?php
	/* References */
	include_once('url-tools.php');
	include_once('data-tools.php');
	include_once('date-tools.php');
	include_once('html-tools.php');
	include_once('email-tools.php');
	include_once('wp-tools.php');

	/* Plugin specific code 
	function add_specials_admin_bar_link() {
		global $wp_admin_bar;
		if ( !is_super_admin() || !is_admin_bar_showing() )
			return;
		$wp_admin_bar->add_menu( array(
		'id' => 'mm_link',
		'title' => __( 'Update Events'),
		'href' => __(site_url() . '/wp-admin/options-general.php?page=mm_options'),
		)
		);
		 $wp_admin_bar->add_menu( array(
		'id' => 'mm_blog_post',
		'title' => __( 'Add Blog Post'),
		'href' => __(site_url() . '/wp-admin/post-new.php?type=17'),
		)
		);
		
	}
	
	function set_category() {
		if ($_GET['type'])
		{
			echo '<script type="text/javascript">setTimeout(\'jQuery("#in-category-' . $_GET['type'] . '").click();\', 1000);</script>';
		}
	}*/
	
	//add_action('admin_bar_menu', 'add_specials_admin_bar_link',25);
	//add_action( 'admin_print_scripts-post-new.php', 'set_category' );
?>