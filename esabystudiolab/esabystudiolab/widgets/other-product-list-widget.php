<?php
class Elementor_Other_Product_List_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'other_product_list_widget';
	}

	public function get_title() {
		return esc_html__( 'Other Product List', 'elementor-addon' );
	}

	public function get_style_depends() {
		return [ 'productList' ];
	}

    public function get_script_depends() {
		return [ 'productList' ];
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'studioslab' ];
	}

	public function get_keywords() {
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Other Product List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Other Product List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
			
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {

        $product_page = get_page_by_path( get_query_var('product'), OBJECT, 'product' );
        
		$products = wc_get_products( array(
			'limit'  => 4,
			'status' => 'publish',
            'exclude' => array($product_page->ID),
            'category' => array(get_product_category()),
			'orderby' => 'name',
            'order' => 'ASC',
		));
		
		$settings = $this->get_settings_for_display();

		$title = '';

		if (PLL_current_language() == 'fr'){
			$title = "D'AUTRES PRODUITS​";
		}

		if (PLL_current_language() == 'en'){
			$title = 'Other Products';
		}
		


		?>


<div class="content">
<?php if (count($products) != 0) {?>
	<div style="margin-bottom:20px">
		<span class="other-title"><?php echo $title ?></span>
	</div>
	<?php } ?>
    <div id="list-produit" class="four-column">
	<?php
	foreach($products as $product) { 
	?>
	<div class="produit">
	<a href="<?php echo $product->get_permalink()?>">
        <img class="img-product" src="<?php
            echo wp_get_attachment_url( $product->get_image_id() );
	?>"/></a>
        <div class="line-one">
          <div class="title">
            <span class="name"><?php 
			$categorie = esa_get_primary_category_link($product->get_id());
			echo $categorie;
			?></span> - 
            <span class="tag"><?php echo $product->get_title(); ?></span> 
           </div>
          <div class="fav"><svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.24901 1C7.7299 1 6.50197 2.20132 6.50197 3.68921C6.50197 2.20132 5.26911 1 3.75099 1C2.23286 1 1 2.20132 1 3.68921C1 3.78667 1.0079 3.88316 1.01481 3.98061C1.18656 6.66211 3.67794 9.24035 6.50197 12C9.32601 9.23939 11.8134 6.66211 11.9852 3.98061C11.9931 3.88316 12 3.78667 12 3.68921C12 2.20132 10.7711 1 9.24901 1Z" stroke="black" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"/>
</svg></div>
        </div>
        <div class="line-two">
          <div class="more">
            <a href="<?php echo $product->get_permalink()?>"><? echo (PLL_current_language() == 'en')? "More information": "En savoir plus" ?></a> &#9679;
           </div>
          <div class="price"><? echo (PLL_current_language() == 'en') ? "Price on request" : "Prix sur demande" ?></div>
        </div>
		
      </div>
	<?php } ?>
    </div>
  </div>

		<?php
	}
}