<?php
/**
 * Plugin Name: ESA by StudioLAB
 * Description: Elementor widget suite designed for Emmanuelle Simon.
 * Version:     1.0.0
 * Author:      StudiosLAB
 * Author URI:  https://studioslab.fr
 * Text Domain: esabystudiolab
 */

require_once(__DIR__ . '/function/url-function.php');
require_once(__DIR__ . '/function/product-function.php');
require_once(__DIR__ . '/function/sorte-function.php');
require_once(__DIR__ . '/function/filter-function.php');
require_once(__DIR__ . '/function/submenu-eshop-function.php');
require_once(__DIR__ . '/function/project-function.php');
require_once(__DIR__ . '/function/submenu-archictecture-mobile-function.php');
require_once(__DIR__ . '/function/submenu-eshop-mobile-function.php');



function register_studioslab_widgets($widgets_manager)
{


    require_once(__DIR__ . '/widgets/product-list-widget.php');
    require_once(__DIR__ . '/widgets/product-grid-widget.php');
    require_once(__DIR__ . '/widgets/other-product-list-widget.php');
    require_once(__DIR__ . '/widgets/header-list-widget.php');
    require_once(__DIR__ . '/widgets/submenu-architecture-widget.php');
    require_once(__DIR__ . '/widgets/header-list-mobile-widget.php');
    require_once(__DIR__ . '/widgets/emma-menu-widget.php');
    require_once(__DIR__ . '/widgets/emma-submenu-widget.php');
    require_once(__DIR__ . '/widgets/emma-mobile-menu-widget.php');
    require_once(__DIR__ . '/widgets/emma-product-materials-widget.php');
    require_once(__DIR__ . '/widgets/emma-product-materials-options-widget.php');
    require_once(__DIR__ . '/widgets/emma-home-page-gallery.php');
    require_once(__DIR__ . '/widgets/emma-mobile-home-page-gallery.php');
    require_once(__DIR__ . '/widgets/emma-search-results-widget.php');


    $widgets_manager->register(new \Elementor_Product_List_Widget());
    $widgets_manager->register(new \Elementor_Other_Product_List_Widget());
    $widgets_manager->register(new \Elementor_Header_List_Widget());
    $widgets_manager->register(new \Elementor_SubMenu_Architecture_Widget());
    $widgets_manager->register(new \Elementor_Header_List_Mobile_Widget());
    $widgets_manager->register(new \Elementor_Emma_Menu_Widget());
    $widgets_manager->register(new \Elementor_Emma_Sub_Menu_Widget());
    $widgets_manager->register(new \Elementor_Emma_Mobile_Menu_Widget());
    $widgets_manager->register(new \Elementor_Emma_Product_Materials_Widget());
    $widgets_manager->register(new \Elementor_Emma_Product_Materials_Options_Widget());
    $widgets_manager->register(new \Elementor_Emma_Home_Page_Gallery_Widget());
    $widgets_manager->register(new \Elementor_Emma_Mobile_Home_Page_Gallery_Widget());
    $widgets_manager->register(new \Elementor_Emma_Search_Results_Widget());

    $widgets_manager->register(new \Elementor_Product_Grid_Widget());

}

function register_widget_styles()
{
    wp_register_style('productList', plugins_url('./assets/css/productList.css', __FILE__));
    wp_register_style('headerList', plugins_url('./assets/css/headerList.css', __FILE__));
    wp_register_style('headerListMobile', plugins_url('./assets/css/headerListMobile.css', __FILE__));
}

function register_widget_scripts()
{
    wp_register_script('productList', plugins_url('./assets/js/productList.js', __FILE__), array(), '', true);
    wp_register_script('headerList', plugins_url('./assets/js/headerList.js', __FILE__), array(), '', true);
    wp_register_script('headerListMobile', plugins_url('./assets/js/headerListMobile.js', __FILE__), array(), '', true);
    wp_register_script('submenuArchitecture', plugins_url('./assets/js/submenuArchitecture.js', __FILE__), array(), '', true);
}


