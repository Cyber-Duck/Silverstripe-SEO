<?php
/**
 * SEO_Extension
 *
 * Core extension used to attach SEO fields to a DataObject
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_MetaPreviewField extends LiteralField
{
    /**
     * Object instance used to populate Meta from
     *
     * @since version 2.0.0
     **/
  	private $page;

    /**
     * Requires a DataObject to be passed
     *
     * @since version 1.0.0
     *
     * @param DataObject $page
     *
     * @return void
     **/
	public function __construct(DataObject $page)
	{
		$this->page = $page;

		Requirements::javascript(Director::absoluteBaseURL().'seo/assets/js/serp.js');

		parent::__construct('SEO_MetaPreviewField', $this->getMetaContent());
	}

    /**
     * Get the required values to show in the SERP preview 
     *
     * @since version 2.0.0
     *
     * @return ViewableData
     **/
	private function getMetaContent()
	{
        return Controller::curr()->customise([
            'SerpMetaTitle'       => $this->getPageMetaTitle(),
            'SerpMetaLink'        => $this->getPageMetaLink(),
            'SerpMetaDescription' => $this->getPageMetaDescription()
        ])->renderWith('MetaPreview');
	}

    /**
     * Get the Meta title to show in the SERP preview
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    private function getPageMetaTitle()
    {
        if($this->page->MetaTitle) {
            return $this->page->MetaTitle;
        }
        if(class_exists('BlogPost')) {
            if($this->page instanceof BlogPost) {
                if($this->page->Parent()->DefaultPostMetaTitle == 1) {
                    return $this->page->Title;
                }
            }
        }
        return Config::inst()->get('SEO_MetaPreviewField', 'meta_title');
    }

    /**
     * Get the page URL to show in the SERP preview
     *
     * @since version 2.0.0
     *
     * @throws Exception
     *
     * @return string
     **/
    private function getPageMetaLink()
    {
        if(!method_exists($this->page, 'Link')) {
            throw Exception(sprintf('No Link() method found on %s', $this->page->ClassName));
        }
        return Director::absoluteBaseURL().substr($this->page->link(), 1);
    }

    /**
     * Get the Meta description to show in the SERP preview
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    private function getPageMetaDescription()
    {
        if($this->page->MetaDescripion) {
            return $this->page->MetaDescripion;
        }
        if(class_exists('BlogPost')) {
            if($this->page instanceof BlogPost) {
                if($this->page->Parent()->DefaultPostMetaDescription == 1) {
                    return strip_tags($this->page->Summary);
                }
            }
        }
        return Config::inst()->get('SEO_MetaPreviewField', 'meta_description');
    }
}