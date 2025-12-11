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
        wp_enqueue_script('custom_js', trailingslashit(get_stylesheet_directory_uri()) . 'js/custom.js?v=1.0',   array('jquery'),null,true);
        
        // wp_localize_script( 'custom_js', 'myScriptData', array(
        //     'currentLanguage' => pll_current_language() // Pass the current language
        // ));
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
        $placeholder = $current_language == 'fr' ? 'Recherche...' : __('Search', 'default') . '...';
        ?>
        <form class="esa-search-form emma-search-widget" action="<?php echo esc_url(home_url($slug)); ?>" method="get">
            <input type="text" name="s" placeholder="<?php echo esc_attr($placeholder); ?>">
            <!-- <input type="hidden" name="lang" value="<?php //echo $current_language ?>"> -->
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
        $placeholder = $current_language == 'fr' ? 'Recherche...' : __('Search', 'default') . '...';
        ?>
        <form class="esa-search-form emma-search-widget mobile" action="<?php echo esc_url(home_url($slug)); ?>" method="get">
            <input type="text" name="s" placeholder="<?php echo esc_attr($placeholder); ?>">
            <!-- <input type="hidden" name="lang" value="<?php //echo $current_language ?>"> -->
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

function esv_custom_pods_field_shortcode( $atts ) {
    $atts = shortcode_atts(
        [
            'field' => '',
        ],
        $atts
    );
    if ( empty( $atts['field'] ) ) {
        return '';
    }
    $value = pods_field( get_post_type(), get_the_ID(), $atts['field'] );

    if ( empty( $value ) ) {
        return ''; // Return blank if no value
    }

    if ( is_array( $value ) ) {
        $value = implode( ', ', array_filter( $value ) ); 
    }

    if ( ! empty( $value ) ) {
        return '<div class="custom-pods-field">' . esc_html( $value ) . '</div>';
    }
    return ''; // Return blank if nothing is valid
}
add_shortcode( 'custom_pods_display', 'esv_custom_pods_field_shortcode' );

// function load_yith_wishlist_translation() {
//     load_plugin_textdomain('yith-woocommerce-wishlist', false, dirname(plugin_basename(__FILE__)) . '/languages/');
// }
// add_action('plugins_loaded', 'load_yith_wishlist_translation');

if ( function_exists( 'pll_register_string' ) ) {
    pll_register_string( 'wishlist_dimension', 'Dimension', 'yith-woocommerce-wishlist' );
    pll_register_string( 'wishlist_download', 'Télécharger', 'yith-woocommerce-wishlist' );
    pll_register_string( 'wishlist_price_request', 'Demande de prix', 'yith-woocommerce-wishlist' );
    pll_register_string( 'Select height', 'Sélectionner la hauteur', 'woocommerce' );
    pll_register_string( 'order', 'COMMANDER', 'child-studio-lab' );
    pll_register_string( 'PRICE', 'PRIX', 'child-studio-lab' );
    pll_register_string( 'EXCL. VAT', 'HT', 'child-studio-lab' );
}


/** 
 * Enhances 'pa_materiaux' label with Pods material info and a color variation note. 
 */
function zl_customize_materiaux_label($label, $name) {
    if ($name === 'pa_materiaux' && !is_admin()) { // Ensure it runs only on the frontend
        global $post;

        // Get the Pods field value (Replace 'material' with your actual Pods field name)
        $pods = pods('product', $post->ID);
        $extra_text = $pods ? $pods->display('material') : '';

        // Only append if the field has a value
        if (!empty($extra_text)) {
            return $label . '</label> <span class="materiaux-description">' . esc_html($extra_text) . '</span><br><i class="zl-variation-note">*Slight color variations may occur.</i><label class="zl-d-none">';
        }
    }

    if( $name == 'pa_hauteur' && !is_admin())
        $label = '<div class="zl-height-attribute-label"><span>' . __('Option ', 'woocommerce') . '</span><span class="zl-height-attribute-placeholder">' . pll__('- Sélectionnez la hauteur', 'woocommerce') . '</span></div>';

    return $label;
}

add_filter('woocommerce_attribute_label', 'zl_customize_materiaux_label', 10, 2);

/** 
 * Adds custom classes to attribute rows for 'pa_tissu' and 'pa_hauteur' labels. 
 * Uses MutationObserver to handle dynamically loaded elements.
 */
