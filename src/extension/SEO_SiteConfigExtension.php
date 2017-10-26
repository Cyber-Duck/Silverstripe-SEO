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
        'OGSiteName'           => 'Varchar(512)',
        'TwitterHandle'        => 'Varchar(512)',
        'CreatorTwitterHandle' => 'Varchar(512)',
        'FacebookAppID'        => 'Varchar(512)',
        'UseTitleAsMetaTitle'  => 'Boolean',
        'AutomapPriority'      => 'Boolean' // todo
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
        $fields->addFieldToTab('Root.SEO', HeaderField::create('SEO'));
        $fields->addFieldToTab('Root.SEO', LiteralField::create(false, 'SilverStripe SEO V'.Config::inst()->get('SEO', 'version')));

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Social Settings'));
        $fields->addFieldToTab('Root.SEO', TextField::create('OGSiteName', 'Open Graph Site Name'));
        $fields->addFieldToTab('Root.SEO', TextField::create('TwitterHandle', 'Twitter handle (no @)'));
        $fields->addFieldToTab('Root.SEO', TextField::create('CreatorTwitterHandle', 'Twitter creator handle (no @)'));
        $fields->addFieldToTab('Root.SEO', TextField::create('FacebookAppID', 'Facebook APP ID'));

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Meta'));
        $fields->addFieldToTab('Root.SEO', CheckboxField::create('UseTitleAsMetaTitle', 'Default Meta title to page Title'));

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Sitemap'));
        $fields->addFieldToTab('Root.SEO', CheckboxField::create('AutomapPriority', 'Automap sitemap priority based on depth'));
		
		return $fields;
	}
}