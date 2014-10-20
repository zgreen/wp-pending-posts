<?php
/**
 * Plugin Name:   WP Pending Posts
 * Plugin URI:    https://github.com/zgreen/wp-pending-posts
 * Text Domain:   wpzgreen_pending_posts
 * Domain Path:   /languages
 * Description:   Displays a "Pending posts" dashboard widget for admin-level users.
 * Author:        Zach Green
 * Version:       0.1.0
 * Licence:       GPL2
 * Author URI:    http://zgreen.github.io
 * Last Change:   10/08/2014
 */

defined('ABSPATH') or die("No script kiddies, please!");

// Add dashboard widgets
function wpzgreen_add_pending_widget() {
	// Add a pending posts dashboard widget
	wp_add_dashboard_widget(
		'wpzgreen_pending_dashboard_widget',         // Widget slug.
		'Urgent and/or pending posts',         // Title.
		'wpzgreen_pending_dashboard_widget_function' // Display function.
	);
}

add_action( 'wp_dashboard_setup', 'wpzgreen_add_pending_widget' );

// Build the Pending posts dashboard widget
function wpzgreen_pending_dashboard_widget_function() {

	$args_urgent = array(
	  'post_type' => 'any',
	  'orderby'   => 'title',
	  'order'     => 'ASC',
	  'post_status' => 'pending',
	  'posts_per_page' => 10,
	  'meta_key'		=> 'urgent',
		'meta_value'		=> 1
	);

	$args_pending = array(
	  'post_type' => 'any',
	  'orderby'   => 'title',
	  'order'     => 'ASC',
	  'post_status' => 'pending',
	  'posts_per_page' => 10,
	  'meta_key'		=> 'urgent',
		'meta_value'		=> 0
	);

	$urgent_posts = new WP_Query ( $args_urgent );

	echo  '<table class="widefat">' .
						'<thead>' .
							'<tr>' .
								'<th class="row-title">Post title</th>' .
								'<th>Post author</th>' .
							'</tr>' .
						'</thead>' .
						'<tbody>';

	// The Loops
	if ( $urgent_posts->have_posts() ) {
		
		while ( $urgent_posts->have_posts() ) {
			$urgent_posts->the_post();
			echo  '<tr class="form-invalid">' .
							'<td class="row-title"><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></td>' .
							'<td>' . get_the_author() . '</td>' .
						'</tr>';
		}
	} else {
		echo 	'<tr>' .
						'<td>There are no urgent posts</td>' .
					'</tr>';
	}

	wp_reset_postdata();

	$pending_posts = new WP_Query( $args_pending );

	if ( $pending_posts->have_posts() ) {
		while ( $pending_posts->have_posts() ) {
			$pending_posts->the_post();
			echo  '<tr>' .
							'<td class="row-title"><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></td>' .
							'<td>' . get_the_author() . '</td>' .
						'</tr>';
		}
		echo    '</tbody>' .
					'</table>';
	} else {
		echo '<tr>' .
						'<td>There are no pending posts</td>' .
					'</tr>' .
				'</tbody>' .
			'</table>';
	}

	wp_reset_postdata();
	
}

?>
