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

        // Filter terms matching the string in the name or description
        $matching_terms = array_filter($terms, function ($term) use ($search) {
            return stripos($term->name, $search) !== false;
        });

        return $matching_terms;
    }

    protected function render()
    {
        $search = get_product_search();

        $matching_categories = $this->get_terms_matching_string('product_cat', $search);
        $matching_sortes = $this->get_terms_matching_string('sorte', $search);

        $terms = array_merge($matching_categories, $matching_sortes);

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
                    'post_type' => 'product',
                    'tax_query' => $tax_query
                )
            );
            
            $idsProduct = [];
            while ($the_query->have_posts()):
                $the_query->the_post();
                array_push($idsProduct, get_the_ID());
            endwhile;

            wp_reset_postdata();

            $products = wc_get_products(array(
                'limit' => -1,
                'include' => $idsProduct,
                'status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ));
        } else {
            $products = get_products();
        }
        ?>

        <div class="content">
            <?php
            $query = get_query_var('s');

            if (count($products) > 0) {
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
                if (count(value: $products) == 0) {
                    echo '<div class="no-products">';
                    if (PLL_current_language() == 'fr') {
                        echo 'Pas de produits trouvés';
                    }
                    if (PLL_current_language() == 'en') {
                        echo 'No products found.';
                    }
                    echo '</div>';
                } else {
                    ?>
                    <?php
                    foreach ($products as $product) {
                        $category_id = esa_get_category_id();
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
                } ?>
            </div>
        </div>

        <?php
    }
}