<?php
function generate_markdown_output( $data, $style, $description ) {
	if ( false !== strpos( $style, 'table' ) ) {
		return generate_markdown_table( $data, $description, $style );
	}

	if ( false !== strpos( $style, 'list' ) ) {
		echo generate_markdown_list( $data, $description, $style );
	}
}