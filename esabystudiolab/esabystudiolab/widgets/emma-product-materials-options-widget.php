<?php
class Elementor_Emma_Product_Materials_Options_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'emma_product_materials_options';
	}

	public function get_title()
	{
		return esc_html__('Emma Product Materials Options', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaProductMaterialsOptions'];
	}

	public function get_script_depends()
	{
		return ['emmaProductMaterialsOptions'];
	}

	public function get_icon()
	{
		return 'eicon-product-stock';
	}

	public function get_categories()
	{
		return ['studioslab'];
	}

	protected function register_controls()
	{

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__('Emma Product Materials Options', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Product Materials Options', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]

		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render()
	{
		echo '<div id="product-materials-option-wrapper">';

		$product_id = get_the_ID();
		$material_1_options = get_post_meta($product_id, 'material_1_options', true);
		$material_1_ids = $material_1_options ? explode(",", $material_1_options) : [];
		$material_2_options = get_post_meta($product_id, 'material_2_options', true);
		$material_2_ids = $material_2_options ? explode(",", $material_2_options) : [];

		if (
			(count($material_1_ids) > 0 && count($material_2_ids) == 0) || 
			(count($material_1_ids) == 0 && count($material_2_ids) > 0)
		) {
			if (count($material_1_ids) > 0) {
				foreach ($material_1_ids as $material_1_id) {
					$product = wc_get_product($material_1_id);
					
					if ($product) {
						$material_1_image = get_post_meta($material_1_id, 'material_1_image', true);
						$attachment = get_post($material_1_image);

						if ($material_1_id != $product_id) {
							echo '<a href="' . esc_url(get_permalink($material_1_id)) . '">';
						}
						
						echo '<div class="product-option">';
						echo wp_get_attachment_image($material_1_image, 'thumbnail', false, array(
							'width' => 60,
							'height' => 60,
							'class' => 'round-image'
						));
						echo '<span>' . $attachment->post_title . '</span></div>';

						if ($material_1_id != $product_id) {
							echo '</a>';
						}
					}
				}
			}

			if (count($material_2_ids) > 0) {
				foreach ($material_2_ids as $material_2_id) {
					$product = wc_get_product($material_2_id);
					if ($product) {
						$material_2_image = get_post_meta($material_2_id, 'material_2_image', true);
						$attachment = get_post($material_2_image);

						if ($material_2_id != $product_id) {
							echo '<a href="' . esc_url(get_permalink($material_2_id)) . '">';
						}

						echo '<div class="product-option">';
						echo wp_get_attachment_image($material_2_image, 'thumbnail', false, array(
							'width' => 60,
							'height' => 60,
							'class' => 'round-image'
						));
						echo '<span>' . $attachment->post_title . '</span></div>';

						if ($material_2_id != $product_id) {
							echo '</a>';
						}
					}
				}
			}

		} else {
			$current_material_1_code = '';
			if (count(value: $material_1_ids) > 0) {
				foreach ($material_1_ids as $material_1_id) {
					$product = wc_get_product($material_1_id);
					if ($product) {
						$material_1_image = get_post_meta($material_1_id, 'material_1_image', true);
						$attachment = get_post($material_1_image);

						if ($material_1_id == $product_id) {
							$current_material_1_code = $attachment->post_title;
						}
					}
				}
			}

			$current_material_2_code = '';
			if (count($material_2_ids) > 0) {
				foreach ($material_2_ids as $material_2_id) {
					$product = wc_get_product($material_2_id);
					if ($product) {
						$material_2_image = get_post_meta($material_2_id, 'material_2_image', true);
						$attachment = get_post($material_2_image);

						if ($material_2_id == $product_id) {
							$current_material_2_code = $attachment->post_title;
						}
					}
				}
			}

			if (count($material_1_ids) > 0) {
				foreach ($material_1_ids as $material_1_id) {
					$product = wc_get_product($material_1_id);
					if ($product) {
						$material_12_image = get_post_meta($material_1_id, 'material_2_image', true);
						$attachment_12 = get_post($material_12_image);

						if ($attachment_12->post_title !== $current_material_2_code) {
							continue;
						}

						$material_1_image = get_post_meta($material_1_id, 'material_1_image', true);
						$attachment = get_post($material_1_image);

						if ($material_1_id != $product_id) {
							echo '<a href="' . esc_url(get_permalink($material_1_id)) . '">';
						}

						echo '<div class="product-option">';
						echo wp_get_attachment_image($material_1_image, 'thumbnail', false, array(
							'width' => 60,
							'height' => 60,
							'class' => 'round-image'
						));
						echo '<span>' . $attachment->post_title . '</span></div>';

						if ($material_1_id != $product_id) {
							echo '</a>';
						}
					}
				}
			}

			echo '<div class="product-option" style="height: 32px; justify-content: center">';
			echo '+';
			echo '</div>';

			if (count($material_2_ids) > 0) {
				foreach ($material_2_ids as $material_2_id) {
					$product = wc_get_product($material_2_id);
					if ($product) {
						$material_21_image = get_post_meta($material_2_id, 'material_1_image', true);
						$attachment_21 = get_post($material_21_image);

						if ($attachment_21->post_title !== $current_material_1_code) {
							continue;
						}

						$material_2_image = get_post_meta($material_2_id, 'material_2_image', true);
						$attachment = get_post($material_2_image);

						if ($material_2_id != $product_id) {
							echo '<a href="' . esc_url(get_permalink($material_2_id)) . '">';
						}

						echo '<div class="product-option">';
						echo wp_get_attachment_image($material_2_image, 'thumbnail', false, array(
							'width' => 60,
							'height' => 60,
							'class' => 'round-image'
						));
						echo '<span>' . $attachment->post_title . '</span></div>';

						if ($material_2_id != $product_id) {
							echo '</a>';
						}
					}
				}
			}
		}

		// $args1 = array(
		// 	'post_type' => 'product',
		// 	'meta_query' => array(
		// 		array(
		// 			'key' => 'material_1_options',
		// 			'value' => get_the_ID(),
		// 			'compare' => 'LIKE',
		// 		),
		// 	),
		// );
		// $query1 = new WP_Query($args1);
		// if ($query1->have_posts()) {
		// 	while ($query1->have_posts()) {
		// 		$query1->the_post();

		// 		$material_1_image = get_post_meta(get_the_ID(), 'material_1_image', true);
		// 		echo '<a href="' . esc_url(get_permalink(get_the_ID())) . '">';
		// 		echo wp_get_attachment_image($material_1_image, 'thumbnail', false, array(
		// 			'width' => 60,
		// 			'height' => 60,
		// 			'class' => 'round-image'
		// 		));
		// 		echo '</a>';
		// 	}
		// }
		// wp_reset_postdata();

		// $args2 = array(
		// 	'post_type' => 'product',
		// 	'meta_query' => array(
		// 		array(
		// 			'key' => 'material_2_options',
		// 			'value' => get_the_ID(),
		// 			'compare' => 'LIKE',
		// 		),
		// 	),
		// );
		// $query2 = new WP_Query($args2);
		// if ($query2->have_posts()) {
		// 	while ($query2->have_posts()) {
		// 		$query2->the_post();

		// 		$material_2_image = get_post_meta(get_the_ID(), 'material_2_image', true);
		// 		echo '<a href="' . esc_url(get_permalink(get_the_ID())) . '">';
		// 		echo wp_get_attachment_image($material_2_image, 'thumbnail', false, array(
		// 			'width' => 60,
		// 			'height' => 60,
		// 			'class' => 'round-image'
		// 		));
		// 		echo '</a>';
		// 	}
		// }
		// wp_reset_postdata();

		echo '<style type="text/css">';
		echo '.product-option {
			display: flex;
			flex-direction: column;
			padding: 0 4px;
			text-align: center;
		}';
		echo '.product-option img{
		}';
		echo '.product-option span{
			font-weight: bold;
			}';
		echo 'a .product-option span{
				font-weight: normal;
				}';
		echo '#product-materials-option-wrapper {
			display: flex;
		}';
		echo '#product-materials-option-wrapper .round-image {
			width: 32px; /* Adjust as needed */
			height: auto; /* Should be equal to width for a perfect circle */
			border-radius: 50%; /* Makes the image round */
			object-fit: cover; /* Ensures the image fills the container if not perfectly square */
			margin-right: 5px;
		}';
		echo '</style>';

		echo '</div>';
	}
}