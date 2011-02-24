<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Plugins extends System_BaseTestCase
{

    public function testViewPlugins()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/plugins.php');
        $this->waitForTitle('Plugins');
    }

    public function tesActivatePlugin()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/plugins.php');
        $this->waitForTitle('Plugins');

        // click hello dolly plugin activate
        $this->clickAndWait('xpath=//*[@id="all-plugins-table"]/tbody/tr[4]/td[2]/div/span[1]/a');
        $this->waitForTitle('Plugins'); // 5 seconds

        // verify plugin activated
        $this->assertTextPresent('exact:Plugin activated.');
    }

    public function testDeactivatePlugin()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/plugins.php');
        $this->waitForTitle('Plugins');

        // click hello dolly plugin deactivate
        $this->clickAndWait('xpath=//*[@id="all-plugins-table"]/tbody/tr[4]/td[2]/div/span[1]/a');
        $this->waitForTitle('Plugins'); // 5 seconds

        // verify plugin activated
        $this->assertTextPresent('exact:Plugin deactivated.');
    }
}