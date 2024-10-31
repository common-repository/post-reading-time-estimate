<?php
class Post_Reading_Time_Estimate_Controller {

	private static $instance;

	private $supported_post_types;
	
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'the_content', array( $this, 'display_reading_time' ) );
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );

		add_shortcode( 'reading_time', array( $this, 'handle_shortcode' ) );

		$this->supported_post_types = prt_get_option_value( 'post_types' );
	}

	public function get_reading_time( $post_id ) {
		$post_content = get_post_field( 'post_content', $post_id );
		$post_content = strip_shortcodes( $post_content );
		$reading_time = str_word_count( $post_content ) / prt_get_option_value( 'wpm' );

		if ( 1 > $reading_time ) {
			$reading_time = 1;
		}

		return ceil( $reading_time );
	}

	public function print_styles() {
		if ( prt_get_option_value( 'custom_styles' ) ) :
		?>
		<style>
			p.post-reading-time-estimate__paragraph {
				font-weight: <?php echo prt_get_option_value( 'font_weight' ); ?> !important;
				font-style: <?php echo prt_get_option_value( 'font_style' ); ?> !important;
				color: <?php echo prt_get_option_value( 'color_text' ); ?> !important;
				text-align: <?php echo prt_get_option_value( 'text_align' ); ?> !important;
			}
		</style>
		<?php
		endif;
	}

	public function is_supported_post_type( $post_type ) {
		if ( in_array( $post_type, $this->supported_post_types ) ) {
			return true;
		}

		return false;
	}

	public function display_reading_time( $content ) {
		$post_id = get_the_ID();
		$post_type = get_post_type();
		$show_on_archives = prt_get_option_value( 'insert_on_archives' );
		$supported_post_types = prt_get_option_value( 'post_types' );

		if ( ! $post_id ) {
			return;
		}

		if ( ! $this->is_supported_post_type( $post_type ) ) {
			return $content;
		}

		if ( is_archive() || is_front_page() && ! $show_on_archives ) {
			return $content;
		}

		$reading_time = $this->get_reading_time( $post_id );
		$reading_time_content = $this->get_time_html( $reading_time );

		return $reading_time_content . $content;
	}

	public function get_time_html( $reading_time ) {
		$label_before = prt_get_option_value( 'label_before' );
		$label_after = prt_get_option_value( 'label_after' );

		switch( $reading_time ) {
			case 1:
				$minutes = prt_get_option_value( 'lang_minute_1' );
				break;
			case 2:
				$minutes = prt_get_option_value( 'lang_minute_2' );
				break;
			case 3:
				$minutes = prt_get_option_value( 'lang_minute_3' );
				break;
			case 4:
				$minutes = prt_get_option_value( 'lang_minute_4' );
				break;
			default:
				$minutes = prt_get_option_value( 'lang_minutes' );
				break;
		}

		$reading_time_content = sprintf(
			'<p class="%s">%s %d %s %s</p>',
			'post-reading-time-estimate__paragraph',
			$label_before,
			$reading_time,
			$minutes,
			$label_after
		);

		return $reading_time_content;
	}

	public function handle_shortcode() {
		$post_id = get_the_ID();
		$post_type = get_post_type();

		if ( ! $post_id ) {
			return;
		}

		if ( ! $this->is_supported_post_type( $post_type ) ) {
			return;
		}

		$reading_time = $this->get_reading_time( $post_id );
		$reading_time_content = $this->get_time_html( $reading_time );

		return $reading_time_content;
	}
}

Post_Reading_Time_Estimate_Controller::instance();