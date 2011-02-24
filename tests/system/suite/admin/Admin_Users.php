<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Users extends System_BaseTestCase
{
    static $user;

    public function testLogin()
    {
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $this->config->wordpressAdminUser);

        // no password
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->assertTextPresent('exact:ERROR: The password field is empty');

        $this->type('xpath=//*[@id="user_pass"]', 'asdf');
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->assertTextPresent('exact:ERROR: Incorrect password. Lost your password?');

        $this->type('xpath=//*[@id="user_pass"]', $this->config->wordpressAdminPass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Dashboard');

        $this->assertTextPresent('exact:Howdy, ' . $this->config->wordpressAdminUser);

    }

    public function testAddUser()
    {
        if ($this->config->isMultiuser == true) {
            $this->markTestSkipped(
                  'This is a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/user-new.php', true);
        $this->waitForTitle('Add New User');

        $user = time();
        $email = $user . '@example.com';
        $password = md5(1);

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="email"]', $email);
        $this->type('xpath=//*[@id="pass1"]', $password);
        $this->type('xpath=//*[@id="pass2"]', $password);
        $this->click('xpath=//*[@id="send_password"]'); // uncheck the email password
        $this->clickAndWait('xpath=//*[@id="addusersub"]');

        $this->waitForTitle('Users');

        $this->assertTextPresent('exact:New user created.');
        $this->assertTextPresent('exact:Search results for “' . $user . '”');
        // get and store our user id
        $id = $this->getAttribute('xpath=//*/tbody/tr[1]@id');
        self::$user = str_replace('user-', '', $id);
    }

    /**
     * @depends testAddUser
     */
    public function testDeleteUser()
    {
        if ($this->config->isMultiuser == true) {
            $this->markTestSkipped(
                  'This is a multiuser install.'
                );
        }

        if (empty(self::$user)) {
            $this->markTestSkipped(
                  'User could not be found to delete'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php', true);

        $this->waitForTitle('Users');

        $this->clickAndWait('xpath=//*[@id=\'user-' . self::$user . '\']/td[1]/div/span[2]/a');

        $this->assertTextPresent('exact:Delete Users');
        $this->clickAndWait('//*[@id=\'updateusers\']/div/p[2]/input');
        $this->waitForTitle('Users');

        $this->assertTextPresent('exact:1 user deleted');
    }

    public function testViewUsers()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php');
        $this->waitForTitle('Users');
    }

    public function testViewAdministrators()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php?role=administrator');
        $this->waitForTitle('Users');
    }

    public function testEditProfile()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/profile.php');
        $this->waitForTitle('Profile');

        // click blue admin color scheme
        $this->click('xpath=//*[@id="admin_color_classic"]');

        // click keyboard shortcuts
        $this->click('xpath=//*[@id="comment_shortcuts"]');

        $this->type('xpath=//*[@id="first_name"]', 'Test first name');
        $this->type('xpath=//*[@id="last_name"]', 'Test last name');
        $this->type('xpath=//*[@id="nickname"]', 'Test nickname');
        $this->type('xpath=//*[@id="description"]', 'Test profile description');

        // click submit
        $this->clickAndWait('xpath=//*[@id="your-profile"]/p[2]/input[3]');
        $this->waitForTitle('Profile'); // 5 seconds

        // verify profile saved
        $this->assertTextPresent('exact:User updated.');
        $this->assertTextPresent('exact:Test first name');
        $this->assertTextPresent('exact:Test last name');
        $this->assertTextPresent('exact:Test nickname');
        $this->assertTextPresent('exact:Test profile description');
    }

    public function testAddUserMultisite()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/ms-users.php', true);
        $this->waitForTitle('Users');

        $this->type('xpath=/html/body/div/div/div[2]/div/div[4]/form/table/tbody/tr/td/input', 'test2');
        $this->type('xpath=/html/body/div/div/div[2]/div/div[4]/form/table/tbody/tr[2]/td/input', 'test2@example.com');
        $this->click('xpath=/html/body/div/div/div[2]/div/div[4]/form/p/input[3]');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->assertTextPresent('exact:User added.');
    }

    /**
     * @depends testAddUserMultisite
     */
    public function testDeleteUserMultisite()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/ms-users.php');
        $this->waitForTitle('Users');

        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form[2]/table/tbody/tr[2]/td/div/span[2]/a');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form/p/input');
        $this->waitForPageToLoad(5000); // 5 seconds
        $this->assertTextPresent('exact:User deleted.');
    }

    /**
     * @depends testAddUserMultisite
     */
    public function testEditUserMultisite()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/ms-users.php');
        $this->waitForTitle('Users');

        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form[2]/table/tbody/tr[2]/td/div/span/a');
        $this->waitForPageToLoad(5000); // 5 seconds

        // click blue admin color scheme
        $this->click('xpath=//*[@id="admin_color_classic"]');

        // click keyboard shortcuts
        $this->click('xpath=//*[@id="comment_shortcuts"]');

        $this->type("dom=document.forms['your-profile'].first_name", 'Test2 first name');
        $this->type("dom=document.forms['your-profile'].last_name", 'Test2 last name');
        $this->type("dom=document.forms['your-profile'].nickname", 'Test2 nickname');
        $this->type("dom=document.forms['your-profile'].description", 'Test2 profile description');

        // click submit
        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form/p[2]/input[3]');
        $this->waitForPageToLoad(5000); // 5 seconds

        // verify profile saved
        $this->assertTextPresent('exact:User updated.');
        $this->assertTextPresent('exact:Test2 first name');
        $this->assertTextPresent('exact:Test2 last name');
        $this->assertTextPresent('exact:Test2 nickname');
        $this->assertTextPresent('exact:Test2 profile description');
    }

    public function testSearchUsersMultisite()
    {
        if ($this->config->isMultiuser == false) {
            $this->markTestSkipped(
                  'This is not a multiuser install.'
                );
        }

        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/ms-admin.php');
        $this->waitForTitle('Network Admin');

        $this->type('xpath=/html/body/div/div/div[2]/div/div[3]/form/p/input[2]', 'test');
        $this->click('xpath=/html/body/div/div/div[2]/div/div[3]/form/p/input[3]');
        $this->waitForPageToLoad(5000); // 5 seconds

        $this->assertTextPresent('exact:Search results for “test”');
    }
}
