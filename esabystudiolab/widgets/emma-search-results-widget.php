<?php
class Elementor_Emma_Search_Results_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'emma_search_results';
    }

    public function get_title()
    {
        return esc_html__('ESA Search Results', 'elementor-addon');
    }

    public function get_style_depends()
    {
        return ['productList'];
    }

    public function get_script_depends()
    {
        return ['productList'];
    }

    public function get_icon()
    {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories()
    {
        return ['studioslab'];
    }

    public function get_keywords()
    {
    }

    protected function register_controls()
    {

        // Content Tab Start

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('ESA Search Results', 'elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();

        // Content Tab End


        // Style Tab Start

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('ESA Search Results', 'elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]

        );

        $this->end_controls_section();

        // Style Tab End

    }

    protected function get_terms_matching_string($taxonomy, $search)
    {
        // Fetch terms from the taxonomy
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms)) {
            return [];
        }

        $matching_terms = array_filter($terms, function ($term) use ($search) {
            return stripos($term->name, $search) !== false;  // Match category name
        });

        return $matching_terms;
    }

    protected function render()
    {
        $search = get_product_search();
        $matching_categories = $this->get_terms_matching_string('product_cat', $search);
        $matching_sortes = $this->get_terms_matching_string('sorte', $search);
        $matching_architecture_categories = $this->get_terms_matching_string('famille', $search);

        $terms = array_merge($matching_categories, $matching_sortes, $matching_architecture_categories);
        $products = []; 
        $architecture_posts = []; 
        $idsProduct = [];
        $idsArchitecture = [];
        if (!empty($terms)) {
            $tax_query = [];
            $tax_query['relation'] = 'OR';
            foreach ($terms as $term) {
                $tax_query[] = array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'term_id',
                    'terms' => array($term->term_id)
                );
            }
            $the_query = new WP_Query(
                array(
                    'posts_per_page' => -1,
                    'post_type' => ['product', 'architecture'],
                    'tax_query' => $tax_query
                )
            );
            
           

            while ($the_query->have_posts()) {
                $the_query->the_post();
                if (get_post_type() === 'product') {
                    $idsProduct[] = get_the_ID();
                } elseif (get_post_type() === 'architecture') {
                    $idsArchitecture[] = get_the_ID();
                }
            }

            wp_reset_postdata();
        }
        // if (!empty($search)) {
        //     // Decode search term to handle '+' and '%20' properly
        //     $search = urldecode($search);
        
        //     // Normalize search term by replacing hyphens with spaces
        //     $normalized_search = str_replace('-', ' ', $search);
        //     $normalized_search = trim($normalized_search);
        
        //     $architecture_search_query = new WP_Query([
        //         'posts_per_page' => -1,
        //         'post_type'      => 'architecture',
        //         's'             => $search, // Default WP Search
        //     ]);
        
        //     $matching_architecture_posts = [];
        //     while ($architecture_search_query->have_posts()) {
        //         $architecture_search_query->the_post();
        
        //         // Get the title and normalize it
        //         $normalized_title = str_replace('-', ' ', get_the_title());
        //         $normalized_title = trim($normalized_title);
        
        //         // Check if the normalized search term is found in the normalized title
        //         if (
        //             stripos($normalized_title, $normalized_search) !== false ||
        //             stripos(get_the_title(), $search) !== false // Fallback to original search
        //         ) {
        //             $matching_architecture_posts[] = get_the_ID();
        //         }
        //     }
        //     wp_reset_postdata();
        
        //     $idsArchitecture = $matching_architecture_posts;
        // }
        // if (!empty($search)) {
        //     $architecture_search_query = new WP_Query([
        //         'posts_per_page' => -1,
        //         'post_type'      => 'architecture',
        //         's'             => $search, // Search without modification
        //     ]);
        
        //     $matching_architecture_posts = [];
        //     while ($architecture_search_query->have_posts()) {
        //         $architecture_search_query->the_post();
                
        //         $title = get_the_title();
        //         $normalized_title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title); // Remove special characters from title
        
        //         // Split search input into words (keeping original special characters)
        //         $search_words = explode(' ', $search);
        
        //         $match_found = true; // Assume a match until proven otherwise
        //         foreach ($search_words as $word) {
        //             // Check if each word exists in the normalized title
        //             if (stripos($normalized_title, $word) === false) {
        //                 $match_found = false;
        //                 break; // Stop checking if any word is missing
        //             }
        //         }
        
        //         if ($match_found) {
        //             $matching_architecture_posts[] = get_the_ID();
        //         }
        //     }
        //     wp_reset_postdata();
        //     $idsArchitecture = $matching_architecture_posts;
        // }
        
        if (!empty($search)) {
            // Normalize the search term: remove special characters, convert to lowercase, and remove apostrophes
            $normalized_search = preg_replace('/[^a-zA-Z0-9\s]/', '', str_replace(["'", "’"], '', $search));
            $normalized_search = strtolower(trim($normalized_search));
        
            $architecture_search_query = new WP_Query([
                'posts_per_page' => -1,
                'post_type'      => 'architecture',
            ]);
        
            $matching_architecture_posts = [];
            while ($architecture_search_query->have_posts()) {
                $architecture_search_query->the_post();
                
                // Get the title and normalize it: remove special characters, convert to lowercase, and remove apostrophes
                $title = get_the_title();
                $normalized_title = preg_replace('/[^a-zA-Z0-9\s]/', '', str_replace(["'", "’"], '', $title));
                $normalized_title = strtolower(trim($normalized_title));
                
                // Additional normalization: Remove spaces from search term
                $search_without_spaces = str_replace(' ', '', $normalized_search);
                $title_without_spaces = str_replace(' ', '', $normalized_title);
        
                // Check for matches in both original and normalized forms
                if (
                    stripos($title, $search) !== false || // Original search with special characters
                    stripos($normalized_title, $normalized_search) !== false || // Normalized search
                    stripos($title_without_spaces, $search_without_spaces) !== false // Search without spaces
                ) {
                    $matching_architecture_posts[] = get_the_ID();
                }
            }
            wp_reset_postdata();
            $idsArchitecture = $matching_architecture_posts;
        }
        
        if (!empty($idsProduct)) {
            $products = wc_get_products([
                'limit' => -1,
                'include' => $idsProduct,
                'status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
        }else{
            $products = get_products();
        }

        // Fetch architecture posts only if matches are found
        if (!empty($idsArchitecture)) {
            $architecture_posts = get_posts([
                'post_type' => 'architecture',
                'post__in' => $idsArchitecture,
                'posts_per_page' => -1
            ]);
        }
    
        ?>

        <div class="content">
            <?php
            $query = get_query_var('s');

            if (count($products) > 0 || count($architecture_posts) > 0) {
                ?>
                <div class="view-column">
                    <button id="view-five">
                        <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <rect x=1 y=1 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                            <rect x=1 y=11 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                            <rect x=11 y=1 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                            <rect x=11 y=11 width="6" height="6" stroke="black" fill="transparent" stroke-width="1" />
                        </svg>
                    </button>
                    <button id="view-four" class="view-active">
                        <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <rect x=1 y=1 width="6" height="16" stroke="black" fill="transparent" stroke-width="1" />
                            <rect x=10 y=1 width="6" height="16" stroke="black" fill="transparent" stroke-width="1" />
                        </svg>
                    </button>
                    <button id="view-three">
                        <svg width="18" height="18" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <rect x=1 y=1 width="16" height="16" stroke="black" fill="transparent" stroke-width="1" />
                        </svg>
                    </button>
                </div>
            <?php } ?>

            <div id="list-produit" class="four-column">

                <?php
                if (empty($products) && empty($architecture_posts)) {
                    echo '<div class="no-products">';
                    if (PLL_current_language() == 'fr') {
                        echo '<p>Aucun résultat trouvé pour "' . esc_html($search) . '".</p>';
                    }
                    if (PLL_current_language() == 'en') {
                        echo '<p>No results found for "' . esc_html($search) . '".</p>';
                    }
                    echo '</div>';
                } else {
                    ?>
                    <?php
                    $displayed_categories = [];
                    foreach ($products as $product) {
                        $category_id = esa_get_category_id();
                        $categories = get_the_terms($product->get_id(), 'product_cat');

                        if (!empty($category_id)) {
                            $visibility = get_post_meta($product->get_id(), 'grid_visibility_' . $category_id, true);
                        } else {
                            $visibility = get_post_meta($product->get_id(), 'grid_visibility', true);
                        }

                        if ($visibility === "0") {
                            continue;
                        }

                        $image1 = wp_get_attachment_image_src($product->get_image_id(), 'large');
                        ?>
                        <div class="produit <?php echo $product->get_id() ?>">
                            <a href="<?php echo $product->get_permalink() ?>">
                                <? $gallery_images_ids = $product->get_gallery_image_ids(); ?>
                                <div class="figure">
                                    <img class="Sirv image-main img-product" src="<?php
                                    echo esc_url($image1[0]);
                                    ?>">
                                    <?php if (!empty($gallery_images_ids)) { ?>
                                        <img class="Sirv image-hover img-product" src="<?php
                                        $image2 = wp_get_attachment_image_src($gallery_images_ids[0], 'large');
                                        echo esc_url($image2[0]);
                                        ?>">
                                    <?php } ?>
                                </div>
                            </a>
                            <div class="line-one">
                                <div class="title">
                                    <span class="name"><?php
                                    $categorie = esa_get_primary_category_link($product->get_id());
                                    echo $categorie;
                                    ?></span> -
                                    <span class="tag"><?php echo $product->get_title(); ?></span>
                                </div>
                                <div class="fav">
                                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist label="" product_id=' . $product->get_id() . ']') ?>
                                </div>
                            </div>
                            <div class="line-two">
                                <div class="more">
                                    <a href="<?php echo $product->get_permalink() ?>">
                                        <? echo (PLL_current_language() == 'en') ? "More information" : "En savoir plus" ?>
                                    </a> &#9679;
                                </div>
                                <div class="price">
                                </div>
                            </div>

                        </div>
                       
                    <?php }
                        foreach ($architecture_posts as $post) {
                            setup_postdata($post);
                            $image = get_the_post_thumbnail_url($post->ID, 'large');
                            ?>
                            <div class="produit architecture <?php echo $post->ID; ?>">
                                <a href="<?php echo get_permalink($post->ID); ?>">
                                    <?php if($image){ ?>
                                        <img class="Sirv image-main img-product" src="<?php echo esc_url($image); ?>">
                                    <?php } ?>
                                </a>
                                <div class="line-one">
                                <a href="<?php echo get_permalink($post->ID); ?>"><span class="name"><?php echo get_the_title($post->ID); ?></span></a>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                } ?>
            </div>
        </div>

        <?php
    }
    
}