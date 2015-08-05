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
	 * @var string $seo_default_title The default Meta title
	 **/
	protected $seo_title_default = '';

	/**
	 * @var int $seo_meta_title_length The maximum Meta title length
	 **/
	protected $seo_title_length = 55;

	/**
	 * @var string $seo_default_description The default Meta description
	 **/
	protected $seo_description_default = '';

	/**
	 * @var int $seo_meta_description_length The maximum Meta description length
	 **/
	protected $seo_description_length = 156;

	/**
	 * @var string $seo_default_keywords The default Meta keywords
	 **/
	protected $seo_keywords_default = '';

	/**
	 * @var string $seo_canonical_default The default Meta canonical value
	 **/
	protected $seo_canonical_default = '';

	/**
	 * @var string $seo_default_robots The default crawl rules
	 **/
	protected $seo_robots_default = 'noindex,nofollow';

	/**
	 * @var string $seo_social_default Show social Meta by default or not
	 **/
	protected $seo_social_default = 1;

	/**
	 * @var string $seo_image_default The default social image URL
	 **/
	protected $seo_image_default = '';

	/**
	 * @var string $seo_og_sitename_default The default social site name
	 **/
	protected $seo_og_sitename_default = '';

	/**
	 * @var string $seo_og_type_default The default open graph site type
	 **/
	protected $seo_og_type_default = '';

	/**
	 * @var string $seo_og_locale_default The default open graph locale
	 **/
	protected $seo_og_locale_default = '';

	/**
	 * @var string $seo_twitter_card_default The default Twitter card type
	 **/
	protected $seo_twitter_card_default = '';

	/**
	 * @var string $seo_twitter_site_default The default Twitter site handle
	 **/
	protected $seo_twitter_site_default = '@';

	/**
	 * @var string $seo_twitter_creator_default The default Twitter creator handle
	 **/
	protected $seo_twitter_creator_default = '@';

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
}