function zl_add_attribute_class_script() {
    ?>
    <script>
    (function() {
        function applyClass() {
            addClassToAttribute('pa_tissu', 'zl-drop-down-attribute');
            addClassToAttribute('pa_hauteur', 'zl-height-attribute');
        }

        function addClassToAttribute(attribute, className) {
            let label = document.querySelector(`label[for="${attribute}"]`);
            if (label) {
                let tr = label.closest('tr');
                if (tr) {
                    tr.classList.add(className);
                }
            }
        }

        // Run immediately if elements are already available
        applyClass();

        // Use MutationObserver to catch dynamically loaded elements
        const observer = new MutationObserver(() => applyClass());
        observer.observe(document, { childList: true, subtree: true });
    })();

    jQuery(document).ready(function($) {
        function updateProductDimensions() {
            $('.woocommerce-product-attributes-item__value').each(function() {
            let $this = $(this);
            let text = $this.text().trim();

            // Replace × with × H (only once)
            if (text.includes('×') && !text.includes('× H')) {
                text = text.replace('×', '× H');
                $this.text(text);
            }

            // Extract dimensions like: 65 × H 65 cm
            let dimensions = text.match(/(\d+(?:\.\d+)?)\s*×\s*H\s*(\d+(?:\.\d+)?)\s*cm/i);
            if (dimensions && dimensions.length >= 3) {
                let widthCm = parseFloat(dimensions[1]);
                let heightCm = parseFloat(dimensions[2]);

                let widthInches = (widthCm * 0.3937).toFixed(2);
                let heightInches = (heightCm * 0.3937).toFixed(2);

                $('.zl-cm-in .elementor-widget-container').text(`Ø  ${widthInches} × H ${heightInches} in`);
            }
            });
        }

        // Trigger on swatch click
        $(document).on('click', '.swatchly-swatch', function() {
            updateProductDimensions();
        });

        // Elementor init
        $(window).on('elementor/frontend/init', function() {
            updateProductDimensions();
        });

        // Observe product attributes section
        const target = document.querySelector('.woocommerce-product-attributes');
        if (target) {
            const observer = new MutationObserver(function() {
            updateProductDimensions();
            });

            observer.observe(target, {
            childList: true,
            subtree: true,
            });
        }
    });

    jQuery(document).ready(function($) {
        $(document).on('click', '.yith-wcwl-add-button a', function(e) {
            e.preventDefault(); 
            
            var $button = $(this).closest('.yith-wcwl-add-button'); // Select the parent button container
            var $link = $(this); // Select the link

            // Disable pointer events on the entire button and the link when clicked
            $button.css('pointer-events', 'none'); 
            $link.css('pointer-events', 'none');

            // Re-enable pointer events after a brief delay (to prevent the blue loader)
            setTimeout(function() {
                $button.css('pointer-events', '');
                $link.css('pointer-events', '');
            }, 3100);
        });
    });

    </script>
    <?php
}

add_action('wp_head', 'zl_add_attribute_class_script');


function esa_custom_dynamic_email_body_shortcode($atts) {
    $current_language = pll_current_language();
    $atts = shortcode_atts(array(
        'firstname'   => '',
        'product'     => '',
        'collection'  => '',
        'price'       => '',
        'price_type'  => ''
    ), $atts);

    ob_start();

    if ($current_language === 'fr') {
        // French email content
        if ($atts['price_type'] === 'price_request') {
            ?>
            Cher/e <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Nous vous remercions de l’intérêt que vous portez pour les créations de design d’EMMANUELLE SIMON.<br/><br/>
            <?php echo esc_html($atts['product']); ?> - <?php echo esc_html($atts['collection']); ?> est disponible à <?php echo esc_html($atts['price']); ?> (HT).<br/>
            Pour toute autre demande, notre équipe reviendra vers vous très prochainement.<br/><br/>
            Bien cordialement,<br/>
            L’équipe Emmanuelle Simon
            <?php
        } else {
            ?>
            Cher/e <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Nous vous remercions de l’intérêt que vous portez pour les créations de design d’EMMANUELLE SIMON.<br/><br/>
            Notre équipe reviendra vers vous très prochainement pour s'occuper de votre commande.<br/><br/>
            Bien cordialement,<br/>
            L’équipe Emmanuelle Simon
            <?php
        }
    } else if ($current_language === 'en') {
        // English email content
        if ($atts['price_type'] === 'price_request') {
            ?>
            Dear <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Thank you for your interest in EMMANUELLE SIMON’s design creations.<br/><br/>
            <?php echo esc_html($atts['product']); ?> - <?php echo esc_html($atts['collection']); ?> is available <?php echo esc_html($atts['price']); ?> (Excl. VAT).<br/>
            For all other inquiries, our team will get back to you shortly.<br/><br/>
            Best regards,<br/>
            The Emmanuelle Simon team
            <?php
        } else {
            ?>
            Dear <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Thank you for your interest in EMMANUELLE SIMON’s design creations.<br/><br/>
            Our team will get back to you shortly to assist with your order.<br/><br/>
            Best regards,<br/>
            The Emmanuelle Simon team
            <?php
        }
    }

    return ob_get_clean();
}
add_shortcode('eas_dynamic_price_email_body', 'esa_custom_dynamic_email_body_shortcode');

