<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Site_Comments extends System_BaseTestCase
{
    protected $user = array();
    protected $block_register;

    public function testRegister() {
        // make sure that "users can register" is checked
        $this->adminLogin();
        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/options-general.php');
        $this->assertTitle('General Settings');
        // select user can register button
        $this->check('xpath=//*[@id="users_can_register"]');
        $this->clickAndWait('xpath=//*[@id="wpbody-content"]/div[2]/form/p/input');
        $this->waitForTitle('General Settings');
        $this->assertTextPresent('exact:Settings saved.');
        // logout
        $this->adminLogout();

        // register the user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php?action=register');
        $this->waitForTitle('Registration Form');
        $this->assertTextPresent('exact:Register For This Site');

        $user = time();
        $email = $user . '@example.com';

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_email"]', $email);
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Log In');
        $this->assertTextPresent('exact:Registration complete. Please check your e-mail.');

        // make sure they exist in the admin
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php?usersearch=' . $user);
        $this->waitForTitle('Users');
        $this->assertTextPresent('Back to All Users');
        $this->assertTextPresent('exact:Search results for');
        $this->assertTextPresent('exact:' . $user);

        // return our user id for dependent tests
        $id = str_replace('user-', '', $this->getAttribute('xpath=//*/tbody/tr[1]@id'));

        self::$depends['user'] = array($user, $id);
    }

    /**
     * @depends testRegister
     *
     * This would be FAR cleaner with a depends system
     * but that is horrible broken with selenium in PHPunit... and the bug has been open for 8 months
     * http://www.phpunit.de/ticket/916
     * hence the horrible use of static class variables
     */
    public function testLogIn() {
        $this->assertTrue(self::$depends['user'] > 0);
        $id = self::$depends['user'][1];
        $user = self::$depends['user'][0];

        // log into admin and sethe user password to something we know
        $this->adminLogin();
        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php?usersearch=' . $user);
        $this->waitForTitle('Users');
        $this->click('xpath=//*[@id="user-' . $id . '"]/td[1]/div/span[1]/a');
        
        $this->waitForTitle('Edit User');
        $pass = md5(1);
        $this->type('xpath=//*[@id="pass1"]', $pass);
        $this->type('xpath=//*[@id="pass2"]', $pass);
        $this->clickAndWait('//*[@id="your-profile"]/p[2]/input[3]');
        sleep(2);
       // logout
        $this->adminLogout();

        // attempt to log in with user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_pass"]', $pass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Profile');

        $this->assertTextPresent('exact:Howdy, ' . $user);
        self::$depends['user'][2] = $pass;
    }

    /**
     * @depends testRegister
     * @depends testLogIn
     */
    public function testLogOut() {
        $this->assertTrue(self::$depends['user'] > 0);
        $id = self::$depends['user'][1];
        $user = self::$depends['user'][0];
        $pass = self::$depends['user'][2];

        // attempt to log in with user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_pass"]', $pass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Profile');

        $this->assertTextPresent('exact:Howdy, ' . $user);

        // Click on the "yes log me out"
        $this->clickAndWait('xpath=//*[@id="user_info"]/p/a[2]');
        $this->waitForTitle('Log In');
        $this->assertTextPresent('exact:You are now logged out.');
    }

    /**
     * @depends testRegister
     */
    public function testLostPassword() {
        $this->assertTrue(self::$depends['user'] > 0);
        $user = self::$depends['user'][0];

        // wahoo
        $this->openAndWait($this->wordpressUrl . 'wp-login.php?action=lostpassword');
        $this->assertTitle('Lost Password');
        $this->assertTextPresent('exact:Please enter your username or e-mail address. You will receive a new password via e-mail.');

        // click it and get an error
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->assertTextPresent('exact:ERROR: Enter a username or e-mail address.');

        // enter a bad username and get an error
        $this->type('xpath=//*[@id="user_login"]', uniqid());
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->assertTextPresent('exact:ERROR: Invalid username or e-mail.');

        // enter good username
        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Log In');
        $this->assertTextPresent('exact:Check your e-mail for the confirmation link.');
        
    }

    /**
     * @depends testRegister
     * @depends testLogIn
     */
    public function testEditProfile() {
        $this->assertTrue(self::$depends['user'] > 0);
        $id = self::$depends['user'][1];
        $user = self::$depends['user'][0];
        $pass = self::$depends['user'][2];

        // attempt to log in with user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_pass"]', $pass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Profile');

        $this->assertTextPresent('exact:Howdy, ' . $user);

        // click blue admin color scheme
        $this->click('xpath=//*[@id="admin_color_classic"]');

        $this->type('xpath=//*[@id="first_name"]', 'Test2 first name');
        $this->type('xpath=//*[@id="last_name"]', 'Test2 last name');
        $this->type('xpath=//*[@id="nickname"]', 'Test2 nickname');
        $this->type('xpath=//*[@id="description"]', 'Test2 profile description');

        // click submit
        $this->clickAndWait('xpath=//*[@id="your-profile"]/p[2]/input[3]');
        sleep(3); // click and wait always fails

        // verify profile saved
        $this->assertTextPresent('exact:User updated.');
        $this->assertTextPresent('exact:Test2 first name');
        $this->assertTextPresent('exact:Test2 last name');
        $this->assertTextPresent('exact:Test2 nickname');
        $this->assertTextPresent('exact:Test2 profile description');
    }

    // TODO: check/change comment settings
    public function testAddCommentPostLoggedIn()
    {
        sleep(5); // comment throttling
        $this->assertTrue(self::$depends['user'] > 0);
        $id = self::$depends['user'][1];
        $user = self::$depends['user'][0];
        $pass = self::$depends['user'][2];

        $this->adminLogin();
        $post_id = $this->createPost();
        $this->adminLogout();

        // attempt to log in with user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_pass"]', $pass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Profile');

        $this->assertTextPresent('exact:Howdy, ' . $user);

        // this format ALWAYS works
        $this->openAndWait($this->wordpressUrl . '?p=' . $post_id);
        $this->waitForTitle('Test Content');

        // Fill out the comments form!
        $this->type('xpath=//*[@id="comment"]', 'This is a test comment.');

        // submit form
        $this->clickAndWait('xpath=//*[@id="submit"]');

        $this->waitForTitle('Test Content');

        $this->assertTextPresent('exact:' . $user . ' says:');
        $this->assertTextPresent('exact:This is a test comment.');
    }

    // TODO: check/change comment settings
    public function testAddCommentPageLoggedIn()
    {
        sleep(5); // comment throttling
        $this->assertTrue(self::$depends['user'] > 0);
        $id = self::$depends['user'][1];
        $user = self::$depends['user'][0];
        $pass = self::$depends['user'][2];

        $this->adminLogin();
        $post_id = $this->createPage();
        $this->adminLogout();

        // attempt to log in with user
        $this->openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        $this->type('xpath=//*[@id="user_login"]', $user);
        $this->type('xpath=//*[@id="user_pass"]', $pass);

        // Submit the form!
        $this->click('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('Profile');

        $this->assertTextPresent('exact:Howdy, ' . $user);

        // this format ALWAYS works
        $this->openAndWait($this->wordpressUrl . '?page_id=' . $post_id);
        $this->waitForTitle('Test Content');

        // Fill out the comments form!
        $this->type('xpath=//*[@id="comment"]', 'This is a test comment.');

        // submit form
        $this->clickAndWait('xpath=//*[@id="submit"]');

        $this->waitForTitle('Test Content');

        $this->assertTextPresent('exact:' . $user . ' says:');
        $this->assertTextPresent('exact:This is a test comment.');
    }

    public function testAddCommentPostAnonymous()
    {
        sleep(5); // comment throttling
        // Login
        $this->adminLogin();

        // get a post
        $id = $this->createPost();

        // logout
        $this->adminLogout();

        // this format ALWAYS works
        $this->openAndWait($this->wordpressUrl . '?p=' . $id);
        $this->waitForTitle('Test Content');

        $author = 'Test Author' . time();
        // Fill out the comments form!
        $this->type('xpath=//*[@id="author"]', $author);
        $this->type('xpath=//*[@id="email"]', 'test@example.com');
        $this->type('xpath=//*[@id="url"]', 'http://www.example.com');
        $this->type('xpath=//*[@id="comment"]', 'This is a test comment.');

        // submit form
        $this->click('xpath=//*[@id="submit"]');

        //login
        $this->adminLogin();

        $this->openAndWait($this->wordpressUrl . 'wp-admin/edit-comments.php');
        $this->assertTitle('Comments');

        $this->assertTextPresent('exact:' . $author);
        $this->assertTextPresent('exact:test@example.com');
        $this->assertTextPresent('exact:This is a test comment.');
    }

    public function testAddCommentPageAnonymous()
    {
        sleep(5); // comment throttling
        // Login
        $this->adminLogin();

        // get a post
        $id = $this->createPage();

        // logout
        $this->adminLogout();

        // this format ALWAYS works
        $this->openAndWait($this->wordpressUrl . '?page_id=' . $id);
        $this->waitForTitle('Test Content');

        $author = 'Test Author' . time();
        // Fill out the comments form!
        $this->type('xpath=//*[@id="author"]', $author);
        $this->type('xpath=//*[@id="email"]', 'test@example.com');
        $this->type('xpath=//*[@id="url"]', 'http://www.example.com');
        $this->type('xpath=//*[@id="comment"]', 'This is a test comment.');

        // submit form
        $this->click('xpath=//*[@id="submit"]');

        //login
        $this->adminLogin();

        $this->openAndWait($this->wordpressUrl . 'wp-admin/edit-comments.php');
        $this->assertTitle('Comments');

        $this->assertTextPresent('exact:' . $author);
        $this->assertTextPresent('exact:test@example.com');
        $this->assertTextPresent('exact:This is a test comment.');

    }

    public function testCleanup()
    {
        $this->adminLogin();
        $this->adminLogin();
        $this->deletePosts();
        $this->adminLogin();
        if (isset(self::$depends['user'])) {
            $user = self::$depends['user'];
            $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/users.php?usersearch=' . $user[0]);
            $this->waitForTitle('Users');
            $this->clickAndWait('xpath=//*[@id="user-'. $user[1] . '"]/td[1]/div/span[2]/a');
            sleep(2);
            $this->clickAndWait('xpath=//*[@id="updateusers"]/div/p[2]/input');
        }
    }
}