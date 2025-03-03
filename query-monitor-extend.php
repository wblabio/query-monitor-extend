<?php
/**
 * Query Monitor Extend plugin for WordPress
 *
 * @package query-monitor-extend
 * @link    https://github.com/crstauf/query-monitor-extend
 * @author  Caleb Stauffer <develop@calebstauffer.com>
 *
 * Plugin Name: Query Monitor Extend
 * Plugin URI: https://github.com/crstauf/query-monitor-extend
 * Description: Enhancements and extensions for the awesome Query Monitor plugin by John Blackbourn
 * Version: 1.1
 * Author: Caleb Stauffer
 * Author URI: http://develop.calebstauffer.com
 * QM tested up to: 3.6.7
*/

if ( !defined( 'ABSPATH' ) || !function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if (
	   'cli' === php_sapi_name()
	|| ( defined( 'DOING_CRON'   ) && DOING_CRON   )
	|| ( defined( 'QM_DISABLED'  ) && QM_DISABLED  )
	|| ( defined( 'QMX_DISABLED' ) && QMX_DISABLED )
)
	return;

add_filter( 'plugin_row_meta', function ( $meta, $file ) {
	if ( class_exists( 'QueryMonitor' ) )
		return $meta;

	if ( 'query-monitor-extend/query-monitor-extend.php' !== $file )
		return $meta;

	$first = array_shift( $meta );

	array_unshift(
		$meta,
		$first,
		sprintf(
			'Requires <a href="%1$s" rel="noopener noreferrer">Query Monitor</a>',
			'https://wordpress.org/plugins/query-monitor/'
		)
	);

	return $meta;
}, 10, 2 );

if ( !class_exists( 'QueryMonitor' ) )
	return;

$qmx_dir = dirname( __FILE__ );

require_once "{$qmx_dir}/classes/Plugin.php";

foreach ( array( 'QueryMonitorExtend', 'Collectors', 'Collector', 'Output' ) as $qmx_class ) {
	require_once "{$qmx_dir}/classes/{$qmx_class}.php";
}

include_once "{$qmx_dir}/output/AdminBar.php";

QueryMonitorExtend::init( __FILE__ );
?>
