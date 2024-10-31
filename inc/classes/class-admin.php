<?php
class Post_Reading_Time_Estimate_Admin {

	private $settings_name = 'post-reading-time-estimate-options';
	
	private static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		global $post_reading_time_estimate;
		$this->prefix = $post_reading_time_estimate->prefix;

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'add_sections_and_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function add_sections_and_settings() {
		add_settings_section(
			$this->settings_name . '_section_general',
			__( 'General', 'post-reading-time-estimate' ),
			'',
			$this->settings_name
		);

		add_settings_section(
			$this->settings_name . '_section_visuals',
			__( 'Visuals', 'post-reading-time-estimate' ),
			'',
			$this->settings_name
		);

		add_settings_section(
			$this->settings_name . '_section_language',
			__( 'Language', 'post-reading-time-estimate' ),
			'',
			$this->settings_name
		);

		$this->add_fields();
	}

	public function add_fields() {
		global $post_reading_time_estimate;
		
		$fields = $post_reading_time_estimate->get_fields();

		foreach ( $fields as $key => $field ) {
			register_setting(
				$this->settings_name,
				$this->prefix . $key,
				array(
					'type' => $field['value_type'],
					'sanitize_callback' => $field['sanitize']
				)
			);

			$setting_options = array(
				'label_for' => $this->prefix . $key,
				'id' => $this->prefix . $key,
				'value' => ( false !== get_option( $this->prefix . $key ) ) ? get_option( $this->prefix . $key ) : $field['default'],
			);

			if ( 'select' === $field['type'] ) {
				$setting_options['options'] = $field['options'];
			}

			add_settings_field(
				$this->prefix . $key,
				$field['label'],
				array( $this, "render_{$field['type']}_control" ),
				$this->settings_name,
				$this->settings_name . '_section_' . $field['section'],
				$setting_options
			);
		}
	}

	public function render_text_control( $options ) {
		?>
		<input name="<?php echo $options['id']; ?>" id="<?php echo $options['id']; ?>" type="text" value="<?php echo $options['value']; ?>">
		<?php
	}

	public function render_number_control( $options ) {
		?>
		<input name="<?php echo $options['id']; ?>" id="<?php echo $options['id']; ?>" type="number" value="<?php echo $options['value']; ?>">
		<?php
	}

	public function render_checkbox_control( $options ) {
		?>
		<input name="<?php echo $options['id']; ?>" id="<?php echo $options['id']; ?>" type="checkbox" value="1"<?php checked( 1 === intval( $options['value'] ) ); ?>>
		<?php
	}

	public function render_select_control( $options ) {
		?>
		<select name="<?php echo $options['id']; ?>" id="<?php echo $options['id']; ?>">
			<?php foreach( $options['options'] as $value => $option_label ) : ?>
				<option value="<?php echo $value; ?>"<?php selected( $value === $options['value'] ); ?>><?php echo $option_label; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function render_color_picker_control( $options ) {
		?>
		<input name="<?php echo $options['id']; ?>" id="<?php echo $options['id']; ?>" type="text" class="rte-color-picker" value="<?php echo $options['value']; ?>">
		<?php
	}

	public function render_checkbox_post_types_control( $options ) {
		$post_types = get_post_types( array(
			'public' => true
		) );

		foreach ( $post_types as $post_type ) :
		?>
			<p>
				<label>
					<input name="<?php echo $options['id']; ?>[]" type="checkbox" value="<?php echo $post_type; ?>"<?php checked( in_array( $post_type, $options['value'] ) ); ?>> <?php echo $post_type; ?>
				</label>
			</p>
		<?php
		endforeach;
	}

	public function add_settings_page() {
		add_options_page(
			__( 'Post Reading Time Estimate Options', 'post-reading-time-estimate' ),
			__( 'Reading Time Options', 'post-reading-time-estimate' ),
			'manage_options',
			$this->settings_name,
			array( $this, 'options_page' )
		);
	}

	public function options_page() {
		require( get_reading_time_estimate_inc_folder() . '/templates/options.php' );
	}

	public function enqueue_scripts() {
		wp_register_script(
			'post-reading-time-estimate-admin',
			get_reading_time_estimate_plugin_url() . '/assets/js/admin-options.js',
			array( 'jquery', 'wp-color-picker' ),
			READING_TIME_ESTIMATE_VERSION
		);

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'post-reading-time-estimate-admin' );
	}
}

Post_Reading_Time_Estimate_Admin::instance();