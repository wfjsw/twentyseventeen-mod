<?php

function twentyseventeen_mod_load_styles() {
    // Theme stylesheet.
    $wp_styles = wp_styles();
    $parenthandle = 'twentyseventeen-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();

    $wp_styles->registered[$parenthandle]->src = get_template_directory_uri() . '/style.css';

    // wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
    //     array(),
    //     $theme->parent()->get('Version')
    // );
    wp_enqueue_style( 'twentyseventeen-mod-style', get_stylesheet_uri(), array($parenthandle), '20200718.2' );
}

add_action('wp_enqueue_scripts', 'twentyseventeen_mod_load_styles', 20);

function twentyseventeen_mod_excerpt_length($length)
{
    return 140;
}

function twentyseventeen_mod_excerpt_paragraph_extract($text = '', $post = null)
{

    if ($text == '') {
        $post = get_post( $post );
        $content = get_the_content( '', false, $post );
        $content = strip_shortcodes( $content );
        $content = excerpt_remove_blocks( $content );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );
        $excerpt_length = intval( _x( '55', 'excerpt_length' ) );
        $excerpt_length = (int) apply_filters( 'excerpt_length', $excerpt_length );
        $excerpt    = ( preg_match( sprintf( '~(<p>.+?</p>){%d}~i', 1 ), $content, $matches ) ) ? $matches[ 0 ] : $content;
        // $excerpt = preg_replace( "/<img[^>]+\>/i", "", $excerpt );
        $excerpt = wp_trim_words( $excerpt, $excerpt_length, ' &hellip;' );
        $excerpt = "<p>$excerpt</p>";
    } else {
        $excerpt = $text;
    }
    $excerpt_more = apply_filters( 'excerpt_more', '' );
    $excerpt = $excerpt . $excerpt_more;
    return $excerpt;
}


function twentyseventeen_mod_excerpt_more( $more_string ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Post title. */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return $link;
}

function twenty_seventeen_mod_setup_theme() {
    add_filter('excerpt_length', 'twentyseventeen_mod_excerpt_length', 11);
    remove_filter('get_the_excerpt', 'wp_trim_excerpt', 10);
    add_filter('the_excerpt', 'twentyseventeen_mod_excerpt_paragraph_extract', 10, 2);
    // remove_filter( 'excerpt_more', 'wp_embed_excerpt_more', 20 );
    remove_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );
    add_filter( 'excerpt_more', 'twentyseventeen_mod_excerpt_more', 21 );
}
add_action( 'after_setup_theme', 'twenty_seventeen_mod_setup_theme' );

function twentyseventeen_mod_remove_ie_styles() {
    wp_dequeue_style('twentyseventeen-ie8');
    wp_dequeue_style('twentyseventeen-ie9');
    wp_dequeue_script('html5');
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_mod_remove_ie_styles', 11 );
