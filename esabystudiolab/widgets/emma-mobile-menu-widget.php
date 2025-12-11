<?php
class Elementor_Emma_Mobile_Menu_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'emma_mobilemenu';
	}

	public function get_title()
	{
		return esc_html__('Emma Mobile Menu', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaMobileMenu'];
	}

	public function get_script_depends()
	{
		return ['emmaMobileMenu'];
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
				'label' => esc_html__('Emma Mobile Menu', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Mobile Menu', 'elementor-addon'),
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
		$widget_id = esc_attr($this->get_id());

		// Get the menu items
		$menu_items = $this->get_nested_menu_items('main-menu-fr');
		$lang = pll_current_language();

		echo '<div id="widget-' . $widget_id . '">';
		if ($menu_items) {
			echo '<div class="emma-mobile-menu">';
			echo '<ul class="level-1">';
			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					$hasChild = count($menu_item->children) ? 'has-child' : '';
					if ($lang == 'en') {
						$transation = $this->getTransation($menu_item);
						echo '<li id="item-' . $menu_item->ID . '"><a id="item-' . $menu_item->ID . '" class="' . $hasChild . '" href="' . $transation->url . '">' . $transation->title . '</a></li>';
					} else {
						echo '<li id="item-' . $menu_item->ID . '"><a id="item-' . $menu_item->ID . '" class="' . $hasChild . '" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
					}
				}
			}
			echo '</ul>';
			echo '</div>';

			echo '<div class="emma-mobile-sub-menu">';

			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					if (count($menu_item->children) > 0) {
						echo '<ul class="level-2 item-' . $menu_item->ID . '">';
						foreach ($menu_item->children as $child1) {
							$hasChild = count($child1->children) ? 'has-child' : '';
							if ($lang == 'en') {
								$transation = $this->getTransation($child1);
								$title = $transation->title;
								if (in_array($transation->title, ['Architecture'])) {
									$title = 'All';
								}
								echo '<li id="item-' . $transation->ID . '"><a id="item-' . $transation->ID . '" class="' . $hasChild . '" href="' . $transation->url . '">' . $title . '</a></li>';
							} else {
								echo '<li id="item-' . $child1->ID . '"><a id="item-' . $child1->ID . '" class="' . $hasChild . '" href="' . $child1->url . '">' . $child1->title . '</a></li>';
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
									$hasChild = count($child2->children) ? 'has-child' : '';
									if ($lang == 'en') {
										$transation = $this->getTransation($child2);
										$title = $transation->title;
										if (in_array($title, ['Products', 'Collections'])) {
											$title = 'All';
										}
										echo '<li id="item-' . $transation->ID . '"><a id="item-' . $transation->ID . '" class="' . $hasChild . '" href="' . $transation->url . '">' . $title . '</a></li>';
									} else {
										echo '<li id="item-' . $child2->ID . '"><a id="item-' . $child2->ID . '" class="' . $hasChild . '" href="' . $child2->url . '">' . $child2->title . '</a></li>';
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

			echo '</div></div>';
		}

		?>
		<script type="text/javascript">
			// let hideMobileMenuTimeout; // Global variable to store the timeout

			// var parentMenus = document.querySelectorAll('a.has-child');
			// parentMenus.forEach(function (parentMenu) {
			// 	parentMenu.addEventListener('click', function (event) {
			// 		event.preventDefault();
			// 	});
			// });

			// Function to hide all submenus of a specific level
			function hideMobileSubmenus(level) {
				const submenus = document.querySelectorAll(`#widget-<?php echo $widget_id ?> .level-${level}`);
				submenus.forEach(submenu => {
					submenu.style.display = 'none';
				});
			}

			// Function to show the submenu matching the id of the hovered item
			function showNextMobileSubmenu(itemId, nextLevel) {
				console.log(`.level-${nextLevel}.${itemId}`);
				const submenu = document.querySelector(`#widget-<?php echo $widget_id ?> .level-${nextLevel}.${itemId}`);
				if (submenu) {
					console.log(submenu);
					submenu.style.display = 'block';
				}
			}

			jQuery(document).ready(function () {
				// setTimeout(() => {
				// 	document.querySelector('.emma-mobile-menu').style = 'display:block';
				// }, 500); // 500m seconds delay


				jQuery(document).on('elementor/popup/show', function (event, id, instance) {
					const mobileSubMenuContainer = document.querySelector('.emma-mobile-sub-menu');
					// console.log('Popup is open:', event);
					// console.log('Popup is open:', id);
					// console.log('Popup is open:', instance);

					// Remove any previously attached event listeners to avoid duplication
					document.querySelectorAll('#widget-<?php echo $widget_id ?> .level-1 li a.has-child').forEach(item => {
						item.replaceWith(item.cloneNode(true));  // Replace with a clone to remove old listeners
					});

					// Add event listeners for Level 1 items
					document.querySelectorAll('#widget-<?php echo $widget_id ?> .level-1 li a.has-child').forEach(item => {
						// console.log(item.id);
						item.addEventListener('click', (event) => {
							event.preventDefault();
							// clearTimeout(hideMobileMenuTimeout); // Stop the timeout when the mouse re-enters
							hideMobileSubmenus(2); // Hide all level-2 submenus
							hideMobileSubmenus(3); // Hide all level-3 submenus
							hideMobileSubmenus(4); // Hide all level-4 submenus
							const itemId = item.id; // Get the id of the hovered item
							// console.log(item.id);
							showNextMobileSubmenu(itemId, 2); // Show the corresponding level-2 submenu
							mobileSubMenuContainer.style.display = 'block';
						});
					});

					// Add event listeners for Level 2 items
					document.querySelectorAll('#widget-<?php echo $widget_id ?> .level-2 li a.has-child').forEach(item => {
						item.addEventListener('click', (event) => {
							event.preventDefault();
							// clearTimeout(hideMobileMenuTimeout); // Stop the timeout when the mouse re-enters
							hideMobileSubmenus(3); // Hide all level-3 submenus
							hideMobileSubmenus(4); // Hide all level-4 submenus
							const itemId = item.id; // Get the id of the hovered item
							showNextMobileSubmenu(itemId, 3); // Show the corresponding level-3 submenu
						});
					});

					// Add event listeners for Level 3 items
					document.querySelectorAll('#widget-<?php echo $widget_id ?> .level-3 li a.has-child').forEach(item => {
						item.addEventListener('click', (event) => {
							event.preventDefault();
							// clearTimeout(hideMobileMenuTimeout); // Stop the timeout when the mouse re-enters
							hideMobileSubmenus(4); // Hide all level-4 submenus
							const itemId = item.id; // Get the id of the hovered item
							showNextMobileSubmenu(itemId, 4); // Show the corresponding level-4 submenu
						});
					});

					// Add mouseleave listener to start a 2-second timer to hide submenus
					// document.querySelector('.emma-mobile-menu').addEventListener('mouseleave', () => {
					// 	hideMobileMenuTimeout = setTimeout(() => {
					// 		hideMobileSubmenus(2); // Hide level-2 and deeper submenus after 2 seconds
					// 		hideMobileSubmenus(3);
					// 		hideMobileSubmenus(4);
					// 		mobileSubMenuContainer.style.display = 'none';
					// 	}, 2000); // 2 seconds delay
					// });

					// document.querySelector('.emma-sub-menu').addEventListener('mouseleave', () => {
					// 	hideMobileMenuTimeout = setTimeout(() => {
					// 		hideMobileSubmenus(2); // Hide level-2 and deeper submenus after 2 seconds
					// 		hideMobileSubmenus(3);
					// 		hideMobileSubmenus(4);
					// 		mobileSubMenuContainer.style.display = 'none';
					// 	}, 2000); // 2 seconds delay
					// });

					// Clear the timeout if the mouse re-enters the emma-mobile-menu before 2 seconds
					// document.querySelector('.emma-mobile-menu').addEventListener('click', () => {
					// 	clearTimeout(hideMobileMenuTimeout); // Stop the timeout
					// });

					// document.querySelector('.emma-sub-menu').addEventListener('click', () => {
					// 	clearTimeout(hideMobileMenuTimeout); // Stop the timeout
					// });
				});
			});
		</script>
		<?php
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