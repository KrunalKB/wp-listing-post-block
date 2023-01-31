<?php
/**
 * Wp Listing Post Block
 *
 * @package           wlpb-block
 * @author            Krunal Bhimajiyani
 * @copyright         2022 Krunal Bhimajiyani
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       Wp Listing Post Block
 * Description:       Gutenberg Block to list posts with additional features.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Krunal Bhimajiyani
 * Author URI:        https://profiles.wordpress.org/krunal265/
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       latest-posts
 */

/**
 * Callback function to render post data.
 *
 * @param array $attributes provide information about data stored by a block.
 */
function wlpb_render_wp_latest_post_block( $attributes ) {
	$args = array(
		'posts_per_page' => $attributes['numberOfPosts'],
		'post_status'    => 'publish',
		'order'          => $attributes['order'],
		'orderBy'        => $attributes['orderBy'],
	);
	if ( isset( $attributes['categories'] ) ) {
		$args['category__in'] = array_column( $attributes['categories'], 'id' );
	}
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

/**
 * Registering the block.
 */
function wplb_create_block_wp_latest_post_block_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'wlpb_render_wp_latest_post_block',
		)
	);
}
add_action( 'init', 'wplb_create_block_wp_latest_post_block_block_init' );
