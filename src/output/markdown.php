<?php
function generate_markdown_output( $data, $style, $description ) {
	if ( is_string( $data ) ) {
		return $data;
	}
	if ( false !== strpos( $style, 'table' ) || false !== strpos( $style, 'default' ) ) {
		return generate_markdown_table( $data, $description, $style );
	}

	if ( false !== strpos( $style, 'list' ) ) {
		return generate_markdown_list( $data, $description, $style );
	}
}