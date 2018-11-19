<?php

namespace CyberDuck\SEO\Model\Extension;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\ORM\DataExtension;

/**
 * SeoBlogPostExtension
 *
 * Adds SEO options to the Blog Post Page class
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SeoBlogPostExtension extends DataExtension
{	
    /**
     * Returns the summary description for use in schema description
     *
     * @return string
     */
    public function getSchemaSummary()
	{
		return strip_tags($this->owner->Summary);
	}
    
    /**
     * Returns the PublishDate in ISO 8601 format for use in schema datePublished
     *
     * @return string
     */
	public function getSchemaPublishDate()
	{
		return date('c', strtotime($this->owner->PublishDate));
	}
    
    /**
     * Returns the PublishDate in ISO 8601 format for use in schema dateModified
     *
     * @return string
     */
	public function getSchemaLastEditedDate()
	{
		return date('c', strtotime($this->owner->LastEdited));
	}
}