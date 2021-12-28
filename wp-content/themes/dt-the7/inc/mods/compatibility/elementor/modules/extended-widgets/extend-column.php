<?php

namespace The7\Mods\Compatibility\Elementor\Modules\Extended_Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Extend_Column {

	public function __construct() {
		// inject controls
		add_action( 'elementor/element/before_section_end', [ $this, 'update_controls' ], 20, 3 );
	}

	public function update_controls( $widget, $section_id, $args ) {
		$widgets = [
			'column' => [
				'section_name' => [ 'layout' ],
			],
		];

		if ( ! array_key_exists( $widget->get_name(), $widgets ) ) {
			return;
		}

		$curr_section = $widgets[ $widget->get_name() ]['section_name'];
		if ( ! in_array( $section_id, $curr_section ) ) {
			return;
		}

		$widget->start_injection( [
			'of' => '_inline_size',
			'at' => 'before',
		] );

		$widget->add_responsive_control( 'the7_auto_width', [
			'label'                => esc_html__( 'Auto-width', 'the7mk2' ),
			'type'                 => Controls_Manager::SELECT,
			'default'              => '',
			'options'              => [
				''         => esc_html__( 'Default', 'the7mk2' ),
				'none'     => esc_html__( 'None', 'the7mk2' ),
				'minimize' => esc_html__( 'Minimize', 'the7mk2' ),
				'maximize' => esc_html__( 'Maximize', 'the7mk2' ),
			],
			'selectors'            => [
				'div{{WRAPPER}}' => '{{VALUE}}',
			],
			'selectors_dictionary' => [
				'none'     => 'max-width: initial; flex: none',
				'minimize' => 'max-width: fit-content; flex: 0 0 auto;',
				'maximize' => 'max-width: initial; flex: 1 0 0;',
			],
			'classes'              => 'the7-control',
		] );

		$widget->add_control( 'the7_auto_width_notice', [
			'raw'             => esc_html__( 'Minimize/Maximize is enabled, column width setting will be ignored', 'the7mk2' ),
			'type'            => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'the7_auto_width' => [ 'minimize', 'maximize' ],
			],
		] );

		$widget->end_injection();
	}
}
