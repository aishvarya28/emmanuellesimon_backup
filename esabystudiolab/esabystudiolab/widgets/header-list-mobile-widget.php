<?php
class Elementor_Header_List_Mobile_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'header_list_mobile_widget';
	}

	public function get_title() {
		return esc_html__( 'Header List Mobile', 'elementor-addon' );
	}

	public function get_style_depends() {
		return [ 'headerListMobile' ];
	}

    public function get_script_depends() {
		return [ 'headerListMobile' ];
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
				'label' => esc_html__( 'Header List Mobile', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Header List Mobile', 'elementor-addon' ),
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

		$titleAbout = '';
		$propos_permalink = '';
		$titlePress = '';
		$presse_permalink = '';

		if(PLL_current_language() == 'fr') {
			$titlePress = 'Presse';
			$titleAbout = 'À propos';
			$account_perlink = get_permalink('28876');
			$favorite_permalink = get_permalink('28910');
			$presse_permalink = get_permalink('28869');
			$propos_permalink = get_permalink('34252');
		}

		if(PLL_current_language() == 'en') {
			$titlePress = 'Press';
			$titleAbout = 'About';
			$account_perlink = get_permalink('37270');
			$favorite_permalink = get_permalink('35477');
			$presse_permalink = get_permalink('35360');
			$propos_permalink = get_permalink('34319');
		}
		
		$settings = $this->get_settings_for_display();
		
		?>

<div>
    <button onmousedown="showMenu()" id="menuButton">
        <svg width="24" height="15" viewBox="0 0 24 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line y1="0.625" x2="23.8" y2="0.625" stroke="black" stroke-width="0.75" />
            <line y1="7.625" x2="23.8" y2="7.625" stroke="black" stroke-width="0.75" />
            <line y1="14.625" x2="23.8" y2="14.625" stroke="black" stroke-width="0.75" />
        </svg>

    </button>
</div>


<div id="popup-mobile" style="display:none;">
<div>
<div id="menuMobile">
        <button onmousedown="showArchitecture()" id='architectureButton'> Architecture</button>
        <button onmousedown="showMobiler()" id='mobilierButton'> Mobilier</button>
        <a href="<?php echo  $propos_permalink;?>"><?echo $titleAbout ?></a>
        <a href="<?php echo  $presse_permalink;?>"><?echo $titlePress ?></a>
	</div>

    <div id="mobilier-mobile" style="display:none;">
        <div id='line'></div>
        <div id="levelOneMobile">
            <ul class="list uppercase">
			<li><button class="header-list" onmousedown="clickEshopMobile(this)" data-level="one" data-why="produit"
                        data-id="" data-action="submenu_eshop_mobile"
                        data-nonce="<?php echo wp_create_nonce('submenu_eshop_mobile'); ?>"
                        data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Produits</button></li>
                <li><button class="header-list" onmousedown="clickEshopMobile(this)" data-level="one"
                        data-why="collection" data-id="" data-action="submenu_eshop_mobile"
                        data-nonce="<?php echo wp_create_nonce('submenu_eshop_mobile'); ?>"
                        data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Collections</button></li>
                
            </ul>
        </div>
        <div id="levelTwoMobile">
        </div>
        <div id="levelThreeMobile">
        </div>
    </div>

    <div id="architecture-mobile" style="display:none;">
        <div id='lineMobile'></div>
        <div id="archtectureOneMobile">
            <ul class="list uppercase">
                <li><a href="<?php echo get_post_type_archive_link('architecture'); ?>">Tout</a></li>
                <?php foreach($familles as $famille){?>
                <li><button class="header-list" onmousedown="clickArchitectureMobile(this)" data-level="one"
                        data-id="<?php echo $famille->slug;?>" data-action="submenu_archictecture_mobile"
                        data-nonce="<?php echo wp_create_nonce('submenu_archictecture_mobile'); ?>"
                        data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"><?php echo $famille->name;?></button>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div id="archtectureTwoMobile">
        </div>
    </div>
</div>
	

	<div id="foot-popup">
		<ul id="lang-menu"><?php pll_the_languages( array('display_names_as' => 'slug') ); ?></ul>
		<div id="account-whishlist">
			<a href="<?php echo  $account_perlink;?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="19" viewBox="0 0 16 19" fill="none"><path d="M8.00013 10.7893C10.7034 10.7893 12.8948 8.59791 12.8948 5.89466C12.8948 3.19141 10.7034 1 8.00013 1C5.29688 1 3.10547 3.19141 3.10547 5.89466C3.10547 8.59791 5.29688 10.7893 8.00013 10.7893Z" stroke="#1C1C1C" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"></path><path d="M1 17.7891C1 13.9233 4.1342 10.7891 8 10.7891C11.8658 10.7891 15 13.9233 15 17.7891" stroke="#1C1C1C" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"></path></svg>
			</a>
			<a href="<?php echo  $favorite_permalink;?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M9.24901 1C7.7299 1 6.50197 2.20132 6.50197 3.68921C6.50197 2.20132 5.26911 1 3.75099 1C2.23286 1 1 2.20132 1 3.68921C1 3.78667 1.0079 3.88316 1.01481 3.98061C1.18656 6.66211 3.67794 9.24035 6.50197 12C9.32601 9.23939 11.8134 6.66211 11.9852 3.98061C11.9931 3.88316 12 3.78667 12 3.68921C12 2.20132 10.7711 1 9.24901 1Z" stroke="black" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"></path></svg>
			</a>
			
		</div>
	</div>

</div>
    

<?php
	}
}