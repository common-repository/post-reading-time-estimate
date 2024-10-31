<div class="wrap">
	<h2><?php _e( 'Post Reading Time Estimate Options', 'post-reading-time-estimate' ); ?></h2>

	<form method="post" action="options.php">
		<p>
			<em><?php _e( 'Did you know', 'post-reading-time-estimate' ); ?>:</em> 
			<?php 
			printf( 
				__( 'You can also use shortcode %s to display reading time anywhere in the post body.', 'post-reading-time-estimate' ),
				'<code>[reading_time]</code>'
			); 
			?>
		</p>
		<?php settings_fields( 'post-reading-time-estimate-options' ); ?>
		<?php do_settings_sections( 'post-reading-time-estimate-options' ); ?>

		<p>
			<?php 
			printf( 
				__( 'Looking for help or want to suggest more features? Ask on <a href="%s">support forums</a> or <a href="%s">email us</a>.', 'post-reading-time-estimate' ),
				'https://wordpress.org/plugins/post-reading-time-estimate/support',
				'mailto:support@wpfoodie.com?subject=Reading Time Estimate Support Query'
			); 
			?>

		<?php submit_button(); ?>
	</form>
</div>