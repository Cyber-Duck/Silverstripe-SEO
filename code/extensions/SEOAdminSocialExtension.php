<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOAdminExtension extends DataExtension {

    /**
     * @static array $db CMS social settings fields
     **/
	private static $db = array(
		'FacebookURL'           => 'Varchar(512)',
		'FBappID'               => 'Varchar(512)',
		'TwitterURL'            => 'Varchar(512)',
		'TwitterHandle'         => 'Varchar(512)',
		'GooglePlusURL'         => 'Varchar(512)',
		'PinterestURL'          => 'Varchar(512)',
		'InstagramURL'          => 'Varchar(512)',
		'SoundcloudURL'         => 'Varchar(512)',
		'YoutubeURL'            => 'Varchar(512)'
	);

    /**
     * @static array $has_one CMS social settings image
     **/
	public static $has_one = array(
		'SocialImage'          => 'Image'
	);
    
    /**
     * Attach the fields to CMS settings
     *
     * @return FieldList
     **/
	public function updateCMSFields(FieldList $fields)
	{
		$fields->addFieldToTab('Root.SocialMedia', HeaderField::create('Social Settings'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('FacebookURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('FBappID','Facebook App ID'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('TwitterURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('TwitterHandle'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('GooglePlusURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('PinterestURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('SoundcloudURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('InstagramURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('YoutubeURL'));

		return $fields;
	}
}