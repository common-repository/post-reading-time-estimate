<?php

if ( ! function_exists( 'prt_get_option_value' ) ) :

	function prt_get_option_value( $option_key ) {
		global $post_reading_time_estimate;

		$fields = $post_reading_time_estimate->get_fields();
		$prefix = $post_reading_time_estimate->prefix;

		return ( false !== get_option( $prefix . $option_key ) ) ? get_option( $prefix . $option_key ) : $fields[$option_key]['default'];
	}

endif;