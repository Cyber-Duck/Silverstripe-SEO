<?php
/**
 * SEOadmin
 * This sets up our database fields and creates the various fields within the 
 * CMS system which we can populate with our SEO settings.
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOadmin extends SiteTree {

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

		$fields->addFieldToTab('Root.SEO', new HeaderField('Meta'));
		$fields->addFieldToTab('Root.SEO', new LabelField('Build your content based SEO here'));

		// Meta title input
		$title = new TextField('Title');
		$title->setTitle('Meta Title');
		$title->setValue($this->seo_title_default);
		$title->setMaxLength($this->seo_title_length);

		$fields->addFieldToTab('Root.SEO', $title); 

		// Meta description input
		$description = new TextField('Description');
		$description->setTitle('Meta Description');
		$description->setValue($this->seo_description_default);
		$description->setMaxLength($this->seo_description_length);

		$fields->addFieldToTab('Root.SEO', $description); 

		// Meta keywords input
		$keywords = new TextField('Keywords');
		$keywords->setTitle('Meta Keywords');
		$keywords->setValue($this->seo_keywords_default);

		$fields->addFieldToTab('Root.SEO', $keywords); 

		$fields->addFieldToTab('Root.SEO', new HeaderField('Indexing'));
		$fields->addFieldToTab('Root.SEO', new LabelField('Control your site and page indexing here'));

		// Meta canonical input
		$canonical = new TextField('Canonical');
		$canonical->setTitle('Canonical');
		$canonical->setValue($this->seo_canonical_default);

		$fields->addFieldToTab('Root.SEO', $canonical); 

		// Meta robots input
		$robots = new DropdownField('Robots');
		$robots->setTitle('Meta Title');
		$robots->setSource($this->Robots());
		$robots->setValue($this->seo_robots_default);

		$fields->addFieldToTab('Root.SEO', $robots); 

		$fields->addFieldToTab('Root.SEO', new HeaderField('Social'));
		$fields->addFieldToTab('Root.SEO', new LabelField('All your social sharing meta here'));

		// Show social Meta input
		$social = new CheckboxField('Social');
		$social->setTitle('Show Social Meta');
		$social->setValue($this->seo_social_default);

		$fields->addFieldToTab('Root.SEO', $social); 

		$fields->addFieldToTab('Root.SEO', new LabelField('This image covers og: and twitter:'));

		// Social Meta image
		$image = new TextField('Image');
		$image->setTitle('Social Sharing Image');
		$image->setValue($this->seo_image_default);

		$fields->addFieldToTab('Root.SEO', $image); 

		// og:site_name
		$og_sitename = new TextField('OgSitename');
		$og_sitename->setTitle('Open Graph Sitename');
		$og_sitename->setValue($this->seo_og_sitename_default);

		$fields->addFieldToTab('Root.SEO', $og_sitename); 

		// og:type
		$og_type = new DropdownField('OgType');
		$og_type->setTitle('Open Graph Type');
		$og_type->setSource($this->OgType());
		$og_type->setValue($this->seo_og_type_default);

		$fields->addFieldToTab('Root.SEO', $og_type); 

		// og:locale
		$og_locale = new DropdownField('OgLocale');
		$og_locale->setTitle('Open Graph Locale');
		$og_locale->setSource($this->OgLocale());
		$og_locale->setValue($this->seo_og_locale_default);

		$fields->addFieldToTab('Root.SEO', $og_locale); 

		// twitter:card
		$twitter_card = new DropdownField('TwitterCard');
		$twitter_card->setTitle('Twitter Card');
		$twitter_card->setSource($this->TwitterCard());
		$twitter_card->setValue($this->seo_twitter_card_default);

		$fields->addFieldToTab('Root.SEO', $twitter_card); 

		// twitter:site
		$twitter_site = new TextField('TwitterSite');
		$twitter_site->setTitle('Twitter Site');
		$twitter_site->setValue($this->seo_twitter_site_default);

		$fields->addFieldToTab('Root.SEO', $twitter_site); 

		// twitter:creator
		$twitter_creator = new TextField('TwitterCreator');
		$twitter_creator->setTitle('Twitter Creator');
		$twitter_creator->setValue($this->seo_twitter_creator_default);

		$fields->addFieldToTab('Root.SEO', $twitter_creator);

		return $fields;
	}

	/**
	 * An array of Meta robots crawl rules used within our admin SEP tab
	 *
	 * @return array
	 **/
	public function Robots()
	{
		return array(
			'index,follow',
			'noindex,nofollow',
			'noindex,follow',
			'index,nofollow'
		);
	}

	/**
	 * An array of Open Graph (og:) types used within our admin SEO tab
	 *
	 * @return array
	 **/
	public function OgType()
	{
		return array(
			'website',
			'article',
			'book',
			'profile',
			'music',
			'video'
		);
	}

	/**
	 * An array of Open Graph (og:) locales used within our admin SEO tab
	 *
	 * @return array
	 **/
	public function OgLocale()
	{
		return array(
			'en_GB',
			'en_US'
		);
	}

	/**
	 * An array of Open Graph (twitter:card) types used within our admin SEO tab
	 *
	 * @return array
	 **/
	public function TwitterCard()
	{
		return array(
			'summary',
			'summary_large_image',
			'photo',
			'gallery',
			'app',
			'product'
		);
	}
}