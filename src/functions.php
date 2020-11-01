<?php
require_once APP_PATH . 'output/table.php';

function generate_output( $type, $data, $description ) {
	if ( 'markdown' === $type ) {
		return generate_markdown_table( $data, $description );
	}
}

function save_output( $type, $output, $save_to, $key ) {
	if ( 'markdown' === $type ) {
		$ex            = file_get_contents( WORK_DIR . $save_to );
		$find_regex    = '/(?P<start><!--\s(?:' . $key . ')\:(?:START|start)\s-->)(?:\n|)(?:[\s\S]*?)(?:\n|)(?P<end><!--\s(?:' . $key . ')\:(?:END|end)\s-->)/m';
		$replace_regex = '/(<!--\s(?:' . $key . ')\:(?:START|start)\s-->)(?:\n|)([\s\S]*?)(?:\n|)(<!--\s(?:' . $key . ')\:(?:END|end)\s-->)/';
		$str_find      = 'PLACEHOLDER_REPLACE:' . rand( 1, 1000 );

		preg_match_all( $find_regex, $ex, $matches, PREG_SET_ORDER, 0 );
		$matches = ( isset( $matches[0] ) ) ? $matches[0] : array( '', '', '', '' );

		$content = preg_replace( $replace_regex, $str_find, $ex );
		$output  = <<<HTML
{$matches['start']}
{$output}
{$matches['end']}
HTML;

		$content = str_replace( $str_find, $output, $content );
		file_put_contents( WORK_DIR . $save_to, $content );
	}
}