function add_elementor_widget_categories($elements_manager)
{

    $elements_manager->add_category(
        'studioslab',
        [
            'title' => esc_html__('StudiosLab', 'esabystudiolab'),
            'icon' => 'fa fa-plug',
        ]
    );
}

function register_elementor_product_price_tag($dynamic_tags)
{
    require_once(__DIR__ . '/dynamic-tags/price-dynamic-tag.php');
    require_once(__DIR__ . '/dynamic-tags/price-en-dynamic-tag.php');

    $dynamic_tags->register(new \Elementor_Product_Price_Tag());
    $dynamic_tags->register(new \Elementor_Product_Price_En_Tag());
}



add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');
add_action('wp_enqueue_scripts', 'register_widget_styles');
add_action('wp_enqueue_scripts', 'register_widget_scripts', '', '', true);
add_action('elementor/widgets/register', 'register_studioslab_widgets');
add_action('elementor/dynamic_tags/register', 'register_elementor_product_price_tag');

add_filter('woocommerce_product_data_store_cpt_get_products_query', 'handle_custom_query_var', 10, 2);

function handle_custom_query_var($query_args, $query_vars)
{
    if (!empty($query_vars['sorte'])) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'sorte',
            'field' => 'slug',
            'terms' => $query_vars['sorte'],
        );
    }
    return $query_args;
}

add_filter('display_post_states', 'add_language_code_to_menu_manager_items', 10, 2);
function add_language_code_to_menu_manager_items($post_states, $post)
{
    // Check if a plugin like Polylang or WPML is installed and active for multilingual setup
    if (function_exists('pll_get_post_language')) {
        $language_code = pll_get_post_language($post->ID); // Get the current language code (e.g., 'en', 'fr')
    } else {
        $language_code = ''; // Fallback if no multilingual plugin is active
    }

    $post_states = [];
    if (!empty($language_code)) {
        $post_states['lang'] = $language_code;
    }

    return $post_states;
}

/* Home Page Tiles - Start */
function register_home_page_tile_post_type()
{
    $labels = array(
        'name' => _x('Home Page Tiles', 'post type general name'),
        'singular_name' => _x('Home Page Tile', 'post type singular name'),
        'menu_name' => __('Home Page Tiles'),
        'name_admin_bar' => __('Home Page Tile'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Home Page Tile'),
        'new_item' => __('New Home Page Tile'),
        'edit_item' => __('Edit Home Page Tile'),
        'view_item' => __('View Home Page Tile'),
        'all_items' => __('All Home Page Tiles'),
        'search_items' => __('Search Home Page Tiles'),
        'not_found' => __('No Home Page Tiles found.'),
        'not_found_in_trash' => __('No Home Page Tiles found in Trash.')
    );

    $args = array(
        'labels' => $labels,
        'public' => false, // Makes it not publicly visible
        'publicly_queryable' => false, // Prevents front-end queries
        'show_ui' => true,  // Allows managing in admin
        'show_in_menu' => true,  // Displays in admin menu
        'query_var' => false, // No custom query variable
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title'), // Only the title field
        'show_in_rest' => false, // Enables Gutenberg editor and REST API
    );

    register_post_type('home_page_tile', $args);
}
add_action('init', 'register_home_page_tile_post_type');

function home_page_tile_add_custom_columns($columns)
{
    // Remove unnecessary columns
    unset($columns['date']);
    unset($columns['stats']);

    // Add featured image and title columns
    $columns = array(
        'cb' => $columns['cb'], // Checkbox for bulk actions
        'featured_image' => __('Featured Image'),
        'title' => __('Title'),
    );

    return $columns;
}
add_filter('manage_home_page_tile_posts_columns', 'home_page_tile_add_custom_columns');

function home_page_tile_custom_column_content($column, $post_id)
{
    if ($column === 'featured_image') {
        // Display the featured image
        $image_id = get_post_meta($post_id, 'image', true);
        $image = wp_get_attachment_image_src($image_id, 'medium');
        echo $image_id ? '<img src="' . $image[0] . '">' : __('No Image', 'emma');
        // print_r($image);
        // echo $thumbnail ? $thumbnail : __('No Image', 'esabystudiolab');
    }
}
add_action('manage_home_page_tile_posts_custom_column', 'home_page_tile_custom_column_content', 10, 2);

function home_page_tile_admin_list_styles()
{
    echo '<style>
        .column-featured_image {
            width: 90px; /* Adjust column width */
        }
        .column-title {
            width: auto;
        }
        .wp-list-table .featured_image img {
            display: block;
            margin: 0 auto;
			width: 70px;
			height: auto;
        }
    </style>';
}
add_action('admin_head', 'home_page_tile_admin_list_styles');

/* Home Page Tiles - End */


/* Category/Subcategory Archive Pages */
function esa_custom_archive_pages()
{
    global $wp;

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($categories) && !empty($categories)) {
        $wp->add_query_var('esacat');
        foreach ($categories as $category) {
            // echo 'Category Name: ' . esc_html($term->name) . '<br>';
            // echo 'Category Slug: ' . esc_html($term->slug) . '<br>';
            // echo 'Category ID: ' . esc_html($term->term_id) . '<br>';
            // echo '<br>';

            add_rewrite_rule('collection/' . $category->slug . '/?$', 'index.php?pagename=test', after: 'top');
        }
    }


    /*global $wp_rewrite;
    $wp_rewrite->flush_rules();*/
}
add_action('init', 'esa_custom_archive_pages');

