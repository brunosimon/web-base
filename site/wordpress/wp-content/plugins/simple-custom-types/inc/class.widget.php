<?php
/**
 * Recent_Objects widget class
 *
 */
class Widget_Recent_Objects extends WP_Widget {
	
	function Widget_Recent_Objects() {
		$widget_ops = array(
			'classname' => 'widget_recent_objects',
			'description' => __( "The most recent objects on your blog", 'simple-customtypes')
		);
		
		$this->WP_Widget('recent-objects', __('Recent Objects', 'simple-customtypes'), $widget_ops);
		$this->alt_option_name = 'widget_recent_objects';
		
		add_action( 'save_post', 	array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}
	
	function widget( $args, $instance ) {
		// Clean post type arg, use post if nothing is specified
		$instance['post_type'] = empty($instance['post_type']) ? 'post' : strip_tags($instance['post_type']);
		
		// Try catch ?
		$cache = wp_cache_get('widget_recent_objects', 'widget');
		
		if ( !is_array($cache) )
			$cache = array();
		
		if ( isset($cache[$instance['post_type']][$args['widget_id']]) ) {
			echo $cache[$instance['post_type']][$args['widget_id']];
			return;
		}
		
		extract($args);
		
		// Build HTML
		ob_start();
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] ) {
			$number = 10;
		} elseif ( $number < 1 ) {
			$number = 1;
		} elseif ( $number > 15 ) {
			$number = 15;
		}
		
		$r = new WP_Query( array(
			'showposts' => $number,
			'post_type' => $instance['post_type'],
			'nopaging' => 0,
			'post_status' => 'publish',
			'caller_get_posts' => 1
		));
		
		if ( $r->have_posts() ) :
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title; 
				?>
				<ul>
					<?php while ( $r->have_posts() ) : $r->the_post(); ?>
						<li>
							<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
							<?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php 
			echo $after_widget;
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		endif;
		
		$cache[$instance['post_type']][$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_recent_objects', $cache, 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title']  	= strip_tags($new_instance['title']);
		$instance['post_type']  = strip_tags($new_instance['post_type']);
		$instance['number'] 	= (int) $new_instance['number'];
		
		$this->flush_widget_cache();
		
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_objects']) )
			delete_option( 'widget_recent_objects' );
		
		return $instance;
	}
	
	function flush_widget_cache() {
		wp_cache_delete( 'widget_recent_objects', 'widget' );
	}
	
	function form( $instance ) {
		$title 		= isset($instance['title']) ? esc_attr($instance['title']) : '';
		$post_type 	= isset($instance['post_type']) ? esc_attr($instance['post_type']) : 'post';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'simple-customtypes'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post type:', 'simple-customtypes'); ?></label>
			<select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
				<?php
				foreach( $this->getObjectTypes() as $type ) {
					echo '<option '.selected($type->name, $post_type, false).' value="'.esc_attr($type->name).'">'.esc_html($type->label).'</option>' . "\n";
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of objects to show:', 'simple-customtypes'); ?></label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
			<small><?php _e('(at most 15)', 'simple-customtypes'); ?></small>
		</p>
		<?php
	}
	
	/**
	 *
	 * @param $key
	 * @return string/array
	 */
	function getObjectTypes( $key = '' ) {
		$object_types = get_post_types( array(), 'objects' );
		$object_types = apply_filters( 'staxo-object-types', $object_types, $key );
		
		if ( isset($object_types[$key]) ) {
			return $object_types[$key];
		}
		
		return $object_types;
	}
}
?>