<?php get_header(); ?>

<main id="content" class="site-main page type-page status-publish hentry">

    <div class="page-header">
        <h1 class="entry-title">
            <?php
            printf(
                /* translators: %s: search query */
                __('Search results for: %s', 'hello-elementor'),
                '<span>' . esc_html(get_search_query()) . '</span>'
            );
            ?>
        </h1>
    </div>

    <div id="esa-search-results" class="page-content">
        <!-- Display Taxonomy Terms -->
        <?php
        $results = esa_search_taxonomy_terms(get_search_query());
        if (have_posts()):
            while (have_posts()):
                the_post();
                global $post;

                $results[] = $post;
            endwhile;
        endif;
        ?>

        <!-- Display Posts -->
        <?php if (!empty($results)): ?>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li>
                        <?php
                        if (is_a($result, 'WP_Post')) {
                            $name = ucfirst($result->post_type).": ";

                            if ($result->post_type == 'product') {
                                $product_categories = wp_get_post_terms($result->ID, 'product_cat');

                                if (!is_wp_error($product_categories) && !empty($product_categories)) {
                                    foreach ($product_categories as $category) {
                                        $name .= '<span>' . esc_html($category->name) . '</span> - ';
                                    }
                                }
                            }

                            $link = get_permalink($result);
                            $title = get_the_title($result);
                        } else {
                            if ($result->taxonomy == 'product_cat') {
                                $name = 'Collection: ';
                            } elseif ($result->taxonomy == 'sorte') {
                                $name = 'Category: ';
                            } else {
                                $name = ucfirst($result->taxonomy).": ";
                            }

                            $link = get_term_link($result);
                            $title = $result->name;
                        }

                        printf(
                            __($name, 'hello-elementor')
                        );
                        ?>
                        <a href="<?php echo $link ?>"><?php echo $title ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>
                <?php
                printf(
                    /* translators: %s: search query */
                    __('No results found.', 'hello-elementor')
                );
                ?>
            </p>
        <?php endif; ?>
    </div>

    <style>
        #esa-search-results ul {
            padding: 0 0 0 20px;
            margin-bottom: 20px;
        }

        #esa-search-results ul li {
            font-size: 16px;
            margin-bottom: 5px;
        }

        #esa-search-results ul li a {
            font-size: 16px;
        }
    </style>

</main>

<?php get_footer(); ?>