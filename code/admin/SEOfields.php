<?php
/**
 * SEOfields
 * This creates our admin CMS database form fields
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOfields {

	/**
	 * @var object $seo An instance of the SEO class
	 **/
	private $seo;

	/**
	 * @var object $seo An instance of the SEO class
	 **/
	private $title_length = 55;

	/**
	 * @var object $seo An instance of the SEO class
	 **/
	private $description_length = 156;

	/**
	 * Our constructor assigns the SEO instance to a class property
	 *
	 * @param object $seo
	 *
	 * @return void
	 **/
	function __construct(SEO $seo)
	{
		$this->seo = $seo;
	}

	/**
	 * The method which creates our admin form fields
	 *
	 * @param object $fields
	 *
	 * @return object
	 **/
	public function makeFields($fields)
	{
		$fields->addFieldToTab('Root.SEO', new HeaderField('Meta'));
		$fields->addFieldToTab('Root.SEO', new LabelField('Build your content based SEO here'));

		// Meta title input
		$title = new TextField('Title');
		$title->setTitle('Meta Title');
		$title->setMaxLength($this->title_length);

		$fields->addFieldToTab('Root.SEO', $title); 

		// Meta description input
		$description = new TextField('Description');
		$description->setTitle('Meta Description');
		$description->setMaxLength($this->description_length);

		$fields->addFieldToTab('Root.SEO', $description); 

		// Meta keywords input
		$keywords = new TextField('Keywords');
		$keywords->setTitle('Meta Keywords');

		$fields->addFieldToTab('Root.SEO', $keywords); 

		$fields->addFieldToTab('Root.SEO', new HeaderField('Indexing'));
		$fields->addFieldToTab('Root.SEO', new LabelField('Control your site and page indexing here'));

		// Meta canonical input
		$canonical = new TextField('Canonical');
		$canonical->setTitle('Canonical');

		$fields->addFieldToTab('Root.SEO', $canonical); 

		// Meta robots input
		$robots = new DropdownField('Robots');
		$robots->setTitle('Meta Title');
		$robots->setSource($this->Robots());

		$fields->addFieldToTab('Root.SEO', $robots); 

		$fields->addFieldToTab('Root.SEO', new HeaderField('Social'));
		$fields->addFieldToTab('Root.SEO', new LabelField('All your social sharing meta here'));

		// Show social Meta input
		$social = new CheckboxField('Social');
		$social->setTitle('Show Social Meta');

		$fields->addFieldToTab('Root.SEO', $social); 

		$fields->addFieldToTab('Root.SEO', new LabelField('This image covers og: and twitter:'));

		// Social Meta image
		$image = new TextField('Image');
		$image->setTitle('Social Sharing Image');

		$fields->addFieldToTab('Root.SEO', $image); 

		// og:site_name
		$og_sitename = new TextField('OgSitename');
		$og_sitename->setTitle('Open Graph Sitename');

		$fields->addFieldToTab('Root.SEO', $og_sitename); 

		// og:type
		$og_type = new DropdownField('OgType');
		$og_type->setTitle('Open Graph Type');
		$og_type->setSource($this->OgType());

		$fields->addFieldToTab('Root.SEO', $og_type); 

		// og:locale
		$og_locale = new DropdownField('OgLocale');
		$og_locale->setTitle('Open Graph Locale');
		$og_locale->setSource($this->OgLocale());

		$fields->addFieldToTab('Root.SEO', $og_locale); 

		// twitter:card
		$twitter_card = new DropdownField('TwitterCard');
		$twitter_card->setTitle('Twitter Card');
		$twitter_card->setSource($this->TwitterCard());

		$fields->addFieldToTab('Root.SEO', $twitter_card); 

		// twitter:site
		$twitter_site = new TextField('TwitterSite');
		$twitter_site->setTitle('Twitter Site');

		$fields->addFieldToTab('Root.SEO', $twitter_site); 

		// twitter:creator
		$twitter_creator = new TextField('TwitterCreator');
		$twitter_creator->setTitle('Twitter Creator');

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