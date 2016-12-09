<?php
/**
 * SEO_Sitemap
 *
 * Generates an HTML sitemap list
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Sitemap
{
    /**
     * An array of objects with pages to include in the sitemap
     *
     * @since version 1.0.0
     *
     * @var array $objects
     **/
    private $objects;

    /**
     * The URL to use for the current sitemap page
     *
     * @since version 1.0.0
     *
     * @var string $url 
     **/
    private $url;

    /**
     * The XML to output
     *
     * @since version 1.0.0
     *
     * @var string $xml 
     **/
    private $xml;

    /**
     * The HTML to output
     *
     * @since version 1.0.0
     *
     * @var string $html 
     **/
    private $html;

    /**
     * Initialise config
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function __construct()
    {
        $this->objects = Config::inst()->get('SEO_Sitemap', 'objects');

        $this->url = substr(Director::AbsoluteBaseURL(),0,-1);
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
    public function Encode($value)
    {
        return trim(urlencode($value));
    }

    /**
     * Return the sitemap XML
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function getSitemapXML()
    {
        return Controller::curr()->customise(array(
            'Pages' => $this->getPages(),
            'URL'   => $this->url
        ))->renderWith('SitemapXML');
    }

    /**
     * Return the sitemap HTML
     *
     * @since version 1.0.0
     *
     * @return string The sitemap HTML
     **/
    public function getSitemapHTML()
    {
        $pages = Page::get()->filter(array(
            'ClassName:not' => 'ErrorPage',
            'Robots:not'    => 'noindex,nofollow',
            'SitemapHide'   => 0,
            'ParentID'      => 0
        ))->Sort('Sort','ASC');

        $this->getChildPages($pages);

        return $this->html;
    }

    /**
     * Merge an objects pages to the current page set
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    private function getPages()
    {
        $pages = new ArrayList();
        $config = Config::inst()->get('SEO_Sitemap','objects');

        foreach($this->objects as $className => $value){

            $object = $className::get()->filter(array(
                'Robots:not'  => 'noindex,nofollow',
                'SitemapHide' => 0
            ));

            foreach($object as $page){
                if(!$page instanceof Page){
                    $page->Link = $this->getPrefix($className, $page);
                }
                $pages->push($page);
            }
        }
        $pages->Sort('Priority DESC');
        return $pages;
    }

    /**
     * Get the URL link prefix from the YML config setting
     *
     * @since version 1.0.0
     *
     * @param string $name
     * @param object $page
     *
     * @return string
     **/
    private function getPrefix($name, $page)
    {
        if(isset($this->objects[$name]['prefix'])){
            return "/".$this->objects[$name]['prefix']."/".$page->URLSegment."/";
        }
    }

    /**
     * Iterate through child Page class objects
     *
     * @since version 1.0.0
     *
     * @param object $pages
     *
     * @return void
     **/
    private function getChildPages($pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page){
            $this->html .= '<li><a href="'.$this->url.$page->Link().'">'.$page->Title.'</a>';

            foreach($this->objects as $className => $config){
                if($config['parent_id'] == $page->ID && $config['parent_id'] !== 0){
                    $pages = $className::get()->filter(array(
                        'Robots:not'  => 'noindex,nofollow',
                        'SitemapHide' => 0
                    ))->sort('Priority DESC');
                    $this->getObjectPages($pages);
                }
            }
            $children = Page::get()->filter(array(
                'ParentID'    => $page->ID,
                'Robots:not'  => 'noindex,nofollow',
                'SitemapHide' => 0
            ))->Sort('ID','ASC');

            if($children) $this->getChildPages($children);

            $this->html .= '</li>';
        }

        $this->html .= '</ul>';

    }

    /**
     * Iterate through child non Page class objects
     *
     * @since version 1.0.0
     *
     * @param object $pages
     *
     * @return void
     **/
    private function getObjectPages($pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page):
            $this->html .= '<li><a href="'.$this->url.$this->getPrefix($page->ClassName, $page).'">'.$page->Title.'</a></li>';
        endforeach;

        $this->html .= '</ul>';
    }
}