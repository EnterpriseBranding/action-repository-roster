<?php
global $github_api, $repo_info;

$api_fork_per_page = 100;
$forks             = gh_input( 'FORK', 'README.md' );

if ( false !== $forks ) {
	$forks             = ( true === $forks ) ? 'README.md' : $forks;
	$fork_output_type  = gh_input( 'FORK_OUTPUT_TYPE', 'image' );
	$fork_output_style = gh_input( 'FORK_OUTPUT_STYLE', 'table' );
	$fork_show_count   = gh_input( 'FORK_COUNTS', 7 );
	$fork_description  = gh_input( 'FORK_DESCRIPTION', '' );
	$total_forks_count = ( isset( $repo_info->forks_count ) && ! empty( $repo_info->forks_count ) ) ? $repo_info->forks_count : '';

	if ( empty( $total_forks_count ) ) {
		$base_response = '<i>Nobody has forked this repository <b>yet</b>.</i>';
	} else {
		$base_response = fetch_recent_forks( $repo, $fork_show_count );
		if ( count( $base_response ) < $fork_show_count ) {
			$status = true;
			$page   = 2;
			while ( $status ) {
				$new = fetch_recent_forks( $repo, $fork_show_count, $page++ );
				if ( empty( $new ) ) {
					$status = false;
					break;
				}
				foreach ( $new as $owner_info ) {
					$base_response[] = $owner_info;

					if ( count( $base_response ) == $fork_show_count ) {
						$status = false;
						break;
					}
				}

				if ( count( $base_response ) == $fork_show_count ) {
					$status = false;
					break;
				}

				if ( $page > $total_forks_count || $page === $total_forks_count ) {
					$status = false;
					break;
				}
			}
		}

		if ( false !== $fork_description ) {
			if ( empty( $fork_description ) ) {
				if ( $total_forks_count == $fork_show_count ) {
					$fork_description = '<i><b>' . $total_forks_count . '</b> people have forked this repository</i>';
				} elseif ( $fork_show_count < $total_forks_count ) {
					$fork_description = '<i><b>' . ( $total_forks_count - $fork_show_count ) . '</b> Others have forked this repository</i>';
				} else {
					$fork_description = '<i><b>' . $total_forks_count . '</b> have forked this repository</i>';
				}
			}
			$fork_description = str_replace( '[count]', $total_forks_count, $fork_description );
		}
	}

	$html = generate_output( 'forks', $fork_output_type, $base_response, $fork_output_style, $fork_description );
	$file = save_output( $html, $forks, 'REPOSITORY_FORKS' );
	gh_commit( $file, '[Repository Roster] Updated :cyclone: Latest Forked Users' );
}