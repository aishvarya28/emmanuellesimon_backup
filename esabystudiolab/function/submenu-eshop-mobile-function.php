<?php
add_action( 'wp_ajax_submenu_eshop_mobile', 'submenu_eshop_mobile' );
add_action( 'wp_ajax_nopriv_submenu_eshop_mobile', 'submenu_eshop_mobile' );

function submenu_eshop_mobile(){

    switch ($_POST['level']) {
        case 'one':
            $collection = get_page_by_title('Collections');
            $collectionPermalink = get_permalink($collection->ID); 
            $htmlLevelOne = "";
            $htmlLevelOne .= '<ul class="list uppercase"><li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'produit') ? 'active' : '';
            $htmlLevelOne .= '" onmousedown="clickEshopMobile(this)" data-level="one" data-why="produit" data-id="" data-action="submenu_eshop_mobile" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('submenu_eshop_mobile');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .= admin_url( 'admin-ajax.php' );
            $htmlLevelOne .=  '">Produits</button></li>';
            $htmlLevelOne .= '<li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'collection') ? 'active' : '';
            $htmlLevelOne .= '" onmousedown="clickEshopMobile(this)" data-level="one" data-why="collection" data-id="" data-action="submenu_eshop_mobile" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('submenu_eshop_mobile');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .=  admin_url( 'admin-ajax.php' );
            $htmlLevelOne .=  '">Collections</button></li></ul>';
            $htmlLevelTwo = "";
            $htmlLevelTwo .= '<ul class="list uppercase"> <li><a href="';
            $htmlLevelTwo .= ($_POST['why'] == 'collection') ? '' : wc_get_page_permalink('shop');
            $htmlLevelTwo .= '">Tout</a>';
            switch ($_POST['why']) {
                case 'collection':
                    $args = array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
                    );
                    
                    $product_categories = get_terms($args);
                    
                    if (!is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $htmlLevelTwo .= '<li><button class="header-list" data-level="two" data-why="collection" onmousedown="clickEshopMobile(this)" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop_mobile" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop_mobile');
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
                        'parent' => '0'
                    );
                    
                    $product_sortes = get_terms($args);
                    
                    if (!is_wp_error($product_sortes)) {
                        foreach ($product_sortes as $sorte) {
                            $htmlLevelTwo .= '<li><button class="header-list" onmousedown="clickEshopMobile(this)" data-level="two" data-why="produit" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop_mobile" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop_mobile');
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
                    );
                    
                    $product_categories = get_terms($args);
                    
                    if (!is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $htmlLevelTwo .= '<li><button class="header-list ';
                            $htmlLevelTwo .= ($_POST['id'] == $category->slug) ? 'active' : '';
                            $htmlLevelTwo .= '" onmousedown="clickEshopMobile(this)" data-level="two" data-why="collection" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop_mobile" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop_mobile');
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
                        'parent' => '0'
                    );
                    
                    $product_sortes = get_terms($args);
                    
                    if (!is_wp_error($product_sortes)) {
                        foreach ($product_sortes as $sorte) {
                            $htmlLevelTwo .= '<li><button class="header-list ';
                            $htmlLevelTwo .= ($_POST['id'] == $sorte->slug) ? 'active' : '';
                            $htmlLevelTwo .= '" onmousedown="clickEshopMobile(this)" data-level="two" data-why="produit" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop_mobile" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop_mobile');
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
                        'parent' => $term->term_id
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