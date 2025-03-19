<?php
class Elementor_Emma_Home_Page_Gallery_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'emma_home_page_gallery';
	}

	public function get_title()
	{
		return esc_html__('Emma Home Page Gallery', 'elementor-addon');
	}

	public function get_style_depends()
	{
		return ['emmaHomePageGallery'];
	}

	public function get_script_depends()
	{
		return ['emmaHomePageGallery'];
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
				'label' => esc_html__('Emma Home Page Gallery', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Emma Home Page Gallery', 'elementor-addon'),
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

	protected function getTransation($object_id, $type)
	{
		if ($type == 'collection') {
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
					case '1':
						$width = 29;
						$height = 30;
						$top = 0;
						$left = 0;
						break;
					case '2':
						$width = 12;
						$height = 17;
						$top = 0;
						$left = 30;
						break;
					case '3':
						$width = 23;
						$height = 17;
						$top = 0;
						$left = 43;
						break;
					case '4':
						$width = 12;
						$height = 17;
						$top = 0;
						$left = 67;
						break;
					case '5':
						$width = 17;
						$height = 12;
						$top = 0;
						$left = 80;
						break;
					case '6':
						$width = 19;
						$height = 12;
						$top = 18;
						$left = 30;
						break;
					case '7':
						$width = 29;
						$height = 30;
						$top = 18;
						$left = 50;
						break;
					case '8':
						$width = 17;
						$height = 17;
						$top = 13;
						$left = 80;
						break;
					case '9':
						$width = 12;
						$height = 17;
						$top = 31;
						$left = 0;
						break;
					case '10':
						$width = 23;
						$height = 17;
						$top = 31;
						$left = 13;
						break;
					case '11':
						$width = 12;
						$height = 17;
						$top = 31;
						$left = 37;
						break;
					case '12':
						$width = 17;
						$height = 17;
						$top = 31;
						$left = 80;
						break;
					case '13':
						$width = 16;
						$height = 12;
						$top = 0;
						$left = 0;
						break;
					case '14':
						$width = 30;
						$height = 30;
						$top = 0;
						$left = 17;
						break;
					case '15':
						$width = 12;
						$height = 17;
						$top = 0;
						$left = 48;
						break;
					case '16':
						$width = 23;
						$height = 17;
						$top = 0;
						$left = 61;
						break;
					case '17':
						$width = 12;
						$height = 17;
						$top = 0;
						$left = 85;
						break;
					case '18':
						$width = 16;
						$height = 17;
						$top = 13;
						$left = 0;
						break;
					case '19':
						$width = 19;
						$height = 12;
						$top = 18;
						$left = 48;
						break;
					case '20':
						$width = 29;
						$height = 30;
						$top = 18;
						$left = 68;
						break;
					case '21':
						$width = 16;
						$height = 17;
						$top = 31;
						$left = 0;
						break;
					case '22':
						$width = 12;
						$height = 17;
						$top = 31;
						$left = 17;
						break;
					case '23':
						$width = 24;
						$height = 17;
						$top = 31;
						$left = 30;
						break;
					case '24':
						$width = 12;
						$height = 17;
						$top = 31;
						$left = 55;
						break;
				}

				if ($index == 1 || $index == 13) {
					echo '<div class="masonry-grid">';
				}

				$image = wp_get_attachment_image_src($image_id, 'full'); ?>
				<a href="<?php echo $link ?>" class="grid-item index-<?php echo $index ?>"
					style="width: <?php echo $width ?>vw; height: <?php echo $height ?>vw; top: <?php echo $top ?>vw; left: <?php echo $left ?>vw; background-image: url('<?php echo $image[0] ?>')">
				</a>

				<?php
				if ($index == 12 || $index == 24) {
					echo '</div>';
				}

				$index++;
			}
		}
		?>

		<style type="text/css">
			/* ---- grid ---- */
			.masonry-grid {
				max-width: 100%;
				position: relative;
				height: 48vw;
				overflow: hidden;
				margin: 1vw;
			}

			.masonry-grid .grid-item {
				background-color: #ccc;
				position: absolute;
				background-size: cover;
				transition: filter 0.5s ease;
				/* Smooth transition */
				background-repeat: no-repeat;
				background-position: center;
			}

			.masonry-grid .grid-item:hover {
				filter: grayscale(100%);
			}
		</style>
		<?php
	}
}