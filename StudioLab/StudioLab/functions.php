<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array());
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

if (!function_exists('child_theme_configurator_css')):
    function child_theme_configurator_css()
    {
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('chld_thm_cfg_parent'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);

function esa_custom_search_filter($search, $query)
{
    global $wpdb;

    if ($query->is_search() && !is_admin() && $query->is_main_query()) {
        $query->set('post_type', ['product']);

        // Get the search term
        $search_term = $query->get('s');

        // If there's a search term, modify the SQL
        if (!empty($search_term)) {
            $search = $wpdb->prepare(
                " AND {$wpdb->posts}.post_title LIKE %s ",
                '%' . $wpdb->esc_like($search_term) . '%'
            );
        }
    }

    return $search;
}
// add_filter('posts_search', 'esa_custom_search_filter', 10, 2);

function esa_custom_search_post_types($query)
{
    // Check if this is a search query and the main query on the front end
    if (!is_admin() && $query->is_search() && $query->is_main_query()) {
        // Restrict to 'product' and 'architecture' post types
        $query->set('post_type', ['product']);
    }
}
// add_action('pre_get_posts', 'esa_custom_search_post_types');

function esa_search_taxonomy_terms($search_term)
{
    $taxonomies = ['product_cat', 'sorte']; // Add your taxonomies here
    $matching_terms = get_terms([
        'taxonomy' => $taxonomies,
        'name__like' => $search_term, // Search for terms matching the search query
        'hide_empty' => false,
    ]);

    if (!empty($matching_terms) && !is_wp_error($matching_terms)) {
        return $matching_terms;
    }

    return [];
}

function esa_post_type_title_filter( $title, $id = null ) {
    if (get_post_type($id) == 'product' && is_admin()) {
        $current_screen = get_current_screen();
        if ($current_screen->id == 'home_page_tile') {
            // $post_meta = get_post_meta($id);
            $ref = get_post_meta($id, 'ref', true);
            $collection_name = get_post_meta($id, 'collection_name', true);
            $language = pll_get_post_language($id, 'slug');

           // return $collection_name.' - '.$title.' ('.$ref.') - '.$language;
           return $ref . ' / ' . $collection_name . ' / ' . $title ;
        }
    }

    if (get_post_type($id) == 'architecture' && is_admin()) {
        $current_screen = get_current_screen();
        if ($current_screen->id == 'home_page_tile') {
            $language = pll_get_post_language($id, 'slug');

            return $title. ' - '.$language;
        }
    }

    return $title;
}
add_filter( 'the_title', 'esa_post_type_title_filter', 10, 2 );

add_filter('manage_edit-product_columns', 'esa_add_ref_column_to_product');
add_action('manage_product_posts_custom_column', 'esa_render_ref_column_content', 10, 2);
add_filter('manage_edit-product_sortable_columns', 'esa_make_ref_column_sortable');
add_action('pre_get_posts', 'esa_sort_ref_column_query');

/**
 * Add custom column to product list.
 */
function esa_add_ref_column_to_product($columns) {
    // Add the "Ref" column after the Name column
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'name') { // After the Name column
            $new_columns['ref'] = __('Ref', 'hello-elementor');
        }
    }
    return $new_columns;
}

/**
 * Display content for the custom column.
 */
function esa_render_ref_column_content($column, $post_id) {
    if ('ref' === $column) {
        $ref_value = get_post_meta($post_id, 'ref', true); // Get the 'ref' meta value
        echo esc_html($ref_value ? $ref_value : '—'); // Display value or fallback
    }
}

/**
 * Make the custom column sortable.
 */
function esa_make_ref_column_sortable($sortable_columns) {
    $sortable_columns['ref'] = 'ref'; // Register the column as sortable
    return $sortable_columns;
}

/**
 * Handle sorting of the custom column.
 */
function esa_sort_ref_column_query($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('ref' === $query->get('orderby')) {
        $query->set('meta_key', 'ref'); // Meta key for sorting
        $query->set('orderby', 'meta_value'); // Sort by the meta value
    }
}

function esa_search_form()
{
    if (function_exists('pll_current_language')):
        $current_language = pll_current_language();
        $slug = $current_language == 'en' ? '/en/' : '/';
        ?>
        <form class="esa-search-form emma-search-widget" action="<?php echo esc_url(home_url($slug)); ?>" method="get">
            <input type="text" name="s" placeholder="<?php esc_attr_e('Search', 'default'); ?>...">
            <!-- <input type="hidden" name="lang" value="<?php echo $current_language ?>"> -->
            <button type="submit">
                <svg aria-hidden="true" class="e-font-icon-svg e-fas-chevron-right" viewBox="0 0 320 512"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                    </path>
                </svg>
            </button>
        </form>
    <?php endif;
}
add_shortcode('esa_search_form', 'esa_search_form');
function esa_search_form_mobile()
{
    if (function_exists('pll_current_language')):
        $current_language = pll_current_language();
        $slug = $current_language == 'en' ? '/en/' : '/';
        ?>
        <form class="esa-search-form emma-search-widget mobile" action="<?php echo esc_url(home_url($slug)); ?>" method="get">
            <input type="text" name="s" placeholder="<?php esc_attr_e('Search', 'default'); ?>...">
            <!-- <input type="hidden" name="lang" value="<?php echo $current_language ?>"> -->
            <button type="submit">
                <svg aria-hidden="true" class="e-font-icon-svg e-fas-chevron-right" viewBox="0 0 320 512"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                    </path>
                </svg>
            </button>
        </form>
    <?php endif;
}
add_shortcode('esa_search_form_mobile', 'esa_search_form_mobile');


