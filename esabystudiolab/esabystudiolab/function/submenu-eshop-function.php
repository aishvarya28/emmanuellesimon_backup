<?php
add_action( 'wp_ajax_submenu_eshop', 'submenu_eshop' );
add_action( 'wp_ajax_nopriv_submenu_eshop', 'submenu_eshop' );

function submenu_eshop(){

    if (PLL_current_language() == 'fr'){
        $array_of_picto = get_posts([
            'title' => 'Collections',
            'post_type' => 'any',
        ]);
    }
    if(PLL_current_language() == 'en') {
        $array_of_picto = get_posts([
            'title' => 'Collections',
            'post_type' => 'any',
        ]);
    }

    $picto_permalink = '';
    
    if (count($array_of_picto) > 0){
        $picto = $array_of_picto[0];//Be sure you have an array with single post or page 
        $picto_permalink = get_permalink($picto->ID);
    }

    switch ($_POST['level']) {
        case 'one':
            $collection = get_page_by_title('Collections');
            $collectionPermalink = get_permalink($collection->ID); 

            $htmlLevelOne = "";
            $htmlLevelOne .= '<ul class="list uppercase"> <li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'produit') ? 'active' : '';
            $htmlLevelOne .= '" data-level="one" data-why="produit" data-id="" data-action="submenu_eshop" onmouseover="clickHeader(this)" onmousedown="clickHeader(this)" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('submenu_eshop');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .= admin_url( 'admin-ajax.php' );
            $htmlLevelOne .= '" data-lang="';
            $htmlLevelOne .= $_POST['lang'];
            $htmlLevelOne .=  '">';
            if($_POST['lang'] == 'en') {
                $htmlLevelOne .=  'Products';
            }else {
                $htmlLevelOne .=  'Produits';
            }
            $htmlLevelOne .=  '</button></li>';
            $htmlLevelOne .= '<li><button class="header-list ';
            $htmlLevelOne .= ($_POST['why'] == 'collection') ? 'active' : '';
            $htmlLevelOne .= '" onmouseover="clickHeader(this)" onmousedown="clickHeader(this)" data-level="one" data-why="collection" data-id="" data-action="submenu_eshop" data-nonce="';
            $htmlLevelOne .= wp_create_nonce('submenu_eshop');
            $htmlLevelOne .= '" data-ajaxurl="';
            $htmlLevelOne .=  admin_url( 'admin-ajax.php' );
            $htmlLevelOne .= '" data-lang="';
            $htmlLevelOne .= $_POST['lang'];
            $htmlLevelOne .=  '">Collections</button></li></ul>';

            $htmlLevelTwo = "";
            $htmlLevelTwo .= '<ul class="list uppercase"> <li><a href="';
            if($_POST['lang'] == 'en') {
                $id_shop = wc_get_page_id( 'shop' );
                $htmlLevelTwo .= ($_POST['why'] == 'collection') ? $picto_permalink :  get_permalink(pll_get_post( $id_shop , 'en' ));
                $htmlLevelTwo .= '">All</a>';
            }else {
                $htmlLevelTwo .= ($_POST['why'] == 'collection') ? $picto_permalink : wc_get_page_permalink('shop');
                $htmlLevelTwo .= '">Tout</a>';
            }
    
            switch ($_POST['why']) {
                case 'collection':
                    $args = array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
                    );
                    
                    $product_categories = get_terms($args);
                    
                    if (!is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $htmlLevelTwo .= '<li><button class="header-list" data-level="two" data-why="collection" onmousedown="clickHeader(this)" onmouseover="clickHeader(this)" data-lang="';
                            $htmlLevelTwo .= $_POST['lang'];
                            $htmlLevelTwo .= '" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop');
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
                            $htmlLevelTwo .= '<li><button class="header-list" onmousedown="clickHeader(this)" onmouseover="clickHeader(this)" data-level="two" data-why="produit" data-lang="';
                            $htmlLevelTwo .= $_POST['lang'];
                            $htmlLevelTwo .='" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop');
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
            if($_POST['lang'] == 'en') {
                $id_shop = wc_get_page_id( 'shop' );
                $htmlLevelTwo .= get_permalink(pll_get_post( $id_shop , 'en' ));
                $htmlLevelTwo .= '">All</a>';
            }else {
                $htmlLevelTwo .= wc_get_page_permalink('shop');
                $htmlLevelTwo .= '">Tout</a>';
            }
            
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
                            $htmlLevelTwo .= '" onmousedown="clickHeader(this)" onmouseover="clickHeader(this)" data-lang="';
                            $htmlLevelTwo .= $_POST['lang'];
                            $htmlLevelTwo .= '" data-level="two" data-why="collection" data-id="';
                            $htmlLevelTwo .= $category->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop');
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

                    if($_POST['lang'] == 'en') {
                        $htmlLevelThree .= '">All</a>';
                    }else { 
                        $htmlLevelThree .= '">Tout</a>';
                    }

                    $products = wc_get_products( array(
                        'status' => 'publish',
                        'category' => array($_POST['id']),
                        'limit' => -1,
                        /*'meta_key' => 'order',
                        'orderby' => 'meta_value',
                        'order' => 'ASC',*/
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
                            $htmlLevelTwo .= '" onmousedown="clickHeader(this)" onmouseover="clickHeader(this)" data-lang="';
                            $htmlLevelTwo .= $_POST['lang'];
                            $htmlLevelTwo .= '" data-level="two" data-why="produit" data-id="';
                            $htmlLevelTwo .= $sorte->slug;
                            $htmlLevelTwo .= '" data-action="submenu_eshop" data-nonce="';
                            $htmlLevelTwo .= wp_create_nonce('submenu_eshop');
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

                    if($_POST['lang'] == 'en') {
                        $id_shop = wc_get_page_id( 'shop' );
                        $shop_url = get_permalink(pll_get_post( $id_shop , 'en' ));
                    }
                    
                    $shop_url .= '?sorte=';
                    $shop_url .= $_POST['id'];
        
                    $htmlLevelThree = "";
                    $htmlLevelThree .= '<ul class="list"> <li><a href="';
                    $htmlLevelThree .= $shop_url;

                    if($_POST['lang'] == 'en') {
                        $htmlLevelThree .= '">All</a>';
                    }else { 
                        $htmlLevelThree .= '">Tout</a>';
                    }

                    $term = get_term_by('slug', $_POST['id'] , 'sorte');

                    $args = array(
                        'taxonomy'   => 'sorte',
                        'parent' => $term->term_id
                    );
                    
                    $product_sortes_two = get_terms($args);
                    
                    if (!is_wp_error($product_sortes_two)) {
                        foreach ($product_sortes_two as $sorte) {

                            $sorte_url = wc_get_page_permalink('shop');
                            
                            if($_POST['lang'] == 'en') {
                                $id_shop = wc_get_page_id( 'shop' );
                                $sorte_url = get_permalink(pll_get_post( $id_shop , 'en' ));
                            }
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