function esa_custom_search_for_products( $query ) {
    // Ensure we are modifying the main search query on the front end
    if ( !is_admin() && $query->is_main_query() && $query->is_search() ) {
        
        // Restrict to 'product' post type only
        $query->set( 'post_type', ['product'] );

        // Add custom taxonomies to the search query
        $tax_query = array(
            'relation' => 'OR', // Match terms in any taxonomy
            array(
                'taxonomy' => 'sorte', // Example: Replace with your custom taxonomy name
                'field'    => 'name',
                'terms'    => $query->query['s'], // Search term
                'operator' => 'LIKE',
            ),
            array(
                'taxonomy' => 'product_cat', // Example: Replace with another taxonomy if needed
                'field'    => 'name',
                'terms'    => $query->query['s'],
                'operator' => 'LIKE',
            )
        );
        
        // Combine taxonomies into query arguments
        $query->set( 'tax_query', $tax_query );

        // Enable search in titles
        // add_filter( 'posts_search', 'esa_search_in_titles_and_taxonomies', 10, 2 );
    }
}
add_action( 'pre_get_posts', 'esa_custom_search_for_products' );

/**
 * Custom function to search in titles while keeping taxonomy filtering.
 */
function esa_search_in_titles_and_taxonomies( $search, $query ) {
    global $wpdb;

    if ( $query->is_search() && !is_admin() ) {
        $search = '';
        $search_terms = explode( ' ', $query->query_vars['s'] );

        // Search in post titles only
        foreach ( $search_terms as $term ) {
            $term = esc_sql( like_escape( $term ) );
            $search .= " AND ({$wpdb->posts}.post_title LIKE '%{$term}%')";
        }
    }

    return $search;
}

add_action('admin_enqueue_scripts', 'enqueue_esa_scripts');
function enqueue_esa_scripts($hook) {
    if ($hook !== 'toplevel_page_esa-products-screen') {
        return;
    }

    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('esa-admin-script', plugin_dir_url(__FILE__) . '/assets/js/esa-admin.js', ['jquery-ui-sortable'], null, true);

    wp_enqueue_style('esa-admin-style', plugin_dir_url(__FILE__) . '/assets/css/esa-admin.css');
}