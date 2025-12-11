<?php
class Elementor_Emma_Sub_Menu_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'emma_submenu';
	}

	public function get_title()
	{
		return esc_html__('Emma Sub Menu', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaSubMenu'];
	}

	public function get_script_depends()
	{
		return ['emmaSubMenu'];
	}

	public function get_icon()
	{
		return 'eicon-navigation-horizontal';
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
				'label' => esc_html__('Emma Sub Menu', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Sub Menu', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]

		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function getTransation($menu_item)
	{
		$object = $menu_item->object;
		if ($object == 'page' || $object == 'product' || $object == 'architecture') {
			$post_id = pll_get_post($menu_item->object_id, 'en');
			$menu_item->url = get_permalink($post_id);
			$menu_item->title = get_the_title($post_id);
		} elseif ($object == 'product_cat' || $object == 'sorte') {
			$term_id = pll_get_term($menu_item->object_id, 'en');
			$term = get_term_by('id', $term_id, $object);
			$menu_item->url = get_term_link($term, $object);
			$menu_item->title = $term->name;
		}

		return $menu_item;
	}

	protected function render()
	{
		// Get the menu items
		$menu_items = $this->get_nested_menu_items('main-menu-fr');
		$lang = pll_current_language();

		if ($menu_items) {
			echo '<div class="emma-sub-menu" style="display:none">';

			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					if (count($menu_item->children) > 0) {
						echo '<ul class="level-2 item-' . $menu_item->ID . '">';
						foreach ($menu_item->children as $child1) {
							if ($lang == 'en') {
								$transation = $this->getTransation($child1);
								$title = $transation->title;
								if (in_array($transation->title, ['Architecture'])) {
									$title = 'All';
								}
								echo '<li id="item-' . $transation->ID . '"><a href="' . $transation->url . '">' . $title . '</a></li>';
							} else {
								echo '<li id="item-' . $child1->ID . '"><a href="' . $child1->url . '">' . $child1->title . '</a></li>';
							}
						}
						echo '</ul>';
					}
				}
			}

			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					if (count($menu_item->children) > 0) {
						foreach ($menu_item->children as $child1) {
							if (count($child1->children) > 0) {
								echo '<ul class="level-3 item-' . $child1->ID . '">';
								foreach ($child1->children as $child2) {
									if ($lang == 'en') {
										$transation = $this->getTransation($child2);
										$title = $transation->title;
										if (in_array($title, ['Products', 'Collections'])) {
											$title = 'All';
										}
										echo '<li id="item-' . $transation->ID . '"><a href="' . $transation->url . '">' . $title . '</a></li>';
									} else {
										echo '<li id="item-' . $child2->ID . '"><a href="' . $child2->url . '">' . $child2->title . '</a></li>';
									}
								}
								echo '</ul>';
							}
						}
					}
				}
			}

			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					if (count($menu_item->children) > 0) {
						foreach ($menu_item->children as $child1) {
							if (count($menu_item->children) > 0) {
								foreach ($child1->children as $child2) {
									if (count($child2->children) > 0) {
										echo '<ul class="level-4 item-' . $child2->ID . '">';
										foreach ($child2->children as $child3) {
											if ($lang == 'en') {
												$transation = $this->getTransation($child3);
												$title = ucfirst(strtolower($transation->title));
												if (strtolower($child3->title) == strtolower($child2->title)) {
													$title = 'All';
												}
												echo '<li id="item-' . $transation->ID . '"><a href="' . $transation->url . '">' . $title . '</a></li>';
											} else {
												echo '<li id="item-' . $child3->ID . '"><a href="' . $child3->url . '">' . $child3->title . '</a></li>';
											}
										}
										echo '</ul>';
									}
								}
							}
						}
					}
				}
			}

			echo '</div>';
		}
	}

	function get_nested_menu_items($menu_name)
	{
		// Get the menu object by name or ID
		$menu = wp_get_nav_menu_object($menu_name);

		// If menu doesn't exist, return an empty array
		if (!$menu) {
			return [];
		}

		// Get the menu items
		$menu_items = wp_get_nav_menu_items($menu->term_id);

		// Create an array to store the menu items, organized by their IDs
		$menu_tree = [];
		$menu_by_id = [];

		// First, organize menu items by their ID
		foreach ($menu_items as $item) {
			$menu_by_id[$item->ID] = $item;
			$menu_by_id[$item->ID]->children = []; // Initialize the children array
		}

		// Now, build the nested array up to 4 levels
		foreach ($menu_by_id as $item) {
			// If the item has a parent (not top level)
			if ($item->menu_item_parent != 0) {
				// Check if the parent exists in the array
				if (isset($menu_by_id[$item->menu_item_parent])) {
					// Add the item to its parent's children array
					$menu_by_id[$item->menu_item_parent]->children[] = $item;
				}
			} else {
				// Top-level menu items go directly into the root of the $menu_tree
				$menu_tree[] = $item;
			}
		}

		// Return the organized menu tree
		return $menu_tree;
	}
}