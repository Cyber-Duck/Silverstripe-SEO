<?php

namespace CyberDuck\SEO\Tests\Controller;

use SilverStripe\Dev\FunctionalTest;

class RobotsTxtControllerTest extends FunctionalTest
{
    public function testRobotsRoute()
    {
        $page = $this->get('robots.txt');

        $this->assertEquals(200, $page->getStatusCode());
        $this->assertEquals('text/plain', $page->getHeader('Content-Type'));
    }

    public function testRobotsText()
    {
        $page = $this->get('robots.txt');
        
        $this->assertContains('User-agent: ', $page->getBody());
        $this->assertContains('Sitemap: ', $page->getBody());
    }
}