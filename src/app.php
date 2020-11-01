<?php
global $repo_info, $repo;
$_ENV['GITHUB_TOKEN'] = '25c2a62b1182fe9041921789ea9366faf1eb35d9';

define( 'APP_PATH', __DIR__ . '/' );
#define( 'WORK_DIR', gh_env( 'GITHUB_WORKSPACE' ) );
define( 'WORK_DIR', APP_PATH . '/work/' );
$repos = array( 'octocat/hello-world' );
require_once APP_PATH . '../../actions-toolkit/toolkit/gh-api.php';
require_once APP_PATH . 'functions.php';


foreach ( $repos as $repo ) {
	$repo_info = $github_api->decode( $github_api->get( sprintf( 'repos/%s', $repo ) ) );
	require_once APP_PATH . 'forks.php';
}


