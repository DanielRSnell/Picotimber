<?php
/*
_               _                  _____        _     _ _     _   _   _
(_)             | |                | ____|      | |   (_) |   | | | | | |
_ __  _  ___ ___  ___| |_ _ __ __ _ _ __| |__     ___| |__  _| | __| | | |_| |__   ___ _ __ ___   ___
| '_ \| |/ __/ _ \/ __| __| '__/ _` | '_ \___ \   / __| '_ \| | |/ _` | | __| '_ \ / _ \ '_ ` _ \ / _ \
| |_) | | (_| (_) \__ \ |_| | | (_| | |_) |__) | | (__| | | | | | (_| | | |_| | | |  __/ | | | | |  __/
| .__/|_|\___\___/|___/\__|_|  \__,_| .__/____/   \___|_| |_|_|_|\__,_|  \__|_| |_|\___|_| |_| |_|\___|
| |                                 | |
|_|                                 |_|

 *************************************** WELCOME TO PICOSTRAP ***************************************

 ********************* THE BEST WAY TO EXPERIENCE SASS, BOOTSTRAP AND WORDPRESS *********************

PLEASE WATCH THE VIDEOS FOR BEST RESULTS:
https://www.youtube.com/playlist?list=PLtyHhWhkgYU8i11wu-5KJDBfA9C-D4Bfl

 */

// Vendor
require_once __DIR__ . '/vendor/autoload.php';

Timber\Timber::init();
Timber::$dirname = ['views'];
Timber::$autoescape = false;

require get_stylesheet_directory() . '/inc/timber/controller.php';

// DE-ENQUEUE PARENT THEME BOOTSTRAP JS BUNDLE
add_action('wp_print_scripts', function () {
    wp_dequeue_script('bootstrap5');
    //wp_dequeue_script( 'dark-mode-switch' );  //optionally
}, 100);

// ENQUEUE THE BOOTSTRAP JS BUNDLE (AND EVENTUALLY MORE LIBS) FROM THE CHILD THEME DIRECTORY
add_action('wp_enqueue_scripts', function () {
    //enqueue js in footer, defer
    wp_enqueue_script('bootstrap5-childtheme', get_stylesheet_directory_uri() . "/js/bootstrap.bundle.min.js", array(), null, array('strategy' => 'defer', 'in_footer' => true));

    //optional: example of how to globally load js files eg  lottie player
    //wp_enqueue_script( 'lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array(), null, array('strategy' => 'defer', 'in_footer' => true)  );
}, 101);

// HACK HERE: ENQUEUE YOUR CUSTOM JS FILES, IF NEEDED
add_action('wp_enqueue_scripts', function () {

    //UNCOMMENT next row to include the js/custom.js file globally
    //wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array(/* 'jquery' */), null, array('strategy' => 'defer', 'in_footer' => true) );

    //UNCOMMENT next 3 rows to load the js file only on one page
    //if (is_page('mypageslug')) {
    //    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array(/* 'jquery' */), null, array('strategy' => 'defer', 'in_footer' => true) );
    //}

}, 102);

// OPTIONAL: ADD MORE NAV MENUS
//register_nav_menus( array( 'third' => __( 'Third Menu', 'picostrap' ), 'fourth' => __( 'Fourth Menu', 'picostrap' ), 'fifth' => __( 'Fifth Menu', 'picostrap' ), ) );
// THEN USE SHORTCODE:  [lc_nav_menu theme_location="third" container_class="" container_id="" menu_class="navbar-nav"]

// CHECK PARENT THEME VERSION
add_action('admin_notices', function () {
    if ((pico_get_parent_theme_version()) >= 3.0) {
        return;
    }

    $message = __('This Child Theme requires at least Picostrap Version 3.0.0  in order to work properly. Please update the parent theme.', 'picostrap');
    printf('<div class="%1$s"><h1>%2$s</h1></div>', esc_attr('notice notice-error'), esc_html($message));
});

// FOR SECURITY: DISABLE APPLICATION PASSWORDS. Remove if needed (unlikely!)
add_filter('wp_is_application_passwords_available', '__return_false');

// ADD YOUR CUSTOM PHP CODE DOWN BELOW /////////////////////////

// TWIG EDITOR SUPPORT
// Enqueue twig.js from child theme
function my_child_theme_enqueue_twig_js()
{
    wp_enqueue_script('twig-js', get_stylesheet_directory_uri() . '/js/editor/twig.js', array(), '1.0', true);
}
add_action('lc_editor_header', 'my_child_theme_enqueue_twig_js');

function my_timber_content_filter($content)
{
    // Check if Timber is available
    if (class_exists('Timber')) {
        global $post;

        $context = Timber::context();
        $context['post'] = Timber::get_post($post->ID);
        $context['state'] = $context;

        // Compile the current content as a Twig template
        $compiled_content = Timber::compile_string($content, $context);

        return $compiled_content;
    }

    // If Timber is not available, return the original content
    return $content;
}

add_filter('the_content', 'my_timber_content_filter', 10);
