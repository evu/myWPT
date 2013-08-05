<?php
	
	// Add RSS links to <head> section
	add_theme_support( 'automatic-feed-links' );
	
	// Load jQuery
	function load_jq() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"), '', '', true);
        wp_enqueue_script('jquery');
    }    
    add_action('wp_enqueue_scripts', 'load_jq');

	// Clean up the <head>
	function tidy_head() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
        global $wp_widget_factory;  
        remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );  
    }
    add_action('init', 'tidy_head');
    remove_action('wp_head', 'wp_generator');
    
    // Add widgetizable Sidebar
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }

    // Add main nav menu
    function register_main_nav_menu() {
        register_nav_menu('primary', 'Main Navigation Menu');
    }
    add_action( 'init', 'register_main_nav_menu' );


    // Add Editor Styles
    add_editor_style('/admin/admin-style.css');

    // Custom Excerpt
    function custom_Excerpt($more) {
        global $post;
        return '... <a href="'. get_permalink($post->ID) . '" class="more-link">read more →</a>';
    }
    add_filter('excerpt_more', 'custom_Excerpt');

    // Lightbox
    function auto_lightbox($content) {
           global $post;
           $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
           $replacement = '<a$1href=$2$3.$4$5 rel="lightbox" title="'.$post->post_title.'"$6>';
           $content = preg_replace($pattern, $replacement, $content);
           return $content;
    }
    add_filter('the_content', 'auto_lightbox');


?>