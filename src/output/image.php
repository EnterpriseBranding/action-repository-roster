<?php
function image_style( $type ) {
	$css = <<<CSS
.user-info {
	display: inline-block;
	margin-right: 15px;
	text-align: center;
	margin-bottom: 15px;
}

.user-info sub{
display: block;
}

.user-info img {
	border-radius: 50%;
	max-width: 75px
}
CSS;

	switch ( $type ) {

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
	$svg .= '</div></foreignObject></svg>';
	return $svg;
}