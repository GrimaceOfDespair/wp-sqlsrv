<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Comments extends System_BaseTestCase
{
    protected static $text;

    public function testViewComments()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php');
        $this->waitForTitle('Comments');
    }

    public function testAddCommentAdmin()
    {
        // Login
        $this->adminLogin();

        // get a post
        $id = $this->createPost();

        // this format ALWAYS works
        $this->openAndWait($this->wordpressUrl . '?p=' . $id);
        $this->waitForTitle('Test Content');
        self::$text = $text = 'This is a test comment. written on ' . time();

        // Fill out the comments form!
        $this->type('xpath=//*[@id="comment"]', $text);

        // submit form
        $this->click('xpath=//*[@id="submit"]');


        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . $text);
    }

    public function testUnapproveComment()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=approved');
        $this->waitForTitle('Comments');

        // click unapprove
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[1]/a');

        // click pending section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[2]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testApproveComment()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=moderated');
        $this->waitForTitle('Comments');

        // click approve
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[1]/a');

        // go to approved comments section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[3]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testMarkCommentAsSpam()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=approved');
        $this->waitForTitle('Comments');

        // click spam
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[5]/a');

        // go to spam comments section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[4]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testMarkCommentAsNotSpam()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=spam');
        $this->waitForTitle('Comments');

        // click not spam
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[3]/a');

        // go to approved comments section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[3]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testTrashComment()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=approved');
        $this->waitForTitle('Comments');

        // click trash
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[6]/a');

        // go to trash comments section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[5]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testRestoreComment()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=trash');
        $this->waitForTitle('Comments');

        // click restore
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[1]/a');

        // go to trash comments section
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[3]/a');
        $this->waitForTitle('Comments');

        $this->assertTextPresent('exact:' . self::$text);
    }

    public function testDeleteComment()
    {
        // Login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-comments.php?comment_status=approved');
        $this->waitForTitle('Comments');

        // click trash
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[6]/a');
        $this->clickAndWait('xpath=//*[@id="comments-form"]/ul/li[5]/a');
        $this->waitForTitle('Comments');

        // click delete
        $this->click('xpath=//*/tbody/tr[1]/td[2]/div[3]/span[2]/a');
        // sleep - this is an ajax call and waitForText is broken
        sleep(3);

        $this->assertTextNotPresent('exact:' . self::$text);
    }
}