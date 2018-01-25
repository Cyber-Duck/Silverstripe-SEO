<?php

use SilverStripe\Dev\FunctionalTest;

class SitemapXMLControllerTest extends FunctionalTest
{
    public function testSitemapRoute()
    {
        $page = $this->get('sitemap.xml');

        $this->assertEquals(200, $page->getStatusCode());
        $this->assertEquals('application/xml', $page->getHeader('Content-Type'));
    }

    public function testSitemapXML()
    {
        $page = $this->get('sitemap.xml');

        $this->assertContains('<?xml version="1.0" encoding="UTF-8"?>', $page->getBody());
        $this->assertContains('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">', $page->getBody());
        $this->assertContains('</urlset>', $page->getBody());
    }
}