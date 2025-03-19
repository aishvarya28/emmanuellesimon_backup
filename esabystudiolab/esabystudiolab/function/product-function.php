<?php

function get_products()
{

	$category = get_categorie();
	$type = get_sorte();
	$search = get_product_search();

	$category_id = esa_get_category_id();
	$ids = get_products_list_by_custom_order($category_id);
	// print_r($ids);
	if ($category != '') {
		$products = wc_get_products(array(
			'posts_per_page' => 40,
			'status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'category' => array($category),
		));

		usort($products, function ($a, $b) use ($ids) {
			$a_index = array_search($a->get_id(), $ids);
			$b_index = array_search($b->get_id(), $ids);
			return $a_index - $b_index;
		});

		return $products;
	}

	if ($type != '') {
		$the_query = new WP_Query(
			array(
				'posts_per_page' => 40,
				'post_type' => 'product',
				'tax_query' => array(
					array(
						'taxonomy' => 'sorte',
						'field' => 'slug',
						'terms' => array($type),
					)
				),
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

		return $products;

	}

	if ($search != '') {
		$products = wc_get_products(array(
			'limit' => -1,
			'status' => 'publish',
			's' => $search,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			/*'meta_key' => 'order',
																																				   'orderby' => 'meta_value',
																																				   'order' => 'ASC',*/
		));

		// echo '33';

		return $products;
	}


	$products = wc_get_products(array(
		'limit' => -1,
		'status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		/*'meta_key' => 'order',
																								  'orderby' => 'meta_value',
																								  'order' => 'ASC',*/
	));

	usort($products, function ($a, $b) use ($ids) {
		$a_index = array_search($a->get_id(), $ids);
		$b_index = array_search($b->get_id(), $ids);
		return $a_index - $b_index;
	});

	// echo '44';

	return $products;
}

// Recursive function to display terms hierarchically
function esa_display_terms_hierarchically($terms, $parent_id = 0)
{
	echo '<ul>';
	foreach ($terms as $term) {
		if ($term->parent == $parent_id) {
			echo '<li data-slug="' . $term->slug . '">' . esc_html($term->name);
			esa_display_terms_hierarchically($terms, $term->term_id);
			echo '</li>';
		}
	}
	echo '</ul>';
}

/* Sorting products */
add_action('admin_menu', 'esa_products_screen');
function esa_products_screen()
{
	add_menu_page(
		'Products Manager',         // Page title
		'Products Manager',         // Menu title
		'manage_options',           // Capability
		'esa-products-screen',      // Menu slug
		'render_esa_products_screen', // Callback function
		'dashicons-products',       // Icon
		25                          // Position
	);
}

function render_esa_products_screen()
{
	$categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
	$cat = $_REQUEST['category'] ?? '';
	?>
	<div class="wrap">
		<h1>Products Manager</h1>
		<div class="filters">
			<select id="product-category-filter">
				<option value="">All Categories</option>
				<?php foreach ($categories as $category): ?>
					<option <?php echo $cat == $category->term_id ? 'selected' : '' ?> value="<?php echo $category->term_id; ?>">
						<?php echo $category->name; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<button id="filter-products" class="button button-primary">Filter</button>
		</div>

		<?php
		// echo 'esa_order_cat_' . $cat;
		// echo '<pre>';
		// print_r(get_option('esa_order_cat_' . $cat));
		// echo '</pre>';
		$posts = get_products_list_by_custom_order($cat);
		// print_r($posts);
		// echo count($posts);
		?>
		<ul id="products-list">
			<?php
			$args = [
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post__in' => $posts,
				'orderby' => 'post__in',
				'suppress_filters' => true,
			];
			$products = new WP_Query($args);
			if ($products->have_posts()):
				while ($products->have_posts()):
					$products->the_post();
					global $product;

					if ($cat) {
						$visibility = get_post_meta(get_the_ID(), 'grid_visibility_' . $cat, true) != 0 ? 1 : 0;
					} else {
						$visibility = get_post_meta(get_the_ID(), 'grid_visibility', true) != 0 ? 1 : 0;
					}
					?>
					<li class="product-item" data-id="<?php the_ID(); ?>">
						<div class="product-thumbnail"><?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?></div>
						<div class="product-details">
							<div class="product-title"><?php the_title(); ?></div>
							<div class="product-sku"><?php echo get_post_meta($product->get_id(), 'ref', true); ?></div>
							<div class="product-visibility"><input type="checkbox" id="visibilty-<?php the_ID(); ?>" <?php checked($visibility, current: '1') ?> name="visibility-<?php the_ID(); ?>" value="1">
							</div>
						</div>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</ul>
		<button id="save-order" class="button button-primary">Save Order</button>
	</div>
	<?php
}

add_action('wp_ajax_save_product_order_esa', 'save_product_order_esa');
function save_product_order_esa()
{
	if (!current_user_can('manage_options')) {
		wp_send_json_error('Permission Denied');
	}

	if (!isset($_POST['order']) || !is_array($_POST['order'])) {
		wp_send_json_error('Invalid Data');
	}

	if (isset($_POST['category']) && !empty($_POST['category'])) {
		update_option('esa_order_cat_' . $_POST['category'], $_POST['order']);
	} else {
		update_option('esa_order', $_POST['order']);
	}

	$out = [];
	foreach ($_POST['order'] as $key => $post_id) {
		if (isset($_POST['category']) && !empty($_POST['category'])) {
			$out[] = $_POST['visibility'][$key];
			update_post_meta($post_id, 'grid_visibility_' . $_POST['category'], $_POST['visibility'][$key]);
		} else {
			$out[] = $_POST['visibility'][$key];
			update_post_meta($post_id, 'grid_visibility', $_POST['visibility'][$key]);
		}
		// wp_update_post([
		// 	'ID' => (int) $post_id,
		// 	'menu_order' => $menu_order,
		// ]);
	}

	// wp_send_json_success(['esa_order_cat_' . $_POST['category'], $_POST['order']]);
	wp_send_json_success($out);
}

function get_products_list_by_custom_order($category_id = null)
{
	// Determine the option key based on the presence of a category ID
	$option_key = $category_id ? 'esa_order_cat_' . $category_id : 'esa_order';
	$saved_order = get_option($option_key, []);

	// Ensure it's an array (option might be empty or not set)
	if (!is_array($saved_order)) {
		$saved_order = [];
	}

	// Query arguments to fetch products
	$args = [
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		// 'orderby' => 'menu_order',
		// 'order' => 'ASC',
	];

	// If a category ID is provided, add a tax_query for the category
	if ($category_id) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $category_id,
			],
		];
	}

	// Query for products
	$query = new WP_Query($args);
	$products = $query->posts;

	// Build an array of all product IDs from the query
	$all_product_ids = wp_list_pluck($products, 'ID');

	// Filter out product IDs that are already in the saved order
	$missing_product_ids = array_diff($all_product_ids, $saved_order);

	// Combine the saved order with missing product IDs
	$final_order = array_merge($saved_order, $missing_product_ids);

	// Query for the products in the final order
	// $ordered_products_query = new WP_Query([
	//     'post_type'      => 'product',
	//     'posts_per_page' => -1,
	//     'post_status'    => 'publish',
	//     'post__in'       => $final_order,
	//     'orderby'        => 'post__in',
	// ]);

	// // Fetch and display the products
	// $posts = [];
	// if ($ordered_products_query->have_posts()) {
	//     while ($ordered_products_query->have_posts()) {
	//         $ordered_products_query->the_post();
	//         // echo '<h2>' . get_the_title() . '</h2>';
	// 		$posts[] = $ordered_products_query->
	//     }
	//     wp_reset_postdata();
	// }
// print_r($final_order);

	return $final_order;
}

function esa_get_primary_category_link($product_id)
{
	$categories = wp_get_post_terms($product_id, 'product_cat');

	if (!empty($categories) && !is_wp_error($categories)) {
		// Check if Yoast has set a primary category
		$primary_cat_id = get_post_meta($product_id, '_yoast_wpseo_primary_product_cat', true);

		if ($primary_cat_id) {
			// Find the category object by ID
			foreach ($categories as $category) {
				if ($category->term_id == $primary_cat_id) {
					return '<a href="'.get_term_link($category).'">'. $category->name.'</a>';
				}
			}
		} else {
			return '<a href="'.get_term_link($categories[0]).'">'. $categories[0]->name.'</a>';
		}
	}
}