// add_filter('woocommerce_get_price_html', 'remove_decimals_from_price_display', 100, 2);

// function remove_decimals_from_price_display($price, $product) {
//     // Remove decimals and keep the comma formatting
//     $price = preg_replace('/([0-9]+),00/', '$1', $price);
//     return $price;
// }
// add_filter('woocommerce_get_price_html', 'remove_decimals_from_price_display', 100, 2);

// function remove_decimals_from_price_display($price, $product) {
//     // Remove all ",00" from price range display too
//     $price = preg_replace('/,00/', '', $price);
//     return $price;
// }
// add_filter('woocommerce_price_decimals', 'remove_woocommerce_decimals');
// function remove_woocommerce_decimals($decimals) {
//     return 0; // Force WooCommerce to show no decimals
// }
// add_filter('woocommerce_get_price_html', 'custom_strip_trailing_zeros', 100, 2);
// function custom_strip_trailing_zeros($price, $product) {
//     // Ensure formatted price does not include any decimals
//     return preg_replace('/,00(?=\s|&nbsp;|€)/', '', $price);
// }
// add_filter('formatted_woocommerce_price', function($formatted_price, $price, $decimals, $decimal_separator) {
//     // Need to trim 0s only if we have the decimal separator present.
//     if (strpos($formatted_price, $decimal_separator) !== false) {
//         $formatted_price = rtrim($formatted_price, '0');
//         // After trimming trailing 0s, it may happen that the decimal separator will remain there trailing... just get rid of it, if it's the case.
//         $formatted_price = rtrim($formatted_price, $decimal_separator);
//     }
//     return $formatted_price;
// }, 10, 4);
// add_filter( 'woocommerce_price_trim_zeros', '__return_true' );


function esa_custom_dynamic_email_body_shortcode_new($atts) {
    $current_language = pll_current_language();
    $atts = shortcode_atts(array(
        'firstname'   => '',
        'product'     => '',
        'collection'  => '',
        'price'       => '',
        'price_type'  => ''
    ), $atts);

    ob_start();

    if ($current_language === 'fr') {
        // French email content
        if ($atts['price_type'] === 'price_request') {
            $template_content = do_shortcode('[elementor-template id="45159"]');

            $replacements = array(
                '[firstname]'  => esc_html($atts['firstname']),
                '[product]'    => esc_html($atts['product']),
                '[collection]' => esc_html($atts['collection']),
                '[price]'      => esc_html($atts['price']),
            );

            $template_content = strtr($template_content, $replacements);

            echo $template_content;
        } else {
            ?>
            Cher/e <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Nous vous remercions de l’intérêt que vous portez pour les créations de design d’EMMANUELLE SIMON.<br/><br/>
            Notre équipe reviendra vers vous très prochainement pour s'occuper de votre commande.<br/><br/>
            Bien cordialement,<br/>
            L’équipe Emmanuelle Simon
            <?php
        }
    } else if ($current_language === 'en') {
        // English email content
        if ($atts['price_type'] === 'price_request') {
            ?>
            Dear <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Thank you for your interest in EMMANUELLE SIMON’s design creations.<br/><br/>
            <?php echo esc_html($atts['product']); ?> - <?php echo esc_html($atts['collection']); ?> is available from <?php echo esc_html($atts['price']); ?> (Excl. VAT).<br/>
            For all other inquiries, our team will get back to you shortly.<br/><br/>
            Best regards,<br/>
            The Emmanuelle Simon team
            <?php
        } else {
            ?>
            Dear <?php echo esc_html($atts['firstname']); ?>,<br/><br/>
            Thank you for your interest in EMMANUELLE SIMON’s design creations.<br/><br/>
            Our team will get back to you shortly to assist with your order.<br/><br/>
            Best regards,<br/>
            The Emmanuelle Simon team
            <?php
        }
    }

    return ob_get_clean();
}
add_shortcode('eas_dynamic_price_email_body_new', 'esa_custom_dynamic_email_body_shortcode_new');