<?php
class Elementor_Project_List_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'project_list_widget';
	}

	public function get_title() {
		return esc_html__( 'Project List', 'elementor-addon' );
	}

	public function get_style_depends() {
		return [ 'projectList' ];
	}

    public function get_script_depends() {
		return [ 'projectList' ];
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
				'label' => esc_html__( 'Project List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Project List', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
			
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {

        $args = array(
            'taxonomy'   => 'famille',
            'hide_empty' => true, // Mettez à true pour masquer les catégories sans produits
        );
        
        $familles = get_terms($args);
		
		?>

<div class="list-header">
  <div id="levelOneProject">
  <ul class="list uppercase">
    <li><a href="<?php echo get_post_type_archive_link('architecture'); ?>">Tout</a></li>
    <?php foreach($familles as $famille){?>
        <li><button class="header-list" onmousedown="clickProject(this)" data-level="one" data-id="<?php echo $famille->slug;?>" data-action="project_list" data-nonce="<?php echo wp_create_nonce('project_list'); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"><?php echo $famille->name;?> g</button></li>
    <?php } ?>
    
  </ul>
</div>
<div id="levelTwoProject">
</div>

</div>

		<?php
	}
}