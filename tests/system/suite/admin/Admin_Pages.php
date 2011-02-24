<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Pages extends System_BaseTestCase
{
    public function testAddPage()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/post-new.php?post_type=page');
        $this->waitForTitle('Add New Page');

        // Fill out the form!
        $this->type('xpath=//*[@id="title"]', 'A Test Page');
        $this->type('xpath=//*[@id="content"]', 'Test Content');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Edit Page');

        // Verify post published
        $this->assertTextPresent('exact:Page published.');
    }

    public function testViewPages()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php?post_type=page');
        $this->waitForTitle('Pages');
    }

    public function testEditPage()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php?post_type=page');
        $this->waitForTitle('Pages');

        // Click on page on top of the list 
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[1]/a');
        $this->waitForTitle('Edit Page'); // 5 seconds

        // Edit title and content
        $this->type('xpath=//*[@id="title"]', 'A Test Title2');
        $this->type('xpath=//*[@id="content"]', 'Test Content2');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Edit Page');

        // Verify the post updated
        $this->assertTextPresent('exact:Page updated.');
    }

    public function testTrashPage()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php?post_type=page');
        $this->waitForTitle('Pages');

        // Click on post on top of the list 
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[3]/a');
        $this->waitForTitle('Pages');

        // Verify the post moved to trash
        $this->assertTextPresent('exact:Item moved to the trash.');
    }

    public function testDeletePage()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php?post_status=trash&post_type=page');
        $this->waitForTitle('Pages');

        $this->assertTextNotPresent('exact:No pages found in Trash');

        // Click on post on top of the list 
        $this->clickAndWait('xpath=//*[@id="delete_all"]');
        $this->waitForTitle('Pages'); // 5 seconds

        // Verify the post moved to trash
        $this->assertTextPresent('exact:permanently deleted.');
    }
}