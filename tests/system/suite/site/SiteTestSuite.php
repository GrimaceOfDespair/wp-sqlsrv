<?php
/**
 * AdminTestSuite.php - All the admin tests
 */

/**
 * Load PHPUnit libraries
 */
require_once 'PHPUnit/Framework.php';

/**
 * Load Tests
 */
require_once 'Site_Comments.php';
require_once 'Site_Content.php';

/**
 * All Site Tests
 */
class SiteTestSuite extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Site System Tests');
        $suite->addTestSuite('System_Site_Comments');
        $suite->addTestSuite('System_Site_Content');
        return $suite;
    }

}