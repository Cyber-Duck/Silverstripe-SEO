<?php
/**
 * SEO
 * This sets up our database fields
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO extends SiteTree {

	/**
	 * @static array $db Our admin CMS database SEO fields
	 **/
	private static $db = array(
		'Title'          => 'Varchar(70)',
		'Description'    => 'Varchar(180)',
		'Keywords'       => 'Varchar(255)',
		'Canonical'      => 'Varchar(255)',
		'Robots'         => 'Varchar(16)',
		'Social'         => 'boolean',
		'Image'          => 'Varchar(255)',
		'OgSitename'     => 'Varchar(255)',
		'OgType'         => 'Varchar(10)',
		'OgLocale'       => 'Varchar(5)',
		'TwitterCard'    => 'Varchar(30)',
		'TwitterSite'    => 'Varchar(255)',
		'TwitterCreator' => 'Varchar(255)'
	);

	/**
	 * @static array $db Our admin CMS database SEO fields
	 **/
	private static $defaults = array(
		'Title'          => '',
		'Description'    => '',
		'Keywords'       => '',
		'Canonical'      => '',
		'Robots'         => '',
		'Social'         => 1,
		'Image'          => '',
		'OgSitename'     => '',
		'OgType'         => '',
		'OgLocale'       => '',
		'TwitterCard'    => '',
		'TwitterSite'    => '@',
		'TwitterCreator' => '@'
	);
	
	/**
	 * This method creates our SEO tab in our admin page and creates the 
	 * necessary fields within it.
	 *
	 * @return object
	 **/
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		// create an SEO fields object and inject an instance of this
		$SEOfields = new SEOfields($this);
		$fields = $SEOfields->makeFields($fields);

		return $fields;
	}

	public static function meta()
	{
		$meta = new SEOmeta();

		return $meta->tags();
	}
}