<?php
add_action( 'wp_ajax_header_list', 'header_list' );
add_action( 'wp_ajax_nopriv_header_list', 'header_list' );

function header_list(){

    switch ($_POST['level']) {
        case 'one':
            $collection = get_page_by_title('Collections');
            $collectionPermalink = get_permalink($collection->ID); 
            $htmlLevelOne = "";
            $htmlLevelOne .= '<ul class="list uppercase"> <li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'produit') ? 'active' : '';
            $htmlLevelOne .= '" data-level="one" data-why="produit" data-id="" data-action="header_list" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('header_list');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .= admin_url( 'admin-ajax.php' );
            if (PLL_current_language() == 'en'){
                $htmlLevelOne .=  '">Products</button></li>';
            }else {
                $htmlLevelOne .=  '">Produits</button></li>';
            }
            
            $htmlLevelOne .= '<li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'collection') ? 'active' : '';
            $htmlLevelOne .= '" onmousedown="clickHeader(this)" data-level="one" data-why="collection" data-id="" data-action="header_list" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('header_list');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .=  admin_url( 'admin-ajax.php' );
            $htmlLevelOne .=  '">Collection s</button></li></ul>';
            
            $htmlLevelTwo = "";
            $htmlLevelTwo .= '<ul class="list uppercase"> <li><a href="';
            $htmlLevelTwo .= ($_POST['why'] == 'collection') ? '' : wc_get_page_permalink('shop');
            $htmlLevelTwo .= '">Tout</a>';
            switch ($_POST['why']) {
                case 'collection':
                    $args = array(
                        'no_found_rows' => true,
	                    'update_post_meta_cache' => false,
	                    'update_post_term_cache' => false,
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
                    );
                    
                    $product_categories = get_terms($args);
                    
                    if (!is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $htmlLevelTwo .= '<li><button class="header-list" data-level="two" data-why="collection" onmousedown="clickHeader(this)" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="header_list" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('header_list');
                            $htmlLevelTwo .= '" data-ajaxurl="';
                            $htmlLevelTwo .=  admin_url( 'admin-ajax.php' );
                            $htmlLevelTwo .=  '">';
                            $htmlLevelTwo .= $category->name;
                            $htmlLevelTwo .= '</button></li>';
                        }
                    }
                    break;
                case 'produit':
                    $args = array(
                        'taxonomy'   => 'sorte',
                        'parent' => '0',
                        'no_found_rows' => true,
	                    'update_post_meta_cache' => false,
	                    'update_post_term_cache' => false,
                    );
                    
                    $product_sortes = get_terms($args);
                    
                    if (!is_wp_error($product_sortes)) {
                        foreach ($product_sortes as $sorte) {
                            $htmlLevelTwo .= '<li><button class="header-list" onmousedown="clickHeader(this)" data-level="two" data-why="produit" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="header_list" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('header_list');
                            $htmlLevelTwo .= '" data-ajaxurl="';
                            $htmlLevelTwo .=  admin_url( 'admin-ajax.php' );
                            $htmlLevelTwo .=  '">';
                            $htmlLevelTwo .= $sorte->name;
                            $htmlLevelTwo .= '</button></li>';
                        }
                    }
                    break;
            }

            $htmlLevelTwo .= '</ul>';
            $html=array("levelOne" => $htmlLevelOne, "levelTwo" => $htmlLevelTwo);
	        return wp_send_json_success( $html );
            
        case 'two':
            $htmlLevelTwo = "";
            $htmlLevelTwo .= '<ul class="list uppercase"> <li><a href="';
            $htmlLevelTwo .= wc_get_page_permalink('shop');
            $htmlLevelTwo .= '">Tout</a>';
            switch ($_POST['why']) {
                case 'collection':
                    $args = array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
                        'no_found_rows' => true,
	                    'update_post_meta_cache' => false,
	                    'update_post_term_cache' => false,
                    );
                    
                    $product_categories = get_terms($args);
                    
                    if (!is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $htmlLevelTwo .= '<li><button class="header-list ';
                            $htmlLevelTwo .= ($_POST['id'] == $category->slug) ? 'active' : '';
                            $htmlLevelTwo .= '" onmousedown="clickHeader(this)" data-level="two" data-why="collection" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="header_list" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('header_list');
                            $htmlLevelTwo .= '" data-ajaxurl="';
                            $htmlLevelTwo .=  admin_url( 'admin-ajax.php' );
                            $htmlLevelTwo .=  '">';
                            $htmlLevelTwo .= $category->name;
                            $htmlLevelTwo .= '</button></li>';
                        }
                    }

                    $htmlLevelTwo .= '</ul>';

                    $product_category_slug = $_POST['id']; 
                    $category_url = get_term_link($product_category_slug, 'product_cat');
        
                    $htmlLevelThree = "";
                    $htmlLevelThree .= '<ul class="list"> <li><a href="';
                    $htmlLevelThree .= $category_url;
                    $htmlLevelThree .= '">Tout</a>';

                    $products = wc_get_products( array(
                        'status' => 'publish',
                        'category' => array($_POST['id'])
                    ));

                    foreach($products as $product) {
                        $htmlLevelThree .= '<li><a href="';
                        $htmlLevelThree .= $product->get_permalink();
                        $htmlLevelThree .=  '">';
                        $htmlLevelThree .= $product->get_title();
                        $htmlLevelThree .= '</a></li>';
                    }

                    $htmlLevelThree .= '</ul>';

                    break;
                case 'produit':
                    $args = array(
                        'taxonomy'   => 'sorte',
                        'parent' => '0',
                        'no_found_rows' => true,
	                    'update_post_meta_cache' => false,
	                    'update_post_term_cache' => false,
                    );
                    
                    $product_sortes = get_terms($args);
                    
                    if (!is_wp_error($product_sortes)) {
                        foreach ($product_sortes as $sorte) {
                            $htmlLevelTwo .= '<li><button class="header-list ';
                            $htmlLevelTwo .= ($_POST['id'] == $sorte->slug) ? 'active' : '';
                            $htmlLevelTwo .= '" onmousedown="clickHeader(this)" data-level="two" data-why="produit" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="header_list" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('header_list');
                            $htmlLevelTwo .= '" data-ajaxurl="';
                            $htmlLevelTwo .=  admin_url( 'admin-ajax.php' );
                            $htmlLevelTwo .=  '">';
                            $htmlLevelTwo .= $sorte->name;
                            $htmlLevelTwo .= '</button></li>';
                        }
                    }

                    $htmlLevelTwo .= '</ul>';

                    $htmlLevelThree = "";

                    $shop_url = wc_get_page_permalink('shop');
                    $shop_url .= '?sorte=';
                    $shop_url .= $_POST['id'];
        
                    $htmlLevelThree = "";
                    $htmlLevelThree .= '<ul class="list"> <li><a href="';
                    $htmlLevelThree .= $shop_url;
                    $htmlLevelThree .= '">Tout</a>';

                    $term = get_term_by('slug', $_POST['id'] , 'sorte');

                    $args = array(
                        'taxonomy'   => 'sorte',
                        'parent' => $term->term_id,
                        'no_found_rows' => true,
	                    'update_post_meta_cache' => false,
	                    'update_post_term_cache' => false,
                    );
                    
                    $product_sortes_two = get_terms($args);
                    
                    if (!is_wp_error($product_sortes_two)) {
                        foreach ($product_sortes_two as $sorte) {

                            $sorte_url = wc_get_page_permalink('shop');
                            $sorte_url .= '?sorte=';
                            $sorte_url .= $sorte->slug;

                            $htmlLevelThree .= '<li><a href="';
                            $htmlLevelThree .= $sorte_url;
                            $htmlLevelThree .=  '">';
                            $htmlLevelThree .= $sorte->name;
                            $htmlLevelThree .= '</a></li>';
                        }
                    }

                    $htmlLevelThree .= '</ul>';

                    break;
            }

            $html=array("levelTwo" => $htmlLevelTwo, "levelThree" => $htmlLevelThree);
	        return wp_send_json_success( $html );
            
    }
    
    $html=array("message" => "OK");
	return wp_send_json_success( $html );

	
}

?>