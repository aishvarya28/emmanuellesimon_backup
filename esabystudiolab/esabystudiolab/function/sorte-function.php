<?php

function get_html_types($filter = null, $types = [], $categorie= ''){

	$html_type = "";

	if(count($types) > 1){
		if ($filter == null){
			$html_type .='<div class="filtre">';
			$html_type .= '<button class="filter-type filtre-selected" onmousedown="clickfiltre(this)" data-id="" data-slug="" data-categorie="';
			$html_type .= $categorie;
			$html_type .='" data-nonce="';
			$html_type .= wp_create_nonce('filter_type');
			$html_type .= '"data-action="filter_type" data-ajaxurl="';
			$html_type .= admin_url( 'admin-ajax.php' );
			if(PLL_current_language() == 'en') {
				$html_type .= '"> All </button>';
			}else{
				$html_type .= '"> Tout </button>';
			}
			foreach($types as $type) { 
				$html_type .='<button class="filter-type" onmousedown="clickfiltre(this)" data-id="';
				$html_type .= $type->term_id;
				$html_type .= '" data-slug="';
				$html_type .= $type->slug;
				$html_type .= '" data-categorie="';
				$html_type .= $categorie;
				$html_type .= '" data-nonce="';
				$html_type .= wp_create_nonce('filter_type');
				$html_type .= '"data-action="filter_type" data-ajaxurl="';
				$html_type .= admin_url( 'admin-ajax.php' );
				$html_type .= '">';
				$html_type .= $type->name;
				$html_type .= '</button>';
			}
			$html_type .='</div>';
			
		}else {
	
		$term = get_term_by('slug', $filter , 'sorte');
	
		if ($term->parent == 0) {
	
			$html_type .='<div class="filtre">';
			$html_type .= '<button class="filter-type" onmousedown="clickfiltre(this)" data-id="" data-slug="" data-categorie="';
			$html_type .= $categorie;
			$html_type .='" data-nonce="';
			$html_type .= wp_create_nonce('filter_type');
			$html_type .= '"data-action="filter_type" data-ajaxurl="';
			$html_type .= admin_url( 'admin-ajax.php' );
			if(PLL_current_language() == 'en') {
				$html_type .= '"> All </button>';
			}else{
				$html_type .= '"> Tout </button>';
			}
			foreach($types as $type) { 
				if ($type->term_id == $term->term_id){
					$html_type .='<button class="filter-type filtre-selected" onmousedown="clickfiltre(this)" data-id="';
				}else{
					$html_type .='<button class="filter-type" onmousedown="clickfiltre(this)" data-id="';
				}
				$html_type .= $type->term_id;
				$html_type .= '" data-slug="';
				$html_type .= $type->slug;
				$html_type .= '" data-categorie="';
				$html_type .= $categorie;
				$html_type .= '" data-nonce="';
				$html_type .= wp_create_nonce('filter_type');
				$html_type .= '"data-action="filter_type" data-ajaxurl="';
				$html_type .= admin_url( 'admin-ajax.php' );
				$html_type .= '">';
				$html_type .= $type->name;
				$html_type .= '</button>';
			}
	
			$html_type .='</div><div class="filtre" id="second-filtre">';
	
			$typesSecondaire = get_terms( array( 
				'taxonomy' => 'sorte',
				'parent'   => $term->term_id,
			));

			if ($categorie != ''){
				$taxonomies_list = []; 
				$query = new WP_Query( array(
					'post_type' => 'product',
					'tax_query' => array(
						array (						
							'taxonomy' => 'product_cat',
							'field' => 'slug',
							'terms' => array($categorie),
						)
					),
				));
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
				
						$product_id = get_the_ID();
			
						$product_sortes = get_the_terms($product_id, 'sorte');
				
						if (!is_wp_error($product_sortes) && !empty($product_sortes)) {
							foreach ($product_sortes as $product_sorte) {
								if (!array_key_exists($product_sorte->term_id, $taxonomies_list)) {
									array_push($taxonomies_list, $product_sorte->term_id);
								}
							}
						}
					}
				
					wp_reset_postdata(); 
				}
		
		
				if (count($taxonomies_list) > 0){
					$typesSecondaire = get_terms( array( 
						'taxonomy' => 'sorte',
						'include' => $taxonomies_list,
						'parent'   => $term->term_id,
					) );
				}
			}
			if (count($typesSecondaire) > 1){
				foreach($typesSecondaire as $type) { 
					$html_type .='<button class="filter-type" onmousedown="clickfiltre(this)" data-id="';
					$html_type .= $type->term_id;
					$html_type .= '" data-slug="';
					$html_type .= $type->slug;
					$html_type .= '" data-categorie="';
					$html_type .= $categorie;
					$html_type .= '" data-nonce="';
					$html_type .= wp_create_nonce('filter_type');
					$html_type .= '"data-action="filter_type" data-ajaxurl="';
					$html_type .= admin_url( 'admin-ajax.php' );
					$html_type .= '">';
					$html_type .= $type->name;
					$html_type .= '</button>';
				}
			}
			$html_type .='</div>';
	
		} else {
	
			$html_type .='<div class="filtre">';
			$html_type .= '<button class="filter-type" onmousedown="clickfiltre(this)" data-id="" data-slug="" data-categorie="';
			$html_type .= $categorie;
			$html_type .='" data-nonce="';
			$html_type .= wp_create_nonce('filter_type');
			$html_type .= '"data-action="filter_type" data-ajaxurl="';
			$html_type .= admin_url( 'admin-ajax.php' );
			if(PLL_current_language() == 'en') {
				$html_type .= '"> All </button>';
			}else{
				$html_type .= '"> Tout </button>';
			}
			foreach($types as $type) { 
				if ($type->term_id == $term->parent){
					$html_type .='<button class="filter-type filtre-selected" onmousedown="clickfiltre(this)" data-id="';
				}else{
					$html_type .='<button class="filter-type" onmousedown="clickfiltre(this)" data-id="';
				}
				$html_type .= $type->term_id;
				$html_type .= '" data-slug="';
				$html_type .= $type->slug;
				$html_type .= '" data-categorie="';
				$html_type .= $categorie;
				$html_type .= '" data-nonce="';
				$html_type .= wp_create_nonce('filter_type');
				$html_type .= '"data-action="filter_type" data-ajaxurl="';
				$html_type .= admin_url( 'admin-ajax.php' );
				$html_type .= '">';
				$html_type .= $type->name;
				$html_type .= '</button>';
			}
	
			$html_type .='</div><div class="filtre" id="second-filtre">';
	
			$typesSecondaire = get_terms( array( 
				'taxonomy' => 'sorte',
				'parent'   => $term->parent,
			));

			if ($categorie != ''){
				$taxonomies_list = []; 
				$query = new WP_Query( array(
					'post_type' => 'product',
					'tax_query' => array(
						array (						
							'taxonomy' => 'product_cat',
							'field' => 'slug',
							'terms' => array($categorie),
						)
					),
				));
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
				
						$product_id = get_the_ID();
			
						$product_sortes = get_the_terms($product_id, 'sorte');
				
						if (!is_wp_error($product_sortes) && !empty($product_sortes)) {
							foreach ($product_sortes as $product_sorte) {
								if (!array_key_exists($product_sorte->term_id, $taxonomies_list)) {
									array_push($taxonomies_list, $product_sorte->term_id);
								}
							}
						}
					}
				
					wp_reset_postdata(); 
				}
		
		
				if (count($taxonomies_list) > 0){
					$typesSecondaire = get_terms( array( 
						'taxonomy' => 'sorte',
						'include' => $taxonomies_list,
						'parent'   => $term->parent,
					) );
				}
			}

			if (count($typesSecondaire) > 1){
				foreach($typesSecondaire as $type) { 
					if ($type->term_id == $term->term_id){
						$html_type .='<button class="filter-type filtre-selected" onmousedown="clickfiltre(this)" data-id="';
					}else{
						$html_type .='<button class="filter-type" onmousedown="clickfiltre(this)" data-id="';
					}
					$html_type .= $type->term_id;
					$html_type .= '" data-slug="';
					$html_type .= $type->slug;
					$html_type .= '" data-categorie="';
					$html_type .= $categorie;
					$html_type .= '" data-nonce="';
					$html_type .= wp_create_nonce('filter_type');
					$html_type .= '"data-action="filter_type" data-ajaxurl="';
					$html_type .= admin_url( 'admin-ajax.php' );
					$html_type .= '">';
					$html_type .= $type->name;
					$html_type .= '</button>';
				}
			}
			$html_type .='</div>';
	
		}
	
		}
	}

	return $html_type;
	
} 

function get_types(){
	
    $category = get_categorie();
	$type = get_sorte();

    if ($category != ''){
        $taxonomies_list = []; 
		$query = new WP_Query( array(
			'post_type' => 'product',
			'tax_query' => array(
				array (						
                    'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array($category),
				)
			),
			'posts_per_page' => -1
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

			// echo '<pre>';
			// print_r($taxonomies_list);
		
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

		$types = get_html_types(null, $types, $category);

        return $types;
    }

	if($type != ''){
		
		$types = get_terms( array( 
			'taxonomy' => 'sorte',
			'parent'   => 0
		) );
			
		$types = get_html_types($type, $types);

        return $types;
    }
    
    $types = get_terms( array( 
        'taxonomy' => 'sorte',
        'parent'   => 0
    ) );

    $types = get_html_types(null, $types);

	return $types;
}
?>