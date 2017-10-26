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
     * The HTML to output
     *
     * @since version 1.0.0
     *
     * @var string $html 
     **/
    private $html;

    /**
     * The current site host and protocol
     *
     * @since version 1.1.0
     *
     * @var string $host 
     **/
    private $host;
    
    /**
     * Get subsite domains
     *
     * @since version 1.1.0
     *
     * @return array
     **/
    private function getSitemapHost()
    {
        if(class_exists('SubSite')) {
            $site = DataObject::get_by_id('SubsiteDomain', Subsite::currentSubSiteID());

            if($site) return Director::protocol().$site->Domain;
        }
        return Director::protocolAndHost();
    }

    /**
     * Get subsite domains
     *
     * @since version 1.1.0
     *
     * @return array
     **/
    private function getSitemapFilters()
    {
        $filters = [
            'ClassName:not' => 'ErrorPage',
            'Robots:not'    => 'noindex,nofollow',
            'SitemapHide'   => 0
        ];
        if(class_exists('SubSite')) {
            $filters['SubsiteID'] = Subsite::currentSubSiteID();
        }
        return $filters;
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
        $data = ArrayList::create();

        $filters = $this->getSitemapFilters();
        $filters['ParentID'] = 0;

        $pages = Page::get()->filter($filters);

        $this->getChildPages($pages);

        return $this->html;
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
    private function getChildPages(DataList $pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page) {
            $this->html .= sprintf('<li><a href="%s%s">%s</a>', $this->host, $page->Link(), $page->Title);

            $filters = $this->getSitemapFilters();
            $filters['ParentID'] = $page->ID;

            // get page children
            $children = Page::get()->filter($filters);

            if($children) $this->getChildPages($children);

            // get object children
            $this->getObjectChildren($page->ID);

            $this->html .= '</li>';
        }
        $this->html .= '</ul>';
    }

    /**
     * Get children which are not of Page type
     *
     * @since version 1.1.0
     *
     * @param int $id
     *
     * @return void
     **/
    private function getObjectChildren($id)
    {
        foreach(Config::inst()->get('SEO_Sitemap', 'objects') as $class => $config) {
            if($config['parent_id'] == $id) {
                $filters = $this->getSitemapFilters();
                
                $children = $class::get()->filter($filters);

                if($children) {
                    $this->html .= '<ul>';
                    foreach($children as $page) {
                        $this->html .= sprintf('<li><a href="%s%s">%s</a>', $this->host, $page->Link(), $page->Title);
                    }
                    $this->html .= '</ul>';
                }
            }
        }
    }
}