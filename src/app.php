<?php
require_once '/gh-toolkit/php.php';
require_once '/gh-toolkit/gh-api.php';

global $repo_info, $repo;

define( 'APP_PATH', __DIR__ . '/' );

define( 'WORK_DIR', gh_env( 'GITHUB_WORKSPACE' ) );

$repos = array( gh_env( 'GITHUB_REPOSITORY' ) );

require_once APP_PATH . 'functions.php';

foreach ( $repos as $repo ) {
	try {
		$repo_info = $github_api->decode( $github_api->get( 'repos/' . $repo ) );
		require_once APP_PATH . 'forks.php';
		require_once APP_PATH . 'stars.php';
	} catch ( \Milo\Github\RateLimitExceedException $exception ) {
		print_r( $exception );
	}
}


