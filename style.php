<?php 

function my_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
    return $options[$option];
    }
 
    return $default;
}


function mv_styles_method() {
	wp_enqueue_style(
		'mv-custom-style',
		MEDIAVINE_URL . 'style.css'
	);
		$custom_css = '';

		$diy_bg_color = my_get_option( 'color-title', 'metabox_defaults', '' );
		$custom_css .= ".diy { background-color: {$diy_bg_color}!important; }";

		$diy_item_size = my_get_option( 'item-size', 'metabox_defaults', '' );
		$custom_css .= ".diy-item { font-size: {$diy_item_size}px!important; line-height: {$diy_item_size}px!important; }";

		$diy_item_color = my_get_option( 'value-color', 'metabox_defaults', '' );
		$custom_css .= ".diy-item { color: {$diy_item_color}!important; }";

		$custom_css_setting = my_get_option( 'custom-css', 'metabox_defaults', '' );
		$custom_css .= "{$custom_css_setting}";

        wp_add_inline_style( 'mv-custom-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'mv_styles_method' );
