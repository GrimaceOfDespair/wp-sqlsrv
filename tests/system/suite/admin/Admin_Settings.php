<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Settings extends System_BaseTestCase
{
    public function testEditGeneral()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-general.php');
        $this->waitForTitle('General Settings');

        // Fill out the form!
        $this->type('xpath=//*["blogname"]', 'Test (edited)');
        $this->type('xpath=//*["blogdescription"]', 'Test blog description');
        $this->type('xpath=//*["new_admin_email"]', 'edit@example.com');

        // select user can register button
        $this->click('xpath=//*[@id="users_can_register"]');

        // select m/d/Y date format
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/table/tbody/tr[9]/td/fieldset/label[3]/input');

        // select g:i A time format
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/table/tbody/tr[10]/td/fieldset/label[3]/input');

        // click save button
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p/input');
        $this->waitForTitle('General Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditWriting()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-writing.php');
        $this->waitForTitle('Writing Settings');

        // Fill out the form!
        $this->type('xpath=//*[@id="default_post_edit_rows"]', '12');

        // select correct XHTML nesting
        $this->click('xpath=//*[@id="use_balanceTags"]');

        // click save button
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p[8]/input');
        $this->waitForTitle('Writing Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditReading()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-reading.php');
        $this->waitForTitle('Reading Settings');

        // Fill out the form!
        $this->type('xpath=//*[@id="posts_per_page"]', rand(10, 20));
        $this->type('xpath=//*[@id="posts_per_rss"]', rand(10, 20));

        // select summary for feeds
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/table/tbody/tr[4]/td/fieldset/p/label[2]/input');

        // click save button
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/table/tbody/tr[4]/td/fieldset/p/label[2]/input');
        $this->waitForTitle('Reading Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditDiscussion()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-discussion.php');
        $this->waitForTitle('Discussion Settings');

        // uncheck default ping status
        $this->click('xpath=//*[@id="default_ping_status"]');

        // check close comments for old posts and set to 12 days
        $this->click('xpath=//*[@id="close_comments_for_old_posts"]');
        $this->type('xpath=//*[@id="close_comments_days_old"]', '12');

        // uncheck notify via email of comments
        $this->click('xpath=//*[@id="comments_notify"]');

        // check identicon avatar
        $this->click('xpath=//*[@id="avatar_identicon"]');

        // click save button
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p[2]/input');
        $this->waitForTitle('Discussion Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditMedia()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-media.php');
        $this->waitForTitle('Media Settings');

        // change medium size dimensions
        $this->type('xpath=//*[@id="medium_size_w"]', rand(50, 350));
        $this->type('xpath=//*[@id="medium_size_h"]', rand(50, 350));

        // uncheck automatically embed
        $this->click('xpath=//*[@id="embed_autourls"]');

        // click save button
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/p[2]/input');
        $this->waitForTitle('Media Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditPrivacy()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-privacy.php');
        $this->waitForTitle('Privacy Settings');

        // check block search engines
        $this->click('xpath=//*[@id="blog-norobots"]');

        // click save button
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p/input');
        $this->waitForTitle('Privacy Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Settings saved.');
    }

    public function testEditPermalink()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-permalink.php');
        $this->waitForTitle('Permalink Settings');

        // select month and name permalinks
        $this->click('xpath=//*[@id="wpbody-content"]/div[2]/form/table[1]/tbody/tr[3]/th/label/input');

        // click save button
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p[3]/input');
        $this->waitForTitle('Permalink Settings');

        // verify settings edited
        $this->assertTextPresent('exact:Permalink structure updated.');
    }
}