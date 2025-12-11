<?php
class Elementor_Header_List_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'header_list_widget';
	}

	public function get_title() {
		return esc_html__( 'Header List', 'elementor-addon' );
	}

	public function get_style_depends() {
		return [ 'headerList' ];
	}

    public function get_script_depends() {
		return [ 'headerList' ];
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
				'label' => esc_html__( 'Header List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Header List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
			
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		?>

<div class="list-header">
  <div id="levelOne">
  <ul class="list uppercase">
  <li><button class="header-list" onmouseover="clickHeader(this)" onmousedown="clickHeader(this)" data-lang="<?php echo PLL_current_language()?>" data-level="one" data-why="produit" data-id="" data-action="submenu_eshop" data-nonce="<?php echo wp_create_nonce('submenu_eshop'); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"><?php echo (PLL_current_language() == 'en') ? "Products" : "Produits" ?></button></li>
    <li><button class="header-list" onmouseover="clickHeader(this)" onmousedown="clickHeader(this)" data-lang="<?php echo PLL_current_language()?>" data-level="one" data-why="collection" data-id="" data-action="submenu_eshop" data-nonce="<?php echo wp_create_nonce('submenu_eshop'); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Collections</button></li>
    
  </ul>
</div>
<div id="levelTwo">
</div>
<div id="levelThree">
</div>
</div>

		<?php
	}
}