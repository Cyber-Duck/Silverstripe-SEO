<?php
/**
 * SEO_SiteConfigExtension
 *
 * Core extension used to add SEO and social settings
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_SiteConfigExtension extends DataExtension
{
    /**
     * Extra admin fields
     *
     * @since version 1.0.6
     *
     * @config array $has_one 
     **/
	private static $db = [
        'TwitterHandle' => 'Varchar(512)',
        'FacebookAppID' => 'Varchar(512)',
        'OGSiteName'    => 'Varchar(512)'
    ];
    
    /**
     * Adds our fields to the site config field list
     *
     * @since version 1.0.6
     *
     * @param string $fields The current FieldList object
     *
     * @return object Return the FieldList object
     **/
	public function updateCMSFields(FieldList $fields)
	{
        $fields->addFieldToTab('Root.Social', HeaderField::create('Social Settings'));
        $fields->addFieldToTab('Root.Social', TextField::create('TwitterHandle', 'Twitter Handle'));
        $fields->addFieldToTab('Root.Social', TextField::create('FacebookAppID', 'Facebook APP ID'));
        $fields->addFieldToTab('Root.Social', TextField::create('OGSiteName', 'Open Graph Site Name'));
		
		return $fields;
	}
}