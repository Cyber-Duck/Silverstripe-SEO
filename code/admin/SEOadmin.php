<?php
/**
 * SEOadmin
 * This sets up our admin databse SEO fields.
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOadmin extends SiteTree {

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

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.SEO', new HeaderField('Meta'));

		$fields->addFieldToTab('Root.SEO', new TextField('Title', 'Meta Title', '', 55)); 
		$fields->addFieldToTab('Root.SEO', new TextField('Description', 'Meta Description', '', 156)); 
		$fields->addFieldToTab('Root.SEO', new TextField('Keywords', 'Meta Keywords'));

		$fields->addFieldToTab('Root.SEO', new HeaderField('Indexing'));

		$fields->addFieldToTab('Root.SEO', new TextField('Canonical')); 
		$fields->addFieldToTab('Root.SEO', new DropdownField('Robots', 'Robots', $this->Robots()));

		$fields->addFieldToTab('Root.SEO', new HeaderField('Social'));

		$fields->addFieldToTab('Root.SEO', new CheckboxField('Social', 'Show Social Meta')); 
		$fields->addFieldToTab('Root.SEO', new TextField('Image')); 
		$fields->addFieldToTab('Root.SEO', new TextField('OgSitename', 'Open Graph Sitename')); 
		$fields->addFieldToTab('Root.SEO', new DropdownField('OgType', 'Open Graph Type', $this->OgType()));
		$fields->addFieldToTab('Root.SEO', new DropdownField('OgLocale', 'Open Graph Locale', $this->OgLocale()));
		$fields->addFieldToTab('Root.SEO', new DropdownField('TwitterCard', 'Twitter Card', $this->TwitterCard())); 
		$fields->addFieldToTab('Root.SEO', new TextField('TwitterSite', 'Twitter Site', '@')); 
		$fields->addFieldToTab('Root.SEO', new TextField('TwitterCreator', 'Twitter Creator', '@'));

		return $fields;
	}
	
	public function Robots()
	{
		return array(
			'index,follow',
			'noindex,nofollow',
			'noindex,follow',
			'index,nofollow'
		);
	}

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

	public function OgLocale()
	{
		return array(
			'en_GB',
			'en_US'
		);
	}

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