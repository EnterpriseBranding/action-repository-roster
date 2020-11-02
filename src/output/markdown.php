<?php
function generate_markdown_output( $data, $style, $description ) {
	if ( false !== strpos( $style, 'table' ) ) {
		echo generate_markdown_table( $data, $description, $style );
	}
}