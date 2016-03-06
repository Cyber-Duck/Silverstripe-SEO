<?php

class SEOTest extends FunctionalTest {

    /**
     * Test SEO object initialisation
     */
    public function testInitialiseSEO()
    {
        $seo = SEO::init();

        $this->assertInstanceOf('SEO', $seo);
    }

    /**
     * Test core Meta tag values
     */
    public function testMetaOutput()
    {
        SEO::init();

        $page = new SiteTree(array(
            'MetaTitle'       => 'test_title',
            'MetaDescription' => 'test_description',
            'Canonical'       => 'test_canonical',
            'Robots'          => 'test_index,follow',
            'OGtype'          => 'test_ogtype',
            'OGlocale'        => 'test_locale',
            'TwitterCard'     => 'test_summary'
        ));
        $page->write();
        $page->publish('Stage', 'Live');

        SEO::setPage($page);

        $tags = SEO::HeadTags();

        $this->assertContains('<title>test_title</title>', $tags);
        $this->assertContains('<meta property="og:title" content="test_title">', $tags);
        $this->assertContains('<meta name="twitter:title" content="test_title">', $tags);

        $this->assertContains('<meta name="description" content="test_description">', $tags);
        $this->assertContains('<meta property="og:description" content="test_description">', $tags);
        $this->assertContains('<meta name="twitter:description" content="test_description">', $tags);

        $this->assertContains('<link rel="canonical" href="test_canonical">', $tags);
        $this->assertContains('<meta name="robots" content="test_index,follow">', $tags);

        $this->assertContains('<meta property="og:type" content="test_ogtype">', $tags);
        $this->assertContains('<meta property="og:locale" content="test_locale">', $tags);

        $this->assertContains('<meta name="twitter:card" content="test_summary">', $tags);
    }

    /**
     * Test SEO object initialisation
     */
    public function testPageUrlSetGet()
    {
        SEO::init();

        $url = 'https://www.cyber-duck.co.uk/';

        SEO::setPageURL($url);

        $this->assertEquals($url, SEO::getPageURL());
    }

    /**
     * Test setting a page object
     */
    public function testPageObjectSetGet()
    {
        SEO::init();

        SEO::setPage(new StdClass);

        $this->assertInstanceOf('StdClass', SEO::getPage());
    }

    /**
     * Test setting meta title and description
     */
    public function testMetaSetGet()
    {
        SEO::init();

        $title = 'Meta Title';

        SEO::setTitle($title);

        $this->assertEquals($title, SEO::getTitle());

        $description = 'Meta Description';

        SEO::setDescription($description);

        $this->assertEquals($description, SEO::getDescription());
    }

    /**
     * Test setting meta title and description with dynamic placeholders
     */
    public function testDynamicMetaSetGet()
    {
        SEO::init();

        $obj = new StdClass();
        $obj->FirstName = 'Andy';

        SEO::setDynamicTitle('[FirstName] Second Name', $obj);

        $this->assertEquals('Andy Second Name', SEO::getTitle());

        $obj = new StdClass();
        $obj->FirstName = 'Andy';

        SEO::setDynamicDescription('[FirstName] Second Name', $obj);

        $this->assertEquals('Andy Second Name', SEO::getDescription());
    }

    /**
     * Test setting and returning the pagination object
     */
    public function testPagination()
    {
        SEO::init();

        $obj = SEO::setPagination(100);

        $this->assertInstanceOf('SEOPagination', $obj);
    }
}