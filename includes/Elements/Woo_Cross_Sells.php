<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Cross_Sells extends Widget_Base {

	public function get_name() {
		return 'eael-woo-cross-sells';
	}

	public function get_title() {
		return esc_html__( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-woo-cross-sells';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [
			'cart',
			'woo cart',
			'cross sells',
			'woo cart cross sells',
			'ea cross sells',
			'woocommerce',
			'woocommerce cross sells',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-cross-sells/';
	}

	protected function register_controls() {
		if ( ! class_exists( 'woocommerce' ) ) {
			$this->start_controls_section(
				'eael_global_warning',
				[
					'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
				]
			);

			$this->add_control(
				'eael_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.',
						'essential-addons-for-elementor-lite' ),
					'content_classes' => 'eael-warning',
				]
			);

			$this->end_controls_section();

			return;
		}

		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'ea_section_woo_cross_sells_general_settings',
			[
				'label' => esc_html__( 'General Settings', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_cross_sells_layout',
			[
				'label'       => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'style-1',
				'label_block' => false,
				'options'     => apply_filters( 'eael/woo-cart/layout', [
					'style-1' => esc_html__( 'Style 1', 'essential-addons-for-elementor-lite' ),
					'style-2' => esc_html__( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'style-3' => esc_html__( 'Style 3', 'essential-addons-for-elementor-lite' ),
					'style-4' => esc_html__( 'Style 4', 'essential-addons-for-elementor-lite' ),
				] ),
			]
		);

		$this->add_control( 'orderby', [
			'label'   => __( 'Order By', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'none'       => __( 'None', 'essential-addons-for-elementor-lite' ),
				'title'      => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'id'         => __( 'ID', 'essential-addons-for-elementor-lite' ),
				'date'       => __( 'Date', 'essential-addons-for-elementor-lite' ),
				'modified'   => __( 'Modified', 'essential-addons-for-elementor-lite' ),
				'menu_order' => __( 'Menu Order', 'essential-addons-for-elementor-lite' ),
				'price'      => __( 'Price', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'none',

		] );

		$this->add_control( 'order', [
			'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'asc'  => __( 'Ascending', 'essential-addons-for-elementor-lite' ),
				'desc' => __( 'Descending', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'desc',
		] );

		$this->add_control( 'products_count', [
			'label'   => __( 'Products Count', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 4,
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
		] );

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style General Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_cart_general_style',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ea_woo_cart_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-woo-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$orderby  = $settings['orderby'];
		$order    = $settings['order'];
		$limit    = $settings['products_count'];

		// Handle product query.
		$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

		foreach ( $cross_sells as $cross_sell ) {
			echo $cross_sell->get_title();
			echo $cross_sell->get_price();
			echo '<br>';
		}

	}
}