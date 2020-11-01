<?php
function generate_markdown_table( $data, $show_description = true, $per_row = 7 ) {
	$html = '<table><tbody><tr>';
	$i    = 0;
	foreach ( $data as $info ) {
		if (0 !== $i && 0 === $i % $per_row ) {
			$html .= '</tr><tr>';
		}

		$html .= <<<HTML
<td align="center"> <a href="{$info['html_url']}" rel="nofollow"> 
<img src="{$info['avatar_url']}" alt="@{$info['owner']}" style="max-width:100%;" width="100px;"> <br/> <sub><b>@{$info['owner']}</b></sub> </a>
</td>
HTML;
		$i++;
	}

	$html .= '</tr></tbody></table>';
	if ( false !== $show_description ) {
		$html .= <<<HTML
<p align="center">{$show_description}</p>
HTML;
	}

	return $html;
}