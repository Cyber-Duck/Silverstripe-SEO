<?php

class SEOPaginationTest extends FunctionalTest {
    
    /**
     * Test setting pagination properties and returning the object
     */
    public function setUp()
    {
        parent::setUp();

        $page = new SiteTree(array(
            'Title' => "Test Page",
            'URLSegment' => 'test'
        ));
        $page->write();
        $page->publish('Stage', 'Live');
    }

    /**
     * Test setting pagination properties and returning the object
     */
    public function testPagaintionSetReturn()
    {
        $obj = new SEOPagination();

        $this->assertInstanceOf('SEOPagination', $obj->setURL('http://cyber-duck.co.uk/'));
        $this->assertInstanceOf('SEOPagination', $obj->setTotal(100));
        $this->assertInstanceOf('SEOPagination', $obj->setPerPage(20));
        $this->assertInstanceOf('SEOPagination', $obj->setParam('page'));
        $this->assertInstanceOf('SEOPagination', $obj->get());
    }

    /**
     * Test the first pagination page tags
     */
    public function testPagaintionFirstPageHTML()
    {
        $page = $this->get('/test');

        $this->assertEquals(200, $page->getStatusCode());

        $obj = new SEOPagination();

        $obj->setURL('https://www.cyber-duck.co.uk/');
        $obj->setTotal(100);
        $obj->setPerPage(20);
        $obj->setParam('page');
        $obj->get();

        $this->assertContains('<link rel="next" href="https://www.cyber-duck.co.uk/?page=20">', $obj->html());
    }
}