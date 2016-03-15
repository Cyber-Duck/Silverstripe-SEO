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
     * @var string $html The sitemap HTML output
     **/
    private $html;

    /**
     * 
     *
     * @since version 1.2
     *
     * @return string The sitemap HTML
     **/
    public function getSitemapHTML()
    {
        $pages = SiteTree::get()->filter(array(
            'ClassName:not' => 'ErrorPage',
            'ParentID'      => 0
        ))->Sort('Sort','ASC');

        $this->getChildPages($pages);

        return $this->html;
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
            $this->html .= '<li><a href="'.$this->URL.$page->Link().'">'.$page->Title.'</a>';

            $children = SiteTree::get()->filter(array(
                'ParentID' => $page->ID
            ))->Sort('ID','ASC');

            $this->getChildPages($children);

            $this->html .= '</li>';
        endforeach;

        $this->html .= '</ul>';

    }
}