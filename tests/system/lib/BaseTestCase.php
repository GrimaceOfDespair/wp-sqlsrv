<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
if (!file_exists( dirname(__FILE__) . '/../config/config.php')) {
    trigger_error('Please edit the config.php.dist file with your settings and rename to config.php', E_USER_ERROR);
}
require_once dirname(__FILE__) . '/../config/config.php';

class System_BaseTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    protected $config;
    protected $wordpressUrl;
    static protected $posts = array();
    static protected $pages = array();
    protected $wordpressQueryLog = null;
    static protected $depends = array(); // generic place to throw all dependency information

    protected function setUp()
    {
        $this->config = $config = new Tests_System_Config;

        $this->setBrowser($config->browser['browser']);
        $this->setBrowserUrl($config->wordpressUrl);
        $this->wordpressUrl = $config->wordpressUrl;

        if (isset($this->config->localTest)) {
            $this->loadWPConstants();
            if (defined('SAVEQUERIES') && SAVEQUERIES) {
                // get WP query log file
                if (defined('QUERY_LOG')) {
                    $this->wordpressQueryLog = QUERY_LOG;
                } else {
                    $this->wordpressQueryLog = $this->config->wordpressPath . 
                        'wp-content' . DIRECTORY_SEPARATOR . 'queries.log';
                }

                // remove log before test runs if exists
                if (file_exists($this->wordpressQueryLog)) {
                    unlink($this->wordpressQueryLog);
                }
            }
        }
    }

    protected function adminLogin()
    {
        parent::openAndWait($this->wordpressUrl . 'wp-login.php');
        $this->waitForTitle('Log In');

        // Fill out the form!
        $this->type('xpath=//*[@id="user_login"]', $this->config->wordpressAdminUser);
        $this->type('xpath=//*[@id="user_pass"]', $this->config->wordpressAdminPass);

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="wp-submit"]');
        $this->waitForTitle('*');
        sleep(3); // try to get around bad auths

    }

    protected function adminLogout()
    {
        parent::openAndWait($this->wordpressUrl . 'wp-admin/index.php');

        $this->assertTitle('Dashboard');

        // Click on the "yes log me out"
        $this->clickAndWait('xpath=//*[@id="user_info"]/p/a[2]');
    }

    protected function createPost()
    {
        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/post-new.php');

        $this->assertTitle('Add New Post');

        $title = time() . 'title';

        // Fill out the form!
        $this->type('xpath=//*[@id="content"]', $title);
        $this->type('xpath=//*[@id="title"]', 'Test Content');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');

        $this->waitForTitle('Edit Post');

        // Verify post published
        $this->assertTextPresent('exact:Post published.');

        // get and return our post id
        $id = $this->getAttribute('xpath=//*[@id="post_ID"]@value');
        self::$posts[] = $id;
        return $id;

    }

    protected function createPage()
    {
        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/post-new.php?post_type=page');

        $this->assertTitle('Add New Page');

        $title = time() . 'title';

        // Fill out the form!
        $this->type('xpath=//*[@id="content"]', $title);
        $this->type('xpath=//*[@id="title"]', 'Test Content');

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="publish"]');

        $this->waitForTitle('Edit Page');

        // Verify post published
        $this->assertTextPresent('exact:Page published.');

        // get and return our post id
        $id = $this->getAttribute('xpath=//*[@id="post_ID"]@value');
        self::$pages[] = $id;
        return $id;

    }

    protected function deletePosts()
    {
        $this->openAndWaitAdmin($this->wordpressUrl . '/wp-admin/edit.php');
        $this->waitForTitle('Posts');
        foreach(self::$posts as $id) {
            $this->click('xpath=//*[@id="post-' . $id . '"]/th/input');
        }
        $this->select('xpath=//*[@id="posts-filter"]/div[3]/div/select', 'Move to Trash');
        $this->clickAndWait('xpath=//*[@id="doaction2"]');
        $this->openAndWaitAdmin($this->wordpressUrl . '/wp-admin/edit.php?post_status=trash&post_type=post');
        $this->waitForTitle('Posts');
        $this->clickAndWait('xpath=//*[@id="delete_all"]');
        $this->waitForTitle('Posts');
    }

    protected function openAndWaitAdmin($url)
    {
        parent::openAndWait($url);
        $this->waitForTitle('*');

        $title = $this->getTitle();
        // shoot, we've been "reauth" tagged
        if (preg_match('/Log In/', $title)) {
            sleep(3); // try to get around bad auths

            // Fill out the form!
            $this->type('xpath=//*[@id="user_login"]', $this->config->wordpressAdminUser);
            $this->type('xpath=//*[@id="user_pass"]', $this->config->wordpressAdminPass);
    
            // Submit the form and we'll be redirected
            $this->clickAndWait('xpath=//*[@id="wp-submit"]');
            $this->waitForTitle('*');
            sleep(3); // try to get around bad auths
        }
    }

    protected function tearDown()
    {
        if (isset($this->config->localTest) && $this->wordpressQueryLog) {
            // if valid log move to test suite driver query log 
            // (grouped into current test being ran)
            if (file_exists($this->wordpressQueryLog) 
                && is_readable($this->wordpressQueryLog)) {
                $log_contents = file_get_contents($this->wordpressQueryLog);
                if (!defined('DB_TYPE')) {
                    define('DB_TYPE', 'mysql');
                }
                $query_log = $this->config->queryLogPath . 
                    DB_TYPE . '_' . $this->name  . '_queries.log';

				file_put_contents($query_log, $log_contents);

                // remove wp query log to be ready for next test run
                unlink($this->wordpressQueryLog);
            }
        }
    }

    /**
     * Given a first parenthesis ( ...will find its matching closing paren )
     *
     * @param string $str given string
     * @param int $start_pos position of where desired starting paren begins+1
     *
     * @return int position of matching ending parenthesis
     */
    protected function getMatchingParen($str, $start_pos)
    {
        $count = strlen($str);
        $bracket = 1;
        for ( $i = $start_pos; $i < $count; $i++ ) {
            if ( $str[$i] == '(' ) {
                $bracket++;
            } elseif ( $str[$i] == ')' ) {
                $bracket--;
            }
            if ( $bracket == 0 ) {
                return $i;
            }
        }
    }

    /**
     * fetch WP Config Constants
     *
     * load wordpress config constants
     * doing this instead of just including the file because
     * at the end of the config file in requires wp-settings.php
     * which in turn barks errors...
     *
     * @return void
     */
    protected function loadWPConstants()
    {
        $wp_config = explode("\n", file_get_contents($this->config->wordpressPath . 'wp-config.php'));
        foreach ($wp_config as $line) {
            if (stripos($line, 'define') === 0) {
                $first_paren = strpos($line, '(');
                $last_paren = $this->getMatchingParen($line, $first_paren + 1);
                $first_comma = strpos($line, ',', $first_paren);
                $constant = substr($line, $first_paren + 1, $first_comma - ($first_paren + 1));
                $value = substr($line, $first_comma + 1, $last_paren - ($first_comma +1));
                
                $constant = trim(trim($constant), "'");
                $value = trim(trim($value), "'");
                if (!defined($constant)) {
                    define($constant, $value);
                }
            }
        }
    }
}
