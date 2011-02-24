<?php
/**
 * TestSuite.php - acceptence tests for wordpress
 */

/**
 * Load PHPUnit libraries
 */
require_once 'PHPUnit/Framework.php';

/**
 * Load Tests
 */
require_once 'admin/AdminTestSuite.php';
require_once 'site/SiteTestSuite.php';

/**
 * All Selenium Tests
 */
class SystemTestSuite extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('System Tests for Wordpress');
        $suite->addTestSuite('AdminTestSuite');
        $suite->addTestSuite('SiteTestSuite');
        return $suite;
    }

}
