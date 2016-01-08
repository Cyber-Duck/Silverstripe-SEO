<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SeoExtension extends DataExtension {

    /**
     * @var string $priority The admin SEO panel heading
     **/
    private $title = 'Meta Tags and SEO';

    /**
     * @var float $priority The default page sitemap page priority
     **/
    private $image_size = 1024;

    /**
     * @var float $priority The default page sitemap page priority
     **/
    private $image_folder = 'Social';

    /**
     * @static array $db Our page fields
     **/
	private static $db = array(
        'MetaTitle'       => 'Varchar(512)',
        'MetaDescription' => 'Varchar(512)',
        'Canonical'       => 'Varchar(512)',
        'Priority'        => 'Decimal(3,2)',
        'ChangeFrequency' => 'Varchar(100)',
        'Robots'          => 'Varchar(100)',
        'ShowSocial'      => 'Boolean',
        'OGtype'          => 'Varchar(100)',
        'OGlocale'        => 'Varchar(10)',
        'TwitterCard'     => 'Varchar(100)',
	);

    /**
     * @static array $db Social image and other has_one relations
     **/
    private static $has_one = array(
        'SocialImage'     => 'Image'
    );

    /**
     * @static array $has_many Class relations
     **/
    private static $has_many = array(
        'HeadTags'        => 'HeadTag'
    );

    /**
     * @static array $defaults Default values for fields in this class
     **/
    static $defaults = array (
        'Priority'        => 0.50,
        'ShowSocial'      => '1'
    );
    
    /**
     * Adds our SEO meta fields to the page field list
     *
     * @return FieldList
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        $fields->addFieldToTab('Root.SEO', $this->preview());

        $fields->addFieldToTab('Root.SEO', HeaderField::create($this->title));

        $fields->addFieldToTab('Root.SEO', TextField::create('MetaTitle')); 
        $fields->addFieldToTab('Root.SEO', TextareaField::create('MetaDescription'));

        $fields->addFieldToTab('Root.SEO', TextField::create('Canonical'));
        $fields->addFieldToTab('Root.SEO', NumericField::create('Priority'));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('ChangeFrequency', 'Change Frequency', $this->SitemapChangeFrequency())); 
        $fields->addFieldToTab('Root.SEO', DropdownField::create('Robots', 'Robots', $this->IndexRules()));
        $fields->addFieldToTab('Root.SEO', CheckboxField::create('ShowSocial'));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('OGtype', 'Open Graph Type', $this->OGtype()));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('OGlocale', 'Open Graph Locale', $this->OGlocale()));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('TwitterCard', 'Twitter Card', $this->TwitterCardTypes()));
        $fields->addFieldToTab('Root.SEO', $this->SharingImage());

        return $fields;
    }

    private function preview()
    {
        return LiteralField::create('Preview', Controller::curr()->renderWith('MetaPreview'));
    }
    
    /**
     * Set some default values for the fields
     *
     * @return void
     **/
    public function populateDefaults()
    {
        parent::populateDefaults();

        $this->Priority = $this->default_priority;
    }
    
    /**
     * Creates our social sharing upload field
     *
     * @return UploadField
     **/
    private function SharingImage()
    {
        $image = new UploadField('SocialImage');

        $image->getValidator()->setAllowedMaxFileSize($this->ImageSize());
        $image->setFolderName($this->image_folder);
        $image->setAllowedFileCategories('image');

        return $image;
    }
    
    /**
     * Returns the max upload image size
     *
     * @return Int
     **/
    private function ImageSize()
    {
        return $this->image_size * 1024;
    }
    
    /**
     * Returns an array of sitemap change frequencies used in a sitemap.xml file
     *
     * @return array
     **/
    private function SitemapChangeFrequency()
    {
        return array(
            'always'  => 'Always',
            'hourly'  => 'Hourly',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
            'never'   => 'Never'
        );
    }
    
    /**
     * Returns an array of robots crawling rules used in a robots meta tag
     *
     * @return array
     **/
    private function IndexRules()
    {
        return array(
            'index,follow'     => 'index,follow',
            'noindex,nofollow' => 'noindex,nofollow',
            'noindex,follow'   => 'noindex,follow',
            'index,nofollow'   => 'index,nofollow'
        );
    }
    
    /**
     * Return an array of Facebook Open Graph Types
     *
     * @return array
     **/
    private function OGtype()
    {
        return array(
            'website' => 'Website',
            'article' => 'Article',
            'book'    => 'Book',
            'profile' => 'Profile',
            'music'   => 'Music',
            'video'   => 'Video'
        );
    }
    
    /**
     * Return an array of Facebook Open Graph locales
     *
     * @return array
     **/
    private function OGlocale()
    {
        return array(
            'en_GB' => 'en_GB',
            'en_US' => 'en_US'
        );
    }
    
    /**
     * Returns an array of Twitter card types
     *
     * @return array
     **/
    private function TwitterCardTypes()
    {
        return array(
            'summary'             => 'Summary',
            'summary_large_image' => 'Summary Large Image',
            'photo'               => 'Photo',
            'gallery'             => 'Gallery',
            'app'                 => 'App',
            'product'             => 'Product'
        );
    }
}