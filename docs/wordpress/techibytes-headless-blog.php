<?php
/**
 * Plugin Name: Techibytes Headless Blog
 * Description: Publishes posts to techibytesmedia.com/blog. Refreshes the site's blog cache when posts change and redirects this WordPress front end to the main site so content is only indexed there.
 * Version: 1.0.0
 *
 * Install: copy this file to wp-content/mu-plugins/ on the WordPress site
 * (create the folder if needed), then add these lines to wp-config.php:
 *
 *     define( 'TECHIBYTES_SITE_URL', 'https://www.techibytesmedia.com' );
 *     define( 'TECHIBYTES_BLOG_WEBHOOK_SECRET', 'same value as WORDPRESS_WEBHOOK_SECRET in the Laravel .env' );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function techibytes_site_url(): string {
	return defined( 'TECHIBYTES_SITE_URL' ) ? untrailingslashit( TECHIBYTES_SITE_URL ) : '';
}

/**
 * Tell the Laravel site to drop its cached posts so changes appear immediately.
 */
function techibytes_refresh_blog(): void {
	$site   = techibytes_site_url();
	$secret = defined( 'TECHIBYTES_BLOG_WEBHOOK_SECRET' ) ? TECHIBYTES_BLOG_WEBHOOK_SECRET : '';

	if ( '' === $site || '' === $secret ) {
		return;
	}

	wp_remote_post(
		$site . '/blog/refresh',
		array(
			'timeout'  => 5,
			'blocking' => false,
			'headers'  => array( 'X-Webhook-Secret' => $secret ),
		)
	);
}

// Fires on every save, including edits to an already published post
add_action(
	'transition_post_status',
	function ( string $new_status, string $old_status, WP_Post $post ): void {
		if ( 'post' === $post->post_type && ( 'publish' === $new_status || 'publish' === $old_status ) ) {
			techibytes_refresh_blog();
		}
	},
	10,
	3
);

add_action(
	'before_delete_post',
	function ( int $post_id ): void {
		if ( 'post' === get_post_type( $post_id ) ) {
			techibytes_refresh_blog();
		}
	}
);

/**
 * "View post" links and SEO plugin canonicals point at the main site.
 * Drafts keep their normal links so previews still work.
 */
add_filter(
	'post_link',
	function ( string $permalink, WP_Post $post ): string {
		$site = techibytes_site_url();

		if ( '' === $site || 'publish' !== $post->post_status ) {
			return $permalink;
		}

		return $site . '/blog/' . $post->post_name;
	},
	10,
	2
);

/**
 * Send visitors of this WordPress front end to the main site.
 * The admin, login, REST API and post previews are unaffected.
 */
add_action(
	'template_redirect',
	function (): void {
		$site = techibytes_site_url();

		if ( '' === $site || is_preview() || is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( is_singular( 'post' ) ) {
			wp_redirect( $site . '/blog/' . get_post_field( 'post_name', get_queried_object_id() ), 301 );
			exit;
		}

		wp_redirect( $site . '/blog', 301 );
		exit;
	}
);
