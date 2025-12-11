<?php
class Elementor_Emma_Product_Materials_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'emma_product_materials';
	}

	public function get_title()
	{
		return esc_html__('Emma Product Materials', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaProductMaterials'];
	}

	public function get_script_depends()
	{
		return ['emmaProductMaterials'];
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
				'label' => esc_html__('Emma Product Materials', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Product Materials', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]

		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render()
	{
		$pod = pods('product', get_the_ID());
		if ($pod->exists()) {
			echo '<div id="product-materials-wrapper">';
			$material1 = $pod->field('material_1_image');
			if ($material1) {
				echo '<a href="' . esc_url(wp_get_attachment_image_url($material1['ID'], 'full')) . '">';
				echo wp_get_attachment_image($material1['ID'], 'thumbnail', false, array(
					'width' => 60,
					'height' => 60,
				));
				echo '</a>';
			}

			$material2 = $pod->field('material_2_image');
			if ($material2) {
				echo '<a href="' . esc_url(wp_get_attachment_image_url($material2['ID'], 'full')) . '">';
				echo wp_get_attachment_image($material2['ID'], 'thumbnail', false, array(
					'width' => 60,
					'height' => 60,
				));
				echo '</a>';
			}
			echo '</div>';
			?>
			<style type="text/css">
				#product-materials-wrapper img {
					width: 60px;
					height: 60px;
					margin-right: 5px;
				}
			</style>
			<?php
		}
	}
}