<?php
class Elementor_SubMenu_Architecture_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'submenu_architecture_widget';
	}

	public function get_title() {
		return esc_html__( 'SubMenu Architecture', 'elementor-addon' );
	}

	public function get_style_depends() {
		return [ 'submenuArchitecture' ];
	}

    public function get_script_depends() {
		return [ 'submenuArchitecture' ];
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
				'label' => esc_html__( 'SubMenu Architecture', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'SubMenu Architecture', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
			
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {

        $args = array(
            'taxonomy'   => 'famille',
			'orderby' => 'description',
        	'order' => 'ASC',
            'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
        );
        
        $familles = get_terms($args);
		
		$settings = $this->get_settings_for_display();
		
		?>

<div class="list-header">
  <div id="levelOneProject">
  <ul class="list uppercase">
    <li><a href="<?php echo get_post_type_archive_link('architecture'); ?>">Tout</a></li>
    <?php foreach($familles as $famille){?>
        <li><button class="header-list" onmouseover="clickProject(this)" onmousedown="clickProject(this)" data-level="one" data-id="<?php echo $famille->slug;?>" data-action="project_list" data-nonce="<?php echo wp_create_nonce('project_list'); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"><?php echo $famille->name;?></button></li>
    <?php } ?>
    
  </ul>
</div>
<div id="levelTwoProject">
</div>

</div>

		<?php
	}
}