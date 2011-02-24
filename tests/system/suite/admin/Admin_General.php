<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_General extends System_BaseTestCase
{
    public function testViewDashboard()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/index.php');
        $this->waitForTitle('Dashboard');
    }

    public function testViewMedia()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/index.php');
        $this->waitForTitle('Dashboard');

        $this->click('xpath=//*[@id="menu-media"]/a');
        $this->waitForTitle('Media Library');
    }

    public function testAppearanceViewThemes()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/themes.php');
        $this->waitForTitle('Manage Themes');
    }

    public function testAppearanceEditHeader()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/themes.php?page=custom-header');
        $this->waitForTitle('Header');

        // click different header image
        $this->click('xpath=//*[@id="available-headers"]/div[3]/label/input');
        // submit
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p/input');

        $this->assertTextPresent('exact:Header updated.');
    }

    public function testAppearanceEditBackground()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/themes.php?page=custom-background');
        $this->waitForTitle('Background');

        // click different header image
        $this->type("xpath=//*[@id='background-color']", '#222222');
        
        // submit
        $this->clickAndWait('xpath=//*[@id="custom-background"]/form/p/input');

        $this->assertTextPresent('exact:Background updated.');
    }
}