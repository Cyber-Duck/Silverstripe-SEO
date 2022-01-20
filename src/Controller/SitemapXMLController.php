<?php

namespace CyberDuck\SEO\Controller;

use Page;
use CyberDuck\SEO\Generators\SitemapGenerator;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Subsites\Model\Subsite;

/**
 * SitemapXML_Controller
 *
 * sitemap.xml controller
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SitemapXMLController extends Controller
{
    /**
     * Set the required content type header
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function init()
    {
        parent::init();

        $this->response->addHeader("Content-Type", "application/xml");
    }

    /**
     * Route request to default index method
     *
     * @param SS_HTTPRequest $request
     *
     * @since version 1.0.0
     *
     * @return ViewableData
     **/
    public function index(HTTPRequest $request)
    {
        return $this->renderWith('SitemapXML');
    }

    /**
     * Get the current site URL
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getSitemapHost()
    {
        return substr(Director::absoluteBaseUrl(), 0, -1);
    }

    /**
     * Return an encoded string compliant with XML sitemap standards
     *
     * @since version 1.0.0
     *
     * @param string $value A sitemap value to encode
     *
     * @return string
     **/
    public function EncodedValue($value)
    {
        return trim(urlencode($value));
    }

    /**
     * Route request to default index method
     *
     * @param SS_HTTPRequest $request
     *
     * @since version 1.0.0
     *
     * @return ViewableData
     **/
    public function getSitemapPages()
    {
        $pages = ArrayList::create();

        $objects = (array) Config::inst()->get(SitemapGenerator::class, 'objects');

        if(!empty($objects)) {
            foreach($objects as $name => $values) {
                foreach($name::get()->filter($this->getSitemapFilters()) as $page) {
                        $pages->push($page);
                }
            }
        }
        return $pages;
    }

    /**
     * @return array
     */
    private function getSitemapFilters()
    {
        $filters = [
            'ClassName:not' => ErrorPage::class,
            'Robots:not'    => ['noindex,nofollow', 'noindex,follow'],
            'SitemapHide'   => 0
        ];
        if(class_exists('SubSite')) {
            $filters['SubsiteID'] = Subsite::currentSubSiteID();
        }
        return $filters;
    }
}
