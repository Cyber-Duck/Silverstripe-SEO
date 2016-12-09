<?php
/**
 * SitemapXML_Controller
 *
 * XML sitemap controller
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SitemapXML_Controller extends Page_Controller
{
    /**
     * Only one action allowed here
     *
     * @since version 1.0.0
     *
     * @config array $allowed_actions 
     **/
    private static $allowed_actions = array('getSitemap');

    /**
     * Push requests to sitemap.xml to the getSitemap method
     *
     * @since version 1.0.0
     *
     * @config array $url_handlers 
     **/
    private static $url_handlers = array(
        '' => 'getSitemap'
    );

    /**
     * Set properties for this class and call the sitemap render method
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function init()
    {
        $this->response->addHeader("Content-Type", "application/xml"); 
        
        parent::init();
    }

    /**
     * Return the XML sitemap
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    public function getSitemap()
    {
        return SEO::getSitemapXML();
    }
}