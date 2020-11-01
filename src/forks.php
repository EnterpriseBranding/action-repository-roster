<?php
global $github_api, $repo_info;

$api_fork_per_page = 100;
$forks             = gh_input( 'FORK', 'README.md' );

if ( false !== $forks ) {
	$forks            = ( true === $forks ) ? 'README.md' : $forks;
	$fork_output_type = gh_input( 'FORK_OUTPUT_TYPE', 'markdown' );
	$fork_show_count  = gh_input( 'FORK_COUNTS', 7 );
	$fork_description = gh_input( 'FORK_DESCRIPTION', '' );


	$total_forks_count = ( isset( $repo_info->forks_count ) && ! empty( $repo_info->forks_count ) ) ? $repo_info->forks_count : '';

	function fetch_recent_forks( $repo, $limit = 10, $page = '1' ) {
		global $github_api, $api_fork_per_page;
		$data     = $github_api->decode( $github_api->get( sprintf( 'repos/%s/forks?sort=newest&per_page=' . $api_fork_per_page . '&page=' . $page, $repo ) ) );
		$response = array();
		foreach ( $data as $fork ) {
			if ( ! isset( $fork->owner ) ) {
				continue;
			}

			$response[] = array(
				'owner'      => $fork->owner->login,
				'avatar_url' => $fork->owner->avatar_url,
				'html_url'   => $fork->owner->html_url,
			);

			if ( 0 !== $response && count( $response ) == $limit ) {
				break;
			}
		}
		return $response;
	}

	$base_response = fetch_recent_forks( $repo, $fork_show_count );

	if ( count( $base_response ) < $fork_show_count ) {
		$status = true;
		$page   = 2;
		while ( $status ) {
			$new = fetch_recent_forks( $repo, $fork_show_count, $page++ );
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
		}
	}

	if ( false !== $fork_description ) {
		if ( empty( $fork_description ) ) {
			if ( $total_forks_count == $fork_show_count ) {
				$fork_description = '<i><b>' . $total_forks_count . '</b> people have forked this repository</i>';
			} elseif ( $fork_show_count < $total_forks_count ) {
				$fork_description = '<i><b>' . ( $total_forks_count - $fork_show_count ) . '</b> Others have forked this repository</i>';
			} else {
				$fork_description = '<i><b>' . ( $total_forks_count - $fork_show_count ) . '</b> Others have forked this repository</i>';
			}
		}
		$fork_description = str_replace( '[count]', $total_forks_count, $fork_description );
	}

	$html = generate_output( $fork_output_type, $base_response, $fork_description );
	save_output( $fork_output_type, $html, $forks, 'REPOSITORY_FORKS' );
}