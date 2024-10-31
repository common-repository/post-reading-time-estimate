<?php
class Post_Reading_Time_Estimate {

	public $prefix = '_prt_';

	public function initialize() {
		require_once( get_reading_time_estimate_inc_folder() . '/helpers.php' );
		require_once( get_reading_time_estimate_inc_folder() . '/classes/class-admin.php' );
		require_once( get_reading_time_estimate_inc_folder() . '/classes/class-controller.php' );
	}
	
	public function get_fields() {
		return array(
			'insert_before_content' => array(
				'type' => 'checkbox',
				'value_type' => 'integer',
				'section' => 'general',
				'label' => __( 'Insert before content', 'post-reading-time-estimate' ),
				'default' => 1,
				'sanitize' => 'intval'
			),
			'insert_on_archives' => array(
				'type' => 'checkbox',
				'value_type' => 'integer',
				'section' => 'general',
				'label' => __( 'Insert on archives', 'post-reading-time-estimate' ),
				'default' => 0,
				'sanitize' => 'intval'
			),
			'label_before' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'general',
				'label' => __( 'Label before', 'post-reading-time-estimate' ),
				'default' => __( 'Reading Time: ', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'label_after' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'general',
				'label' => __( 'Label after', 'post-reading-time-estimate' ),
				'default' => __( 'read', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'wpm' => array(
				'type' => 'number',
				'value_type' => 'integer',
				'section' => 'general',
				'label' => __( 'Words Per Minute (WPM)', 'post-reading-time-estimate' ),
				'default' => 300,
				'sanitize' => 'intval'
			),
			'post_types' => array(
				'type' => 'checkbox_post_types',
				'value_type' => 'string',
				'section' => 'general',
				'label' => __( 'Enable on', 'post-reading-time-estimate' ),
				'default' => array( 'post', 'page' ),
				'sanitize' => ''
			),
			'custom_styles' => array(
				'type' => 'checkbox',
				'value_type' => 'integer',
				'section' => 'visuals',
				'label' => __( 'Use custom styles', 'post-reading-time-estimate' ),
				'default' => 0,
				'sanitize' => 'intval'
			),
			'font_weight' => array(
				'type' => 'select',
				'value_type' => 'string',
				'section' => 'visuals',
				'label' => __( 'Font Weight', 'post-reading-time-estimate' ),
				'default' => 400,
				'options' => array(
					300 => __( 'Light', 'post-reading-time-estimate' ),
					400 => __( 'Normal', 'post-reading-time-estimate' ),
					700 => __( 'Bold', 'post-reading-time-estimate' )
				),
				'sanitize' => 'sanitize_text_field'
			),
			'font_style' => array(
				'type' => 'select',
				'value_type' => 'string',
				'section' => 'visuals',
				'label' => __( 'Font Style', 'post-reading-time-estimate' ),
				'default' => 'normal',
				'options' => array(
					'normal' => __( 'Normal', 'post-reading-time-estimate' ),
					'italic' => __( 'Italic', 'post-reading-time-estimate' ),
				),
				'sanitize' => 'sanitize_text_field'
			),
			'color_text' => array(
				'type' => 'color_picker',
				'value_type' => 'string',
				'section' => 'visuals',
				'label' => __( 'Text', 'post-reading-time-estimate' ),
				'default' => '#000000',
				'sanitize' => 'sanitize_text_field'
			),
			'text_align' => array(
				'type' => 'select',
				'value_type' => 'string',
				'section' => 'visuals',
				'label' => __( 'Text Align', 'post-reading-time-estimate' ),
				'default' => 'normal',
				'options' => array(
					'left' => __( 'Left', 'post-reading-time-estimate' ),
					'center' => __( 'Center', 'post-reading-time-estimate' ),
					'right' => __( 'Right', 'post-reading-time-estimate' ),
				),
				'sanitize' => 'sanitize_text_field'
			),
			'lang_minute_1' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'language',
				'label' => __( '1 / one', 'post-reading-time-estimate' ),
				'default' => __( 'minute', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'lang_minute_2' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'language',
				'label' => __( '2 / two', 'post-reading-time-estimate' ),
				'default' => __( 'minutes', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'lang_minute_3' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'language',
				'label' => __( '3 / three', 'post-reading-time-estimate' ),
				'default' => __( 'minutes', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'lang_minute_4' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'language',
				'label' => __( '4 / four', 'post-reading-time-estimate' ),
				'default' => __( 'minutes', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
			'lang_minutes' => array(
				'type' => 'text',
				'value_type' => 'string',
				'section' => 'language',
				'label' => __( '5 / five and more', 'post-reading-time-estimate' ),
				'default' => __( 'minutes', 'post-reading-time-estimate' ),
				'sanitize' => 'sanitize_text_field'
			),
		);
	}

}

if ( ! function_exists( 'Reading_Time_Estimate' ) ) :

	function Reading_Time_Estimate() {
		global $post_reading_time_estimate;
	
		if ( is_null( $post_reading_time_estimate ) ) {
			$post_reading_time_estimate = new Post_Reading_Time_Estimate();
		}
	
		return $post_reading_time_estimate;
	}
	
endif;