<?php

class SitemapXML_Controller extends Page_Controller {

    private static $url_handlers = array(
        '' => 'GetSitemapXML'
    );

    private static $allowed_actions = array(
        'GetSitemapXML'
    );

    public function init()
    {
        parent::init();
    }

    public function GetSitemapXML()
    {
        $this->response->addHeader("Content-Type", "application/xml");
        $this->getSiteTree();

        $sitemap = new ArrayData(array(
            'Pages' => $this->pages
        ));
        return $sitemap->renderWith('SitemapPages');
    }

    private function getSiteTree()
    {
        $this->pages = SiteTree::get()->filter(array(
            'ClassName:not' => 'ErrorPage'
        ));
    }
}