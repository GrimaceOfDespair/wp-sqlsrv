<?php
/**
 * Wordpress for SQL Server/Azure Upgrade Check
 * Mimicks http://api.wordpress.org/core/version-check/1.3/?version=$wp_version&php=$php_version&locale=$locale&mysql=$mysql_version&local_package=$local_package
 * currently hosted at http://wordpress.visitmix.com/api/wp/
 * @author Anthony Gentile
 *
 */
$current_version = '3.1.3';
$current_locale = 'en_US';

if (isset($_GET['version']) && trim($_GET['version']) != '') {
    $version = trim($_GET['version']);
} else {
    $version = $current_version;
}

if (isset($_GET['locale']) && trim($_GET['locale']) != '') {
    $locale = trim($_GET['locale']);
} else {
    $locale = $current_locale;
}

if ($current_version == $version) {
    $status = 'latest';
} elseif ($current_version > $version) {
    $status = 'upgrade';
} else {
    $status = 'development';
}
header('Content-type: text/plain');
echo $status . "\r\n";
echo 'http://wordpress.visitmix.com/download/' . "\r\n";
echo 'http://downloads.sourceforge.net/project/wp-sqlsrv/distribution/wp-db-abstraction-1.1.0.zip?use_mirror=iweb' . "\r\n";
echo $current_version. "\r\n";
echo $current_locale;