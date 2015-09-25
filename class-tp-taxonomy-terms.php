<?php
/**
 * Plugin Name: Taxonomy terms
 * Description: Taxonomy terms widget.
 *
 * Plugin URI: https://github.com/trendwerk/widget-taxonomy-terms
 * 
 * Author: Trendwerk
 * Author URI: https://github.com/trendwerk
 * 
 * Version: 1.0.0
 */

class TP_Taxonomy_Terms_Plugin {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'localization' ) );
	}

	/**
	 * Load localization
	 */
	function localization() {
		load_muplugin_textdomain( 'widget-taxonomy-terms', dirname( plugin_basename( __FILE__ ) ) . '/assets/lang/' );
	}

} new TP_Taxonomy_Terms_Plugin;

class TP_Taxonomy_Terms extends WP_Widget {

	function __construct() {
		parent::__construct( 'TP_Taxonomy_Terms', __( 'Term list', 'widget-taxonomy-terms' ), array(
			'description' => __( 'List of terms from a given taxonomy', 'widget-taxonomy-terms' )
		) );
	}
	
	function form( $instance ) {
		$defaults = array(
			'title'    => '',
			'taxonomy' => '',
		);

		$instance = wp_parse_args( $instance, $defaults );

		$taxonomies = get_taxonomies( array(
			'public'   => true,
			'_builtin' => false,
		), 'objects' );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<strong><?php _e( 'Title' ); ?></strong><br />
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		
		<?php 
		if( $taxonomies ) { 
			?>

			<p>
				<label>
					<strong><?php _e( 'Taxonomy', 'widget-taxonomy-terms' ); ?></strong><br />
					<select class="widefat" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
						<?php foreach( $taxonomies as $taxonomy ) { ?>
							<option <?php selected( $taxonomy->name, $instance['taxonomy'] ); ?> value="<?php echo $taxonomy->name; ?>"><?php echo $taxonomy->label; ?> (<?php echo $taxonomy->name; ?>)</option>
						<?php } ?>
					</select>
				</label>
			</p>
			
			<?php
		}
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		
		if( $terms = get_terms( $instance['taxonomy'] ) ) {
			echo $before_widget;
				echo $before_title . $instance['title'] . $after_title;
				?>

			    <div class="widget-inner">
					<ul class="term-list">
						<?php foreach( $terms as $term ) { ?>
							<li>
								<a href="<?php echo get_term_link( $term ); ?>">
									<?php echo $term->name; ?>
								</a>
							</li>
						<?php } ?>
					</ul>
			    </div>

				<?php
			echo $after_widget;
		}
	}
}

add_action( 'widgets_init', function() {
	return register_widget( 'TP_Taxonomy_Terms' );
} );
