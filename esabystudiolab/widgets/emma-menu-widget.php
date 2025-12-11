<?php
class Elementor_Emma_Menu_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'emma_menu';
	}

	public function get_title()
	{
		return esc_html__('Emma Menu', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaMenu'];
	}

	public function get_script_depends()
	{
		return ['emmaMenu'];
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
				'label' => esc_html__('Emma Menu', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Menu', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]

		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function getTransation($menu_item)
	{
		$object = $menu_item->object;
		if ($object == 'page') {
			$page_id = pll_get_post($menu_item->object_id, 'en');
			$menu_item->url = get_permalink($page_id);
			$menu_item->title = get_the_title($page_id);
		}

		return $menu_item;
	}

	protected function render()
	{
		// Get the menu items
		$menu_items = $this->get_nested_menu_items('main-menu-fr');

		$lang = pll_current_language();

		if ($menu_items) {
			echo '<div class="emma-menu" style="display:none">';
			echo '<ul class="level-1">';
			foreach ($menu_items as $menu_item) {
				if ($menu_item->menu_item_parent == 0) {
					if ($lang == 'en') {
						$transation = $this->getTransation($menu_item);
						echo '<li id="item-' . $menu_item->ID . '"><a href="' . $transation->url . '">' . $transation->title . '</a></li>';
					} else {
						echo '<li id="item-' . $menu_item->ID . '"><a href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
					}
				}
			}
			echo '</ul>';
			echo '</div>';
		}
		?>

		<script type="text/javascript">
			let hideMenuTimeouts = {}; // Object to store timeouts for each submenu level
			let showMenuTimeout; // Timeout to delay showing a submenu
			let activeItemId = null; // To track the currently active item

			// Function to clear a specific hide timeout for a level
			function clearHideTimeout(level) {
				if (hideMenuTimeouts[level]) {
					clearTimeout(hideMenuTimeouts[level]);
					hideMenuTimeouts[level] = null;
				}
			}

			// Function to set a hide timeout for a specific level
			function setHideTimeout(level, duration, hideFunc) {
				clearHideTimeout(level); // Clear any existing timeout
				hideMenuTimeouts[level] = setTimeout(hideFunc, duration);
			}

			// Function to hide all submenus of a specific level and remove active class from parent items
			function hideSubmenus(level) {
				if (level == 2) {
					doHideSubmenus(2);
					doHideSubmenus(3);
					doHideSubmenus(4);
				} else if (level == 3) {
					doHideSubmenus(3);
					doHideSubmenus(4);
				} else if (level == 4) {
					doHideSubmenus(4);
				} else {
					doHideSubmenus(level);
				}
			}

			function doHideSubmenus(level) {
				const submenus = document.querySelectorAll(`.level-${level}`);
				submenus.forEach(submenu => {
					submenu.style.display = 'none';
				});
				// Remove active class from parent items at this level
				document.querySelectorAll(`.level-${level - 1} li.active-item`).forEach(item => {
					item.classList.remove('active-item');
				});
			}

			// Function to show the submenu that corresponds to the hovered item and add active class to parent
			function showNextSubmenu(itemId, nextLevel) {
				const submenu = document.querySelector(`.level-${nextLevel}.${itemId}`);
				if (submenu) {
					submenu.style.display = 'block';
					addActiveClass(itemId, nextLevel - 1); // Add active class to parent
				}
			}

			// Function to add 'active' class to parent menu item
			function addActiveClass(itemId, parentLevel) {
				const parentItem = document.querySelector(`.level-${parentLevel} li#${itemId}`);
				if (parentItem) {
					parentItem.classList.add('active-item');
				}
			}

			// Debounce function to delay submenu activation
			function debounce(func, delay) {
				return function (...args) {
					clearTimeout(showMenuTimeout);
					showMenuTimeout = setTimeout(() => func.apply(this, args), delay);
				};
			}

			// Function to check if the user is intentionally hovering over an item
			function hoverIntent(itemId, nextLevel, delay) {
				return function () {
					if (activeItemId !== itemId) {
						clearTimeout(showMenuTimeout);
						showMenuTimeout = setTimeout(() => {
							activeItemId = itemId;
							hideSubmenus(nextLevel); // Hide other submenus at this level
							showNextSubmenu(itemId, nextLevel); // Show the relevant submenu
							document.querySelector('.emma-sub-menu').style.display = 'block'; // Ensure sub-menu is visible
						}, delay); // Show submenu after the delay
					}
				};
			}

			document.addEventListener("DOMContentLoaded", function () {
				const subMenuContainer = document.querySelector('.emma-sub-menu');

				setTimeout(() => {
					document.querySelector('.emma-menu').style = 'display:block';
					// document.querySelector('.emma-sub-menu').style = 'display:block';
				}, 500); // 500m seconds delay

				// Add event listeners for Level 1 items with hover intent
				document.querySelectorAll('.level-1 li').forEach(item => {
					item.addEventListener('mouseenter', hoverIntent(item.id, 2, 300)); // 300ms delay for hover intent
					item.addEventListener('mouseleave', () => {
						clearTimeout(showMenuTimeout); // Clear timeout on leave
					});
				});

				// Add event listeners for Level 2 items with hover intent
				document.querySelectorAll('.level-2 li').forEach(item => {
					item.addEventListener('mouseenter', hoverIntent(item.id, 3, 300)); // 300ms delay for hover intent
					item.addEventListener('mouseleave', () => {
						clearTimeout(showMenuTimeout); // Clear timeout on leave
					});
				});

				// Add event listeners for Level 3 items with hover intent
				document.querySelectorAll('.level-3 li').forEach(item => {
					item.addEventListener('mouseenter', hoverIntent(item.id, 4, 300)); // 300ms delay for hover intent
					item.addEventListener('mouseleave', () => {
						clearTimeout(showMenuTimeout); // Clear timeout on leave
					});
				});

				// Add mouseleave listener to start a 2-second timer to hide submenus
				document.querySelector('.emma-menu').addEventListener('mouseleave', () => {
					setHideTimeout(2, 2000, () => {
						hideSubmenus(2); // Hide level-2 and deeper submenus after 2 seconds
						hideSubmenus(3);
						hideSubmenus(4);
						subMenuContainer.style.display = 'none'; // Hide the submenu container
						activeItemId = null; // Reset active item
					});
				});

				document.querySelector('.emma-sub-menu').addEventListener('mouseleave', () => {
					setHideTimeout(2, 2000, () => {
						hideSubmenus(2); // Hide level-2 and deeper submenus after 2 seconds
						hideSubmenus(3);
						hideSubmenus(4);
						subMenuContainer.style.display = 'none'; // Hide the submenu container
						activeItemId = null; // Reset active item
					});
				});

				// Clear the hide timeout if the mouse re-enters the emma-menu before 2 seconds
				document.querySelector('.emma-menu').addEventListener('mouseenter', () => {
					clearHideTimeout(2); // Clear any hide timeout for the menu
				});

				document.querySelector('.emma-sub-menu').addEventListener('mouseenter', () => {
					clearHideTimeout(2); // Clear any hide timeout for the submenu
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