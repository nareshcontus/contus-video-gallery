<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress Video Gallery Video Category Widget.
  Version: 2.6
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

class Widget_ContusVideoCategory_init extends WP_Widget {

	function Widget_ContusVideoCategory_init() {
		$widget_ops = array( 'classname' => 'Widget_ContusVideoCategory_init ', 'description' => 'Contus Video Categories' );
		$this->WP_Widget( 'Widget_ContusVideoCategory_init', 'Contus Video Category', $widget_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Video Categories', 'show' => '3' ) );
		## These are our own options
		$title = esc_attr( $instance['title'] );
		$show  = esc_attr( $instance['show'] );
		?>
		<p><label for='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>'>Title: <input class='widefat' id='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>' name='<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>' type='text' value='<?php echo esc_html( $title ); ?>' /></label></p>
		<p><label for='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>'>Show: <input class='widefat' id='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>' name='<?php echo esc_html( $this->get_field_name( 'show' ) ); ?>' type='text' value='<?php echo esc_html( $show ); ?>' /></label></p>
				<?php
			}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show']  = $new_instance['show'];
		return $instance;
	}

	function widget( $args, $instance ) {
		## and after_title are the array keys." - These are set up by the theme
		extract( $args, EXTR_SKIP );
		$title = empty( $instance['title']) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
		## WIDGET CODE GOES HERE
		global $wpdb;
		## These are our own options
		$show = $instance['show']; ## # of Posts we are showing
		?>
		<!-- Recent videos For Getting The Page Id More and Video-->
		<?php
		$moreName = $wpdb->get_var( 'SELECT ID from ' . $wpdb->prefix . 'posts WHERE post_content="[videomore]" and post_status="publish" and post_type="page" limit 1' );
		## For video category
		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1 LIMIT ' . $show;
		$features = $wpdb->get_results( $sql );
		$moreCategories   = $wpdb->get_results( 'SELECT COUNT(*) AS contus FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1' );
		$countCategories  = $moreCategories[0]->contus;
		$div = '';
		$more_videos_link = get_morepage_permalink( $moreName, 'categories' );
		$div .= '<div id="videos-category"  class="sidebar-wrap "> <h3 class="widget-title"><a href="' . $more_videos_link . '">' . $title . '</a></h3>';
		$div .= '<ul class="ulwidget clearfix">';
		## were there any posts found?
		if ( ! empty( $features ) ) {
			## posts were found, loop through them
			foreach ( $features as $feature ) {
				$fetched = $feature->playlist_name;
				$playlist_slugname = $feature->playlist_slugname;
				$playlist_id = $feature->pid;
				$div .= '<li>';
				$playlist_url = get_playlist_permalink( $moreName, $playlist_id, $playlist_slugname );
				$div .= '<a class="videoHname "  href="' . $playlist_url . '">' . $fetched . '</a>';
				$div .= '</li>';
			}
		} else {
			$div .= '<li>' . __( 'No Categories', 'video_gallery' ) . '</li>';
		}
		## end list
		if ( ( $show < $countCategories ) ) {
			$div .= '<li><div class="right video-more"><a href="' . $more_videos_link . '">' . __( 'More Categories', 'video_gallery' ) . ' &#187;</a></div></li>';
		}
		$div .= '</ul></div>';
		echo balanceTags( $div );
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget("Widget_ContusVideoCategory_init" );' ) ); ##adding product tag widget
?>