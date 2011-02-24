<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Multisite extends System_BaseTestCase
{
    public function testAdminUISuperAdminDisableTheme()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->open($this->wordpressUrl . 'wp-admin/ms-themes.php');
        sleep(2);
        $this->assertTitle('Network Themes');

        $this->click('xpath=//*[@id="disabled_twentyten"]');
        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form/p[3]/input');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->assertTextPresent('exact:Site themes saved.');
    }

    /**
     * @depends testAdminUISuperAdminDisableTheme
     */
    public function testAdminUISuperAdminEnableTheme()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->open($this->wordpressUrl . 'wp-admin/ms-themes.php');
        sleep(2);
        $this->assertTitle('Network Themes');

        $this->click('xpath=//*[@id="enabled_twentyten"]');
        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form/p[3]/input');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->assertTextPresent('exact:Site themes saved.');
    }

    public function testAdminUISuperAdminSearchSites()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->open($this->wordpressUrl . 'wp-admin/ms-admin.php');
        sleep(2);
        $this->assertTitle('Network Admin');

        $this->type('xpath=/html/body/div/div/div[2]/div/div[3]/form[2]/p/input[3]', 'test');
        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form[2]/p/input[4]');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->assertTextPresent('exact:Search results for “test”');
    }
}