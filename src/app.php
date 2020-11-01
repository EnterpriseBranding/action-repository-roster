<?php
require_once '/gh-toolkit/php.php';
require_once '/gh-toolkit/gh-api.php';

global $repo_info, $repo;

define( 'APP_PATH', __DIR__ . '/' );

define( 'WORK_DIR', gh_env( 'GITHUB_WORKSPACE' ) );

$repos = array( gh_env( 'GITHUB_REPOSITORY' ) );


require_once APP_PATH . 'functions.php';

foreach ( $repos as $repo ) {
	var_dump( sprintf( 'repos/%s', $repo ) );
	$repo_info = $github_api->decode( $github_api->get( sprintf( 'repos/%s', $repo ) ) );

	var_dump( $repo_info );
	exit;
	#require_once APP_PATH . 'forks.php';
	#require_once APP_PATH . 'stars.php';
}


