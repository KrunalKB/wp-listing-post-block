<?php
/**
 * Plugin Name:       Wp Latest Post Block
 * Description:       Display and filter latest posts.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Krunal Bhimajiyani
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       latest-posts
 *
 * @package           create-block
 */

function render_wp_latest_post_block( $attributes ) {
	$args         = array(
		'posts_per_page' => $attributes['numberOfPosts'],
		'post_status'    => 'publish',
	);
	$recent_posts = get_posts( $args );
	$posts        = '<ul ' . get_block_wrapper_attributes() . ' >';
	foreach ( $recent_posts as $post ) {
		$title     = get_the_title( $post );
		$title     = $title ? $title : __( '(No title)', 'latest-posts' );
		$permalink = get_permalink( $post );
		$excerpt   = get_the_excerpt( $post );
		$posts    .= '<li>';
		if ( $attributes['displayFeaturedImage'] && has_post_thumbnail( $post ) ) {
			$posts .= get_the_post_thumbnail( $post, 'medium' );
		}
		$posts .= '<h5><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h5>';
		$posts .= '<time datetime="' . esc_attr( get_the_date( 'c', $post ) ) . '">' . esc_html( get_the_date( '', $post ) ) . '</time>';

		if ( ! empty( $excerpt ) ) {
			$posts .= '<p>' . esc_html( $excerpt ) . '</p>';
		}
		$posts .= '</li>';
	}
	$posts .= '</ul>';
	return $posts;
}

function create_block_wp_latest_post_block_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'render_wp_latest_post_block',
		)
	);
}
add_action( 'init', 'create_block_wp_latest_post_block_block_init' );
