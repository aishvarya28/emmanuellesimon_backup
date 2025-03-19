<?php

namespace Product_Gallery_Sldier;

class Bootstrap {
	/**
	 * Initializes a singleton instance
	 *
	 * @return $instance
	 */
	public static function get_instance() {

		/**
		 * @var mixed
		 */
		static $instance = false;
		if ( ! $instance ) {
			$instance = new self();
		}
		return $instance;
	}
	private function __construct() {
		Product::get_instance();
		new Options();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 90 );
		if ( time() > strtotime( get_option( 'ciwpgs_installed' ) . ' + 5 Days' ) ) {
			add_action( 'admin_notices', array( $this, 'review' ), 10 );
		}
		add_action( 'csf_options_after', array( $this, 'update_notice_option' ) );
		add_action( 'csf_options_before', array( $this, 'update_notice_ltd' ) );
		add_action( 'admin_init', array( $this, 'wcpg_param_check' ), 10 );
		add_action( 'plugin_action_links_' . CIPG_FILE, array( $this, 'wpgs_plugin_row_meta' ), 90 );
		add_action( 'admin_init', array( 'PAnD', 'init' ) );
	}
	// notice for option page
	/**
	 * @return null
	 */
	function update_notice_ltd() {
		$currentScreen = get_current_screen();
		
		if ( 'codeixer_page_cix-gallery-settings' != $currentScreen->id ) {
			return;
		}
		?>
		<div class="notice ciplugin-review">
		<p style="font-size: 15px; margin: 0 0 12px 0;">Thank you for using <strong>Product Gallery Slider for WooCommerce</strong>! As a special offer, we're giving you <strong>LIFETIME access</strong> for the price of our yearly plan - <span style="background: #f1f1f1; padding: 3px 8px; border-radius: 3px;">only $59</span> (normally $59/year)!</p>

<ul style="margin: 0 0 12px 0; padding-left: 20px;">
 	<li>🔥 <strong>Same price as 1 year</strong> - use forever!</li>
 	<li>🚀 Premium updates &amp; support included</li>
 	<li>💡 14-day money back guarantee</li>
</ul>
<a class="button button-primary" style="margin-right: 10px;" target="_" href="https://codeixer.com/forever-deal">🔥 Grab Lifetime Access Now</a>
<p style="margin: 10px 0 0 0; font-size: 13px; color: red;">🚨 This special price is available for the next 100 licenses only. After that, it will no longer be offered. - Claim yours now!</p>
		</div>
		<?php
	}
	function update_notice_option() {
		$currentScreen = get_current_screen();
		
		if ( 'codeixer_page_cix-gallery-settings' != $currentScreen->id ) {
			return;
		}
		?>
		<div class="notice ciplugin-review">
		<p><img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"><strong style="font-size: 19px; margin-bottom: 5px; display: inline-block;" ><?php echo __( 'Thanks for using Product gallery slider for WooCommerce.', 'woo-product-gallery-slider' ); ?></strong><br> <?php _e( 'If you have a moment, we’d truly appreciate your support—please consider leaving us a 5-star review on WordPress.org. 🙌', 'woo-product-gallery-slider' ); ?></p>
		<p class="dfwc-message-actions">
			<a style="margin-right:5px;" href="https://wordpress.org/support/plugin/woo-product-gallery-slider/reviews/#new-post" target="_blank" class="button"><?php _e( 'Happy To Help', 'woo-product-gallery-slider' ); ?></a>
			
		</p>
		</div>
		<?php
	}
	/**
	 * Admin scripts/styles
	 *
	 * @return void
	 */
	public function admin_scripts() {
	}

	/**
	 * Leave Review Notice
	 *
	 * @return void
	 */
	public function review() {
		$dismiss_parm    = array( 'wcpg-review-dismiss' => '1' );
		$ciwg_maybelater = array( 'wcpg-later-dismiss' => '1' );

		if ( get_option( 'wcpg_plugin_review' ) || get_transient( 'wpgs-review-later' ) ) {
			return;
		}
		?>
		<div class="notice ciplugin-review">
		<p><img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"><strong style="font-size: 19px; margin-bottom: 5px; display: inline-block;" ><?php echo __( 'Thanks for using Product gallery slider for WooCommerce.', 'woo-product-gallery-slider' ); ?></strong><br> <?php _e( 'If you can spare a minute, please help us by leaving a 5 star review on WordPress.org.', 'woo-product-gallery-slider' ); ?></p>
		<p class="dfwc-message-actions">
			<a style="margin-right:5px;" href="https://wordpress.org/support/plugin/woo-product-gallery-slider/reviews/#new-post" target="_blank" class="button button-primary button-primary"><?php _e( 'Happy To Help', 'woo-product-gallery-slider' ); ?></a>
			<a style="margin-right:5px;" href="<?php echo esc_url( add_query_arg( $ciwg_maybelater ) ); ?>" class="button button-alt"><?php _e( 'Maybe later', 'woo-product-gallery-slider' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( $dismiss_parm ) ); ?>" class="dfwc-button-notice-dismiss button button-link"><?php _e( 'Hide Notification', 'woo-product-gallery-slider' ); ?></a>
		</p>
		</div>
		<?php
	}


	/**
	 * simple dismissable logic
	 *
	 * @return void
	 */
	public function wcpg_param_check() {
		if ( isset( $_GET['wcpg-review-dismiss'] ) && 1 == $_GET['wcpg-review-dismiss'] ) {
			update_option( 'wcpg_plugin_review', 1 );
		}
		if ( isset( $_GET['dfwc-banner'] ) && 1 == $_GET['dfwc-banner'] ) {
			update_option( 'dfwc-banner', 1 );
		}
		if ( isset( $_GET['wcpg-later-dismiss'] ) && 1 == $_GET['wcpg-later-dismiss'] ) {
			set_transient( 'wpgs-review-later', 1, 4 * DAY_IN_SECONDS );
		}
	}
}
