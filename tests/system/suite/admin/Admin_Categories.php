<?php
require_once dirname(__FILE__) . '/../../lib/BaseTestCase.php';

class System_Admin_Categories extends System_BaseTestCase
{
    public function testAddCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=category');
        $this->waitForTitle('Categories');
        
        $name = 'test category' . time();
        $slug = 'testcat' . time();
        $desc = 'test category description' . time();

        // Fill out the form!
        $this->type('xpath=//*[@id="tag-name"]', $name);
        $this->type('xpath=//*[@id="tag-slug"]', $slug);
        $this->type('xpath=//*[@id="tag-description"]', $desc);

        // Submit the form!
        $this->click('xpath=//*[@id="submit"]');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextPresent('exact:' . $name);
        $this->assertTextPresent('exact:' . $slug);
        $this->assertTextPresent('exact:' . $desc);
    }

    public function testEditCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=category');
        $this->waitForTitle('Categories');

        // click edit
        $this->clickAndWait('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[1]/a');
        $this->waitForTitle('Edit Category');

        $name = 'test category 2' . time();
        $slug = 'testcat 2' . time();
        $desc = 'test category description 2' . time();

        // Fill out the form!
        $this->type('xpath=//*[@id="name"]', $name);
        $this->type('xpath=//*[@id="slug"]', $slug);
        $this->type('xpath=//*[@id="description"]', $desc);

        // Submit the form!
        $this->clickAndWait('xpath=//*[@id="edittag"]/p/input');
        $this->waitForTitle('Categories');

        $this->assertTextPresent('exact: Item updated.');
    }

    public function testDeleteCategory()
    {
        //login
        $this->adminLogin();

        $this->openAndWaitAdmin($this->wordpressUrl . 'wp-admin/edit-tags.php?taxonomy=category');
        $this->waitForTitle('Categories');

        $this->chooseOkOnNextConfirmation();
        // click delete - this should really have a safer lookup
        $this->click('xpath=//*/tbody/tr[1]/td[1]/div[1]/span[3]/a');

        // this is AJAX - sleep, everything else fales because waitForVisible and waitForText
        // are broken in phpunit
        sleep(5);

        $this->assertTextNotPresent('exact:test cat');
        $this->assertTextNotPresent('exact:testcat');
        $this->assertTextNotPresent('exact:test category description');
    }
}