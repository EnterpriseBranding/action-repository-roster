<?php
global $github_api, $repo_info;

$api_stars_per_page = 100;
$stars              = gh_input( 'STARS', 'README.md' );

if ( false !== $stars ) {
	$stars             = ( true === $stars ) ? 'README.md' : $stars;
	$stars_output_type = gh_input( 'STARS_OUTPUT_TYPE', 'markdown' );
	$stars_show_count  = gh_input( 'STARS_COUNTS', 7 );
	$stars_description = gh_input( 'STARS_DESCRIPTION', '' );
	$total_stars_count = ( isset( $repo_info->stargazers_count ) && ! empty( $repo_info->stargazers_count ) ) ? $repo_info->stargazers_count : '';

	if ( empty( $total_stars_count ) ) {
		$html = '';
	} else {
		$total_stars_page = ceil( $total_stars_count / $api_stars_per_page );

		function fetch_recent_stars( $repo, $limit = 10, $page = '1' ) {
			global $github_api, $api_stars_per_page;
			$data     = $github_api->decode( $github_api->get( sprintf( 'repos/%s/stargazers?per_page=' . $api_stars_per_page . '&page=' . $page, $repo ) ) );
			$response = array();
			foreach ( $data as $_star ) {
				if ( ! isset( $_star->login ) ) {
					continue;
				}

				$response[] = array(
					'owner'      => $_star->login,
					'avatar_url' => $_star->avatar_url,
					'html_url'   => $_star->html_url,
				);
			}
			return $response;
		}

		$status = true;
		$retun  = array();
		$page   = $total_stars_page;
		while ( $status ) {
			if ( 0 === $page ) {
				$status = false;
				break;
			}
			print_r( $page );
			$new = fetch_recent_stars( $repo, $stars_show_count, $page-- );
			if ( empty( $new ) ) {
				$status = false;
				break;
			}
			print_r( $new );
			print_r( $page );
			krsort( $new );
			foreach ( $new as $owner_info ) {
				$retun[] = $owner_info;

				if ( count( $retun ) == $stars_show_count ) {
					$status = false;
					break;
				}
			}

			if ( count( $retun ) == $stars_show_count ) {
				$status = false;
				break;
			}

			if ( $page < 0 || $page === 0 ) {
				$status = false;
				break;
			}
		}

		if ( false !== $stars_description ) {
			if ( empty( $stars_description ) ) {
				if ( $total_stars_count == $stars_show_count ) {
					$stars_description = '<i><b>' . $total_stars_count . '</b> people have starred this repository</i>';
				} elseif ( $stars_show_count < $total_stars_count ) {
					$stars_description = '<i><b>' . ( $total_stars_count - $stars_show_count ) . '</b> Others have starred this repository</i>';
				} else {
					$stars_description = '<i><b>' . ( $total_stars_count - $stars_show_count ) . '</b> Others have starred this repository</i>';
				}
			}
			$stars_description = str_replace( '[count]', $total_stars_count, $stars_description );
		}
		$html = generate_output( $stars_output_type, $retun, $stars_description );
	}


	$file = save_output( $stars_output_type, $html, $stars, 'REPOSITORY_STARS' );
	shell_exec( 'git add -f ' . $file );
	shell_exec( 'git commit -m "[Repository Roster] Updated :star2: Stargazers Information" ' );
}