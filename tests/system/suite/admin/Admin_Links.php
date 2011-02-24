<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Links extends System_BaseTestCase
{
    static protected $link;

    public function testAddLink()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/link-add.php');
        $this->waitForTitle('Add New Link');

        $time = time();
        $link = 'Aaaa Test Link ' . $time;
        $desc = 'Aa Example Link Description' . $time;

        // Fill out the form!
        $this->type('xpath=//*[@id="link_name"]', $link);
        $this->type('xpath=//*[@id="link_url"]', 'http://www.example.com');
        $this->type('xpath=//*[@id="link_description"]', $desc);

        // select target _blank option
        $this->click('xpath=//*[@id="link_target_blank"]');

        // add link button
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Add New Link');

        // verify link added
        $this->assertTextPresent('exact:Link added.');
        self::$link = array($link, $desc, $time);
    }

    public function testAdminViewLinks()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/link-manager.php');
        $this->waitForTitle('Links');
    }

    public function testAdminEditLink()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/link-manager.php');
        $this->waitForTitle('Links');

        // click edit button
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div/span[1]/a');
        $this->waitForTitle('Edit Link');
        list($link, $desc, $time) = self::$link;

        // Fill out the form!
        $this->type('xpath=//*[@id="link_name"]', $link . ' (edited)');
        $this->type('xpath=//*[@id="link_url"]', 'http://www.exampleedit.com');
        $this->type('xpath=//*[@id="link_description"]', $desc . ' (edited)');

        // select target _top option
        $this->click('xpath=//*[@id="link_target_top"]');

        // update button
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Links');

        // verify link edited
        $this->assertTextPresent('exact:' . $link . ' (edited)');
    }

    public function testAdminDeleteLink()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/link-manager.php');
        $this->waitForTitle('Links');

        $this->chooseOkOnNextConfirmation();

        // click delete button
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div/span[2]/a');
        $this->waitForTitle('Links');
        sleep(3);
        list($link, $desc, $time) = self::$link;

        // verify link deleted
        $this->assertTextNotPresent('exact:' . $link . ' (edited)');
    }


    public function testAddLinkCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-link-categories.php');
        $this->waitForTitle('Link Categories');
        list($link, $desc, $time) = self::$link;

        // Fill out the form!
        $this->type('xpath=//*[@id="link-name"]', $link . ' Category');
        $this->type('xpath=//*[@id="link-slug"]', $time);
        $this->type('xpath=//*[@id="link-description"]', $desc . ' Category');

        // click add category
        $this->click('xpath=//*[@id="addcat"]/p/input');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextPresent('exact:' . $link . ' Category');
        $this->assertTextPresent('exact:' . $time);
        $this->assertTextPresent('exact:' . $desc . ' Category');
    }

    public function testViewLinkCategories()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-link-categories.php');
        $this->waitForTitle('Link Categories');
    }


    public function testEditLinkCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-link-categories.php');
        $this->waitForTitle('Link Categories');

        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[1]/a');
        $this->waitForTitle('Edit Category');
        sleep(3);
        list($link, $desc, $time) = self::$link;

        // Fill out the form!
        $this->type('xpath=//*[@id="name"]', $link . ' Category (edited)');
        $this->type('xpath=//*[@id="slug"]', $time . '-edited');
        $this->type('xpath=//*[@id="description"]', $desc . ' Category (edited)');

        // click add category
        $this->clickAndWait('xpath=//*[@id="editcat"]/p/input');
        
        $this->waitForTitle('Link Categories');

        $this->assertTextPresent('exact:Category updated.');
        $this->assertTextPresent('exact:' . $link . ' Category (edited)');
        $this->assertTextPresent('exact:' . $time. '-edited');
        $this->assertTextPresent('exact:' . $desc . ' Category (edited)');
    }

    public function testDeleteLinkCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-link-categories.php');
        $this->waitForTitle('Link Categories');

        $this->chooseOkOnNextConfirmation();
        $this->click('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[1]/a');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(8);
        list($link, $desc, $time) = self::$link;

        $this->assertTextNotPresent('exact:' . $link . ' Category (edited)');
    }
}