<?php
/**
 * SEO_SiteConfigExtension
 *
 * Updates the CMS site config with custom fields for SEO and Social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_SiteConfigExtension extends DataExtension
{
    /**
     * Array of extra CMS settings fields
     *
     * @since version 1.0.6
     *
     * @config array $db 
     **/
	private static $db = [
        'FacebookAppID'        => 'Varchar(512)',
        'OGSiteName'           => 'Varchar(512)',
        'TwitterHandle'        => 'Varchar(512)',
        'CreatorTwitterHandle' => 'Varchar(512)'
    ];
    
    /**
     * Adds extra fields for social config across networks
     *
     * @since version 1.0.6
     *
     * @param FieldList $fields The current FieldList object
     *
     * @return FieldList
     **/
	public function updateCMSFields(FieldList $fields)
	{
        $fields->addFieldToTab('Root.Social', HeaderField::create('Social Settings'));
        $fields->addFieldToTab('Root.Social', TextField::create('FacebookAppID', 'Facebook APP ID'));
        $fields->addFieldToTab('Root.Social', TextField::create('OGSiteName', 'Open Graph Site Name'));
        $fields->addFieldToTab('Root.Social', TextField::create('TwitterHandle', 'Twitter site handle'));
        $fields->addFieldToTab('Root.Social', TextField::create('TwitterHandle', 'Twitter site creator handle'));
		
		return $fields;
	}
}