<?php
/**
 * Functions.php snippets for the Series Archive feature.
 * Add these to your existing functions.php — do not replace the whole file.
 */


// ── 1. Shortcode ──────────────────────────────────────────────────────────────
//
// Renders the template part via [series_by_project] in the block template.
// ob_start/ob_get_clean captures the PHP output so the shortcode can return
// it as a string (required by the Shortcode API).

add_shortcode( 'series_by_project', function () {
	ob_start();
	include get_theme_file_path( 'template-parts/series-by-project.php' );
	return ob_get_clean();
} );


// ── 2. Stylesheet ─────────────────────────────────────────────────────────────
//
// Loads archive-series.css only on the Series CPT archive.
// get_theme_file_uri() checks child theme first, then parent.

add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_post_type_archive( 'series' ) ) {
		return;
	}

	wp_enqueue_style(
		'series-archive',
		get_theme_file_uri( 'archive-series.css' ),
		[ 'global-styles' ],
		wp_get_theme()->get( 'Version' )
	);
} );


// /**
//  * Enqueue Series Archive stylesheet.
//  *
//  * Loads only on the Series CPT archive and on any page
//  * assigned the "Series — All Projects" page template.
//  */
// function crp_enqueue_series_archive_styles() {

//     $is_series_archive = is_post_type_archive( 'series' );

//     $is_series_page_template = is_page_template( 'archive-series.php' );

//     if ( ! $is_series_archive && ! $is_series_page_template ) {
//         return;
//     }

//     wp_enqueue_style(
//         'series-archive',                         // handle
//         get_theme_file_uri( '/assets/styles/archive-series.css' ), // URL to the file
//         [ 'global-styles' ],                       // load after theme global styles
//         wp_get_theme()->get( 'Version' )           // version = theme version
//     );
// }
// add_action( 'wp_enqueue_scripts', 'crp_enqueue_series_archive_styles' );

// // Load the archive-series template
// add_filter( 'theme_page_templates', function( $templates ) {
//     $templates['archive-series.php'] = __( 'Series — All Projects', 'your-theme-textdomain' );
//     return $templates;
// } );


// add_action( 'wp_head', function() {
//     $templates = wp_get_theme()->get_page_templates();
//     echo '<!-- PAGE TEMPLATES: ' . print_r( $templates, true ) . ' -->';
// } );