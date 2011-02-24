<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Tags extends System_BaseTestCase
{
    static protected $time;

    public function testAddTag()
    {
        self::$time = $time = time();

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=post_tag');
        $this->waitForTitle('Post Tags');

        // Fill out the form!
        $this->type('xpath=//*[@id="tag-name"]', 'test tag' . $time);
        $this->type('xpath=//*[@id="tag-slug"]', 'testtag' . $time);
        $this->type('xpath=//*[@id="tag-description"]', 'test tag description' . $time);

        // Submit the form!
        $this->click('xpath=//*[@id="submit"]');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextPresent('exact:test tag' . $time);
        $this->assertTextPresent('exact:testtag' . $time);
        $this->assertTextPresent('exact:test tag description' . $time);
    }

    /**
     * @depends testAdminUIPostsAddTag
     */
    public function testEditTag()
    {
        $time = self::$time;

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=post_tag');
        $this->waitForTitle('Post Tags');

        // click edit
        $this->click('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[1]/a');
        $this->waitForPageToLoad(5000); // 5 seconds

        // Fill out the form!
        $this->type('xpath=//*[@id="name"]', 'test tag2' . $time);
        $this->type('xpath=//*[@id="slug"]', 'testtag2' . $time);
        $this->type('xpath=//*[@id="description"]', 'test tag description2' . $time);

        // Submit the form!
        $this->click('xpath=//*[@id="edittag"]/p/input');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextPresent('exact:test tag2' . $time);
        $this->assertTextPresent('exact:testtag2' . $time);
        $this->assertTextPresent('exact:test tag description2' . $time);
    }

    /**
     * @depends testAdminUIPostsAddTag
     */
    public function testDeleteTag()
    {
        $time = self::$time;

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=post_tag');
        $this->waitForTitle('Post Tags');

        $this->chooseOkOnNextConfirmation();

        // click delete - this should really have a safer lookup
        $this->click('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[3]/a');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextNotPresent('exact:test tag' . $time);
        $this->assertTextNotPresent('exact:testtag' . $time);
        $this->assertTextNotPresent('exact:test tag description' . $time);

        $this->assertTextNotPresent('exact:test tag2' . $time);
        $this->assertTextNotPresent('exact:testtag2' . $time);
        $this->assertTextNotPresent('exact:test tag description2' . $time);
    }
}