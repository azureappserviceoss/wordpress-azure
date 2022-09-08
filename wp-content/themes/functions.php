<?php


    function thumbnails(){
        add_theme_support('post-thumbnails');
    }
        add_action('init','thumbnails');

    function add_theme_scripts() {
        wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
        wp_enqueue_style( 'style', get_template_directory_uri() . './style.css' ); 
    }
        add_action('wp_enqueue_scripts', 'add_theme_scripts');

      
        add_filter('body_class','background_images');
        function background_images($classes) {
        
            $background_class = 'background_img';
        
            $classes[] = $background_class;
        
            return $classes;
        }
     

?>
