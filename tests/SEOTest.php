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