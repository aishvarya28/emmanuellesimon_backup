<?php
add_action( 'wp_ajax_filter_type', 'filter_type' );
add_action( 'wp_ajax_nopriv_filter_type', 'filter_type' );

function filter_type(){

	$category = $_POST['categorie'];

	$types = get_terms( array( 
		'taxonomy' => 'sorte',
		'parent'   => 0,
	));
	
	if ($category != ''){
        $taxonomies_list = []; 
		$query = new WP_Query( array(
			'post_type' => 'product',
			'posts_per_page' => 50,
			'no_found_rows' => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query' => array(
				array (						
                    'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array($category),
				)
			),
		));
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
		
				$product_id = get_the_ID();
	
				$terms = get_the_terms($product_id, 'sorte');
		
				if (!is_wp_error($terms) && !empty($terms)) {
					foreach ($terms as $term) {
						if (!array_key_exists($term->term_id, $taxonomies_list)) {
							array_push($taxonomies_list, $term->term_id);
						}
					}
				}
			}
		
			wp_reset_postdata(); 
		}

		$types = [];

		if (count($taxonomies_list) > 0){
			$types = get_terms( array( 
				'taxonomy' => 'sorte',
				'include' => $taxonomies_list,
				'parent'   => 0
			) );
		}
    }

	
	$html_type = get_html_types($_POST['slug'], $types, $category);

	if ($category != ''){ 
		$tax_query = array(
			array (
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => array($_POST['categorie'])
			)
		);
		if ($_POST['id'] != ''){
			array_push($tax_query, array (
				'taxonomy' => 'sorte',
				'field' => 'term_id',
				'terms' => array($_POST['id']),
			));
		}
	$the_query = new WP_Query( array(
		'posts_per_page' => 50,
		'post_type' => 'product',
		'tax_query' => $tax_query,
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		

	) );

	}else {
		$the_query = new WP_Query( array(
			'posts_per_page' => 50,
			'post_type' => 'product',
			'no_found_rows' => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query' => array(
				array (
					'taxonomy' => 'sorte',
					'field' => 'term_id',
					'terms' => array($_POST['id']),
				),
			),
	
		) );
	}
	$idsProduct = [];
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		array_push($idsProduct, get_the_ID());
	endwhile;
	wp_reset_postdata();

	$products = wc_get_products(array(
		'limit' => -1,
		'include' => $idsProduct,
		'status' => 'publish',
		'orderby' => 'name',
        'order' => 'ASC',
	));

	if($category == '' && $_POST['id'] == ''){
		$products = wc_get_products(array(
			'limit' => -1,
			'status' => 'publish',
			'orderby' => 'name',
			'order' => 'ASC',	
		));
	}
	

	$html_products = "";
	
	foreach($products as $product) { 
		$gallery_images_ids = $product-> get_gallery_image_ids();
		$image1 = wp_get_attachment_image_src( $product->get_image_id(), 'large' );
		$image2 = !empty($gallery_images_ids) ? wp_get_attachment_image_src( $gallery_images_ids[0], 'large' ) : '';

		$html_products .='<div class="produit"><a href="';
		$html_products .= $product->get_permalink();
		$html_products .= '"><div class="figure"><img class="Sirv image-main img-product" src="';
		$html_products .= esc_url( $image1[0] );
		$html_products .= '"/>';
		if (!empty($image2) && !empty($image2[0])) {
			$html_products .= '<img class="Sirv image-hover img-product" src="';
			$html_products .= esc_url( $image2[0] );
			$html_products .= '"/>';
		}
		$html_products .='</div></a><div class="line-one"> <div class="title"><span class="name">';
		$categorie = esa_get_primary_category_link($product->get_id());
		$html_products .= $categorie;
		$html_products .= '</span> - <span class="tag">';
		$html_products .= $product->get_title(); 
		$html_products .= '</span> </div> <div class="fav">';
		$html_products .=  '<a href="';
		$html_products .= esc_url( wp_nonce_url( add_query_arg( 'add_to_wishlist', $product->get_id(), $base_url ), 'add_to_wishlist' ) );
		$html_products .= 'class="add_to_wishlist single_add_to_wishlist button alt" data-product-id="';
		$html_products .=  $product->get_id();
		$html_products .= '" data-product-type="simple" data-original-product-id="'; 
		$html_products .= $product->get_id(); 
		$html_products .= '" data-title="';
		$html_products .= esc_attr( apply_filters( 'yith_wcwl_add_to_wishlist_title', $label ) );
		$html_products .= ' " rel="nofollow" > <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M9.24901 1C7.7299 1 6.50197 2.20132 6.50197 3.68921C6.50197 2.20132 5.26911 1 3.75099 1C2.23286 1 1 2.20132 1 3.68921C1 3.78667 1.0079 3.88316 1.01481 3.98061C1.18656 6.66211 3.67794 9.24035 6.50197 12C9.32601 9.23939 11.8134 6.66211 11.9852 3.98061C11.9931 3.88316 12 3.78667 12 3.68921C12 2.20132 10.7711 1 9.24901 1Z" stroke="black" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"/></svg> </a></div> </div> <div class="line-two"> <div class="more"> <a href="'; 
		$html_products .= $product->get_permalink();
		if (PLL_current_language() == 'en'){
			$html_products .= '">More information</a> &#9679; </div> <div class="price">Price on request</div></div></div>';
		}else {
			$html_products .= '">En savoir plus</a> &#9679; </div> <div class="price"></div></div></div>';
		}
	}
	
	
	$html=array("filter" => $html_type, "listProduct" => $html_products);
	wp_send_json_success( $html );
}


?>