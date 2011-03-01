<?php
/*
Plugin Name: WP Db Abstraction
Plugin URI:  http://sourceforge.net/projects/wp-sqlsrv/
Description: Data-Access abstraction and SQL abstraction support for Wordpress.
             Must be installed as a Must Use plugin, uses db.php drop-in for
             database connectivity.  Currently supports PDO, sqlsrv, mssql
             and mysql database extensions and sql abstraction for SQL Server.
             Can be easily extended for other database extensions and sql dialects.
Version: 1.0.1
Author: Anthony Gentile and Elizabeth M Smith
Author URI:  http://wordpress.visitmix.com/
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Filter function so we use our ping to make sure you don't see wordpress core
 * upgrades until they're tested
 */
function wp_db_abstraction_check_core_upgrade() {
    global $wp_version, $wpdb, $wp_local_package;
    $php_version = phpversion();

    $locale = apply_filters( 'core_version_check_locale', get_locale() );

    if ( method_exists( $wpdb, 'db_version' ) )
        $mysql_version = preg_replace('/[^0-9.].*/', '', $wpdb->db_version());
    else
        $mysql_version = 'N/A';

    if ( is_multisite( ) ) {
        $user_count = get_user_count( );
        $num_blogs = get_blog_count( );
        $wp_install = network_site_url( );
        $multisite_enabled = 1;
    } else {
        $user_count = count_users( );
        $multisite_enabled = 0;
        $num_blogs = 1;
        $wp_install = home_url( '/' );
    }

    $local_package = isset( $wp_local_package )? $wp_local_package : '';
    $url = "http://wordpress.visitmix.com/api/wp/?version=$wp_version&php=$php_version&locale=$locale&mysql=$mysql_version&local_package=$local_package&blogs=$num_blogs&users={$user_count['total_users']}&multisite_enabled=$multisite_enabled";

    $options = array(
        'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
        'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
        'headers' => array(
            'wp_install' => $wp_install,
            'wp_blog' => home_url( '/' )
        )
    );

    $response = wp_remote_get($url, $options);

    if ( is_wp_error( $response ) )
        return false;

    if ( 200 != $response['response']['code'] )
        return false;

    $body = trim( $response['body'] );
    $body = str_replace(array("\r\n", "\r"), "\n", $body);
    $new_options = array();
    foreach ( explode( "\n\n", $body ) as $entry ) {
        $returns = explode("\n", $entry);
        $new_option = new stdClass();
        $new_option->response = esc_attr( $returns[0] );
        if ( isset( $returns[1] ) )
            $new_option->url = esc_url( $returns[1] );
        if ( isset( $returns[2] ) )
            $new_option->package = esc_url( $returns[2] );
        if ( isset( $returns[3] ) )
            $new_option->current = esc_attr( $returns[3] );
        if ( isset( $returns[4] ) )
            $new_option->locale = esc_attr( $returns[4] );
        if ( isset( $returns[5] ) )
            $new_option->php_version = esc_attr( $returns[5] );
        if ( isset( $returns[6] ) )
            $new_option->mysql_version = esc_attr( $returns[6] );
        $new_options[] = $new_option;
    }

    $updates = new stdClass();
    $updates->updates = $new_options;
    $updates->last_checked = time();
    $updates->version_checked = $wp_version;

    return $updates;
}

/**
 * Function to "namespace" our basic "are we installed properly" checks
 * to avoid polluting global namespace
 */
function wp_db_abstraction_check_plugin_install() {
    /**
     * Check to make sure that this file is in the mu-plugins folder
     */
    $wp_db_ab_plugin_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wp-db-abstraction' . DIRECTORY_SEPARATOR;
    if (basename(dirname(__FILE__)) !== 'mu-plugins') {
        $error_message = 'WP Db Abstraction can only be installed to the mu-plugins directory in wp-content/';
        include $wp_db_ab_plugin_path . 'error_page.php';
        die;
    }
    
    /**
     * Check to make sure that db.php is up from here in the wp-content folder
     */
    $wp_db_ab_db_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'db.php';
    if (!file_exists($wp_db_ab_db_file)) {
        if (!copy($wp_db_ab_plugin_path . 'db.php', $wp_db_ab_db_file)) {
            $error_message = 'WP Db Abstraction requires db.php to be in the wp-content/ directory.';
            include $wp_db_ab_plugin_path . 'error_page.php';
            die;
        }
    }

    add_filter ('pre_site_transient_update_core', 'wp_db_abstraction_check_core_upgrade', 1, 1);
}

wp_db_abstraction_check_plugin_install();