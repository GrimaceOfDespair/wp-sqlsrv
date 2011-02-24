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
require_once 'Admin_Users.php';
require_once 'Admin_Pages.php';
require_once 'Admin_Posts.php';
require_once 'Admin_Tags.php';
require_once 'Admin_Categories.php';
require_once 'Admin_Links.php';
require_once 'Admin_Comments.php';
require_once 'Admin_Plugins.php';
require_once 'Admin_Settings.php';
require_once 'Admin_General.php';
require_once 'Admin_Multisite.php';

/**
 * All Admin Tests
 */
class AdminTestSuite extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Admin System Tests');
        $suite->addTestSuite('System_Admin_Users');
        $suite->addTestSuite('System_Admin_Posts');
        $suite->addTestSuite('System_Admin_Pages');
        $suite->addTestSuite('System_Admin_Tags');
        $suite->addTestSuite('System_Admin_Categories');
        $suite->addTestSuite('System_Admin_Links');
        $suite->addTestSuite('System_Admin_Comments');
        $suite->addTestSuite('System_Admin_Plugins');
        $suite->addTestSuite('System_Admin_Settings');
        $suite->addTestSuite('System_Admin_General');
        $suite->addTestSuite('System_Admin_Multisite');
        return $suite;
    }

}