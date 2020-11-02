<?php
/**
 * Possible Options
 * noimage,noname,square,rounded,italic,imglarge,imgsmall
 *
 * @param $type
 *
 * @return false|string
 */
function image_style( $type ) {
	$css  = file_get_contents( APP_PATH . 'output/image-default.css' );
	$type = explode( ',', trim( $type ) );

	foreach ( $type as $style ) {
		switch ( $style ) {
			case 'no-image':
				$css .= '.user-info img {display:none !important;}';
				break;
			case 'no-name':
				$css .= '.user-info sub {display:none !important;}';
				break;
			case 'square':
				$css .= '.user-info img {border-radius:10px;}';
				break;
			case 'rounded':
				$css .= '.user-info img {border-radius:50%;}';
				break;
			case 'italic':
				$css .= '.user-info sub {font-style:italic;}';
				break;
			case 'img-large':
				$css .= '.user-info img {max-width: 100px}';
				break;
			case 'img-small':
				$css .= '.user-info img {max-width: 50px}';
				break;
		}
	}

	return $css;
}

function generate_image( $data, $style, $desc ) {
	$css        = image_style( $style );
	$svg_width  = '800';
	$svg_height = 'auto';
	$svg        = '<svg fill="none" viewBox="0 0 ' . $svg_width . ' ' . $svg_height . '" width="' . $svg_width . '" height="' . $svg_height . '" xmlns="http://www.w3.org/2000/svg">';
	$svg        .= '<foreignObject width="100%" height="100%"> <div xmlns="http://www.w3.org/1999/xhtml">';
	$svg        .= <<<HTML
<style>$css</style>
<div class="container">
HTML;

	foreach ( $data as $info ) {
		$image = convert_image_to_base64( $info['avatar_url'] );
		$svg   .= <<<HTML
<div class="user-info">
	<img src="{$image}" alt="@{$info['owner']}"/>
	<sub><b>@{$info['owner']}</b></sub> 
</div>
HTML;
	}
	$svg .= '</div>';

	$svg .= '<div class="description">' . $desc . '</div>';
	$svg .= '</div ></foreignObject ></svg > ';
	return $svg;
}