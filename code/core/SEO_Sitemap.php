<?php

/**
 * Generates an HTML sitemap list
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Sitemap {

    /**
     * @since version 1.2
     *
     * @var array $objects An array of objects with pages to include in the sitemap
     **/
    private $objects;

    /**
     * @since version 1.2
     *
     * @var string $url The URL to use for the current sitemap page
     **/
    private $url;

    /**
     * @since version 1.2
     *
     * @var string $xml The XML to output
     **/
    private $xml;

    /**
     * @since version 1.2
     *
     * @var string $html The HTML to output
     **/
    private $html;

    public function __construct()
    {
        $this->objects = Config::inst()->get('SEO_Sitemap', 'objects');

        $this->url = substr(Director::AbsoluteBaseURL(),0,-1);
    }

    /**
     * Return an encoded string compliant with XML sitemap standards
     *
     * @since version 1.2
     *
     * @param string $value A sitemap value to encode
     *
     * @return string
     **/
    public function Encode($value)
    {
        return trim(urlencode($value));
    }

    /**
     * Loops through the various page objects and sets the sitemap XML
     *
     * @since version 1.2
     *
     * @return void
     **/
    public function getSitemapXML()
    {
        return Controller::curr()->customise(array(
            'Pages' => $this->getPages(),
            'URL'  => $this->url
        ))->renderWith('SitemapXML');
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return string The sitemap HTML
     **/
    public function getSitemapHTML()
    {
        foreach($this->objects as $object){
            if($object == 'Page'){
                $pages = Page::get()->filter(array(
                    'ParentID'      => 0
                ))->Sort('Sort','ASC');

                $this->getChildPages($pages);
            } else {
                $pages = $object::get()->sort('Priority DESC');

                $this->getObjectPages($pages);
            }
        }
        return $this->html;
    }

    /**
     * Checks if this page should be indexed, if so renders a page object SEO 
     * values into a XML sitemap entry 
     *
     * @since version 1.2
     *
     * @param object $page An object with the SEO extension attached
     *
     * @return string
     **/

    private function getPages()
    {
        $pages = new ArrayList();
        foreach($this->objects as $object){

            $object = $object::get();

            foreach($object as $page){
                $pages->push($page);
            }
        }
        $pages->Sort('Priority DESC');
        return $pages;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @param $pages
     *
     * @return void
     **/
    private function getChildPages($pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page):
            $this->html .= '<li><a href="'.$this->url.$page->Link().'">'.$page->Title.'</a>';

            $children = Page::get()->filter(array(
                'ParentID' => $page->ID
            ))->Sort('ID','ASC');

            $this->getChildPages($children);

            $this->html .= '</li>';
        endforeach;

        $this->html .= '</ul>';

    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @param $pages
     *
     * @return void
     **/
    private function getObjectPages($pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page):
            $this->html .= '<li><a href="'.$this->url.$page->URLSegment.'">'.$page->Title.'</a></li>';
        endforeach;

        $this->html .= '</ul>';
    }
}