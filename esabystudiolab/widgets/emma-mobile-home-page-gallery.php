<?php
class Elementor_Emma_Mobile_Home_Page_Gallery_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'emma_mobile_home_page_gallery';
	}

	public function get_title()
	{
		return esc_html__('Emma Mobile Home Page Gallery', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaMobileHomePageGallery'];
	}

	public function get_script_depends()
	{
		return ['emmaMobileHomePageGallery'];
	}

	public function get_icon()
	{
		return 'eicon-gallery-masonry';
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
				'label' => esc_html__('Emma Mobile Home Page Gallery', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Mobile Home Page Gallery', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]

		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function get_embedded_image_ids($post_id)
	{
		// Get the post content
		$post_content = get_post_field('post_content', post: $post_id);

		if (empty($post_content)) {
			return [];
		}

		// Use regex to find all <img> tags
		preg_match_all('/<img[^>]+src="([^">]+)"/i', $post_content, $matches);

		if (empty($matches[1])) {
			return [];
		}

		$image_urls = $matches[1]; // Array of image URLs
		$image_ids = [];

		foreach ($image_urls as $image_url) {
			// Get the attachment ID from the image URL
			$image_url = str_replace('-768x1024', '-scaled', $image_url);
			$attachment_id = attachment_url_to_postid($image_url);

			if ($attachment_id) {
				$image_ids[] = $attachment_id;
			}
		}

		return $image_ids;
	}

	protected function get_image_aspect_ratio($image_id) {
		// Get the attachment metadata
		$image_meta = wp_get_attachment_metadata($image_id);
	
		// Check if metadata exists and includes width and height
		if ($image_meta && isset($image_meta['width']) && isset($image_meta['height'])) {
			$width = $image_meta['width'];
			$height = $image_meta['height'];
	
			// Calculate the aspect ratio
			$aspect_ratio = $width / $height;
	
			return $aspect_ratio; // Return the aspect ratio as a float
		}
	
		return false; // Return false if metadata is missing or invalid
	}

	protected function getTransation($object_id, $type)
	{
		if ($type == 2) {
			$translated_term_id = pll_get_term($object_id, 'fr');
			$link = get_term_link($translated_term_id);
		} else {
			$translated_post_id = pll_get_post($object_id, 'fr');
			$link = get_permalink($translated_post_id);
		}
		return $link;
	}

	protected function render()
	{
		$pod_name = 'home_page_tile';

		$params = array(
			'limit' => 24
		);

		$pods = pods($pod_name, $params);

		$lang = pll_current_language();

		$index = 1;
		if ($pods->total() > 0) {
			// Loop through each Pod item
			// echo '<script type="text/javascript" src="' . plugin_dir_url(file: __FILE__) . '../assets/js/masonry.pkgd.min.js"></script>';
			// echo '<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>';
			echo '<div class="masonry-grid-mob">';
			while ($pods->fetch()) {
				$type = $pods->field('object');
				$post_id = $pods->field('ID');
				$object_id = '';
				$link = '';
				if ($type == 0) {
					$object_id = get_post_meta($post_id, 'product', true);
					$link = ($lang == 'fr') ? $this->getTransation($object_id, $type) : get_permalink($object_id);
				} else if ($type == 1) {
					$object_id = get_post_meta($post_id, 'architecture', true);
					$link = ($lang == 'fr') ? $this->getTransation($object_id, $type) : get_permalink($object_id);
				} else {
					$object_id = get_post_meta($post_id, 'collection', true);
					$link = ($lang == 'fr') ? $this->getTransation((int)$object_id, $type) : get_term_link((int)$object_id);
				}

				$image = $pods->field('image');
				$image_id = $image['ID'];

				switch ($index) {
					case '1': // 3
						$width = 47;
						$height = 48;
						$top = 50;
						$left = 0;
						break;
					case '2': // 1
						$width = 32;
						$height = 48;
						$top = 0;
						$left = 0;
						break;
					case '3': // 2
						$width = 62;
						$height = 48;
						$top = 0;
						$left = 34;
						break;
					case '4': // 4
						$width = 47;
						$height = 59;
						$top = 50;
						$left = 49;
						break;
					case '5': // 7
						$width = 96;
						$height = 62;
						$top = 162;
						$left = 0;
						break;
					case '6': // 12
						$width = 96;
						$height = 60;
						$top = 339;
						$left = 0;
						break;
					case '7': // 6
						$width = 47;
						$height = 48;
						$top = 112;
						$left = 49;
						break;
					case '8': // 9
						$width = 47;
						$height = 49;
						$top = 226;
						$left = 49;
						break;
					case '9': // 5
						$width = 47;
						$height = 60;
						$top = 100;
						$left = 0;
						break;
					case '10': // 22
						$width = 96;
						$height = 60;
						$top = 678;
						$left = 0;
						break;
					case '11': // 8
						$width = 47;
						$height = 60;
						$top = 226;
						$left = 0;
						break;
					case '12': // 10
						$width = 47;
						$height = 49;
						$top = 288;
						$left = 0;
						break;
					case '13': // 14
						$width = 61;
						$height = 49;
						$top = 401;
						$left = 35;
						break;
					case '14': // 15
						$width = 48;
						$height = 49;
						$top = 452;
						$left = 0;
						break;
					case '15': // 11
						$width = 47;
						$height = 60;
						$top = 277;
						$left = 49;
						break;
					case '16': // 19
						$width = 96;
						$height = 60;
						$top = 565;
						$left = 0;
						break;
					case '17': // 13
						$width = 33;
						$height = 49;
						$top = 401;
						$left = 0;
						break;
					case '18': // 18
						$width = 46;
						$height = 49;
						$top = 514;
						$left = 50;
						break;
					case '19': // 23
						$width = 96;
						$height = 60;
						$top = 740;
						$left = 0;
						break;
					case '20': // 20
						$width = 47;
						$height = 49;
						$top = 627;
						$left = 0;
						break;
					case '21': // 21
						$width = 47;
						$height = 49;
						$top = 627;
						$left = 49;
						break;
					case '22': // 16
						$width = 46;
						$height = 60;
						$top = 452;
						$left = 50;
						break;
					case '23': // 24
						$width = 96;
						$height = 60;
						$top = 802;
						$left = 0;
						break;
					case '24': // 17
						$width = 48;
						$height = 60;
						$top = 503;
						$left = 0;
						break;
				}

				// if ($index == 1 || $index == 13) {
				// echo '<div class="masonry-grid">';
				// }

				$image = wp_get_attachment_image_src($image_id, 'full'); ?>
				<a href="<?php echo $link ?>" class="grid-item index-<?php echo $index ?>"
					style="width: <?php echo $width ?>vw; height: <?php echo $height ?>vw; top: <?php echo $top ?>vw; left: <?php echo $left ?>vw; background-image: url('<?php echo $image[0] ?>')">
				</a>

				<?php
				// if ($index == 12 || $index == 24) {
				// echo '</div>';
				// }

				$index++;
			}
			echo '</div>';
		}
		?>

		<style type="text/css">
			/* ---- grid ---- */
			.masonry-grid-mob {
				max-width: 100%;
				position: relative;
				height: 800vw;
				overflow: hidden;
				margin-top: 2vw;
				margin-left: 2vw;
			}

			.masonry-grid-mob .grid-item {
				background-color: #ccc;
				position: absolute;
				background-size: cover;
				transition: filter 0.5s ease;
				/* Smooth transition */
				background-repeat: no-repeat;
				background-position: center;
			}

			.masonry-grid-mob .grid-item:hover {
				filter: grayscale(100%);
			}
		</style>
		<?php
	}
}