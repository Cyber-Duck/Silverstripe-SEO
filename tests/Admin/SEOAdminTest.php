<?php

namespace CyberDuck\SEO\Tests\Admin;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;

class SEOAdminText extends FunctionalTest
{
    public function testAdminRoute()
    {   
        $this->logOut();
        $page = $this->get('admin/seo-admin');
        $this->assertEquals(403, $page->getStatusCode());

        $admin = $this->objFromFixture(Member::class, 'admin');
        $this->logInAs($admin);
    
        $page = $this->get('admin/seo-admin');
        $this->assertEquals(200, $page->getStatusCode());
        $this->logOut();
    }
}