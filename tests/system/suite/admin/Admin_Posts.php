<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Posts extends System_BaseTestCase
{
    public function testAddPost()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/post-new.php');
        $this->waitForTitle('Add New Post');

        // Fill out the form!
        $this->type('xpath=//*[@id="title"]', 'Test Title');
        $this->type('xpath=//*[@id="content"]', 'Test Content');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Edit Post');

        // Verify post published
        $this->assertTextPresent('exact:Post published.');
    }

    public function testViewPosts()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php');
        $this->waitForTitle('Posts');
    }

    public function testEditPost()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php');
        $this->waitForTitle('Posts');

        // Click on post on top of the list 
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div/span[1]/a');
        $this->waitForTitle('Edit Post'); // 5 seconds

        // Edit title and content
        $this->type('xpath=//*[@id="title"]', 'Test Title 2');
        $this->type('xpath=//*[@id="content"]', 'Test Content 2');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');
        $this->waitForTitle('Edit Post'); // 5 seconds

        // Verify the post updated
        $this->assertTextPresent('exact:Post updated.');
    }

    public function testTrashPost()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php');
        $this->waitForTitle('Posts');

        // Click on post on top of the list 
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div/span[3]/a');
        $this->waitForTitle('Posts');

        // Verify the post moved to trash
        $this->assertTextPresent('exact:Item moved to the trash.');
    }

    public function testDeletePost()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit.php?post_status=trash&post_type=post');
        $this->waitForTitle('Posts');

        $this->assertTextNotPresent('exact:No posts found in Trash');

        // Click on post on top of the list 
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div/span[2]/a');
        $this->waitForTitle('Posts');

        // Verify the post moved to trash
        $this->assertTextPresent('exact:Item permanently deleted.');
    }
}
