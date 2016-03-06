<?php

/**
 * Creates an SEO admin panel within the CMS for an object / page
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOExtension extends DataExtension {

    /**
     * @since version 1.0
     *
     * @var string $title The CMS page SEO panel heading
     **/
    private $title = 'Meta Tags and SEO';

    /**
     * @since version 1.0
     *
     * @var int $image_size The maximum image size for the social image
     **/
    private $image_size = 1024;

    /**
     * @since version 1.0
     *
     * @var string $image_folder The social image folder
     **/
    private $image_folder = 'Social';

    /**
     * @since version 1.0
     *
     * @static array $db Our page fields
     **/
    private static $db = array(
        'MetaTitle'       => 'Varchar(512)',
        'MetaDescription' => 'Varchar(512)',
        'Canonical'       => 'Varchar(512)',
        'Robots'          => 'Varchar(100)',
        'Priority'        => 'Decimal(3,2)',
        'ChangeFrequency' => 'Varchar(100)',
        'HideSocial'      => 'Boolean',
        'OGtype'          => 'Varchar(100)',
        'OGlocale'        => 'Varchar(10)',
        'TwitterCard'     => 'Varchar(100)',
    );

    /**
     * @since version 1.0
     *
     * @static array $has_one Social image and other has_one relations
     **/
    private static $has_one = array(
        'SocialImage'     => 'Image'
    );

    /**
     * @since version 1.0
     *
     * @static array $many_many Has many extra Meta tags
     **/
    private static $many_many = array(
        'HeadTags'        => 'SEOHeadTag'
    );

    /**
     * @since version 1.0
     *
     * @static array $defaults Sitemap defaults
     **/
    private static $defaults = array(
        'Priority'        => 0.50,
        'ChangeFrequency' => 'weekly'
    );
    
    /**
     * Adds our SEO Meta fields to the page field list
     *
     * @since version 1.0
     *
     * @param string $fields The current FieldList object
     *
     * @return FieldList Return the FieldList object
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        $fields->addFieldsToTab('Root.SEO', array(
            HeaderField::create($this->title),
            $this->preview(),
            TextField::create('MetaTitle'),
            TextareaField::create('MetaDescription'),
            HeaderField::create('Indexing'),
            TextField::create('Canonical'),
            DropdownField::create('Robots', 'Robots', $this->IndexRules()),
            HeaderField::create('Sitemap'),
            NumericField::create('Priority'),
            DropdownField::create('ChangeFrequency', 'Change Frequency', $this->SitemapChangeFrequency()),
            HeaderField::create('Social'),
            CheckboxField::create('HideSocial','Hide Social Meta?'),
            DropdownField::create('OGtype', 'Open Graph Type', $this->OGtype()),
            DropdownField::create('OGlocale', 'Open Graph Locale', $this->OGlocale()),
            DropdownField::create('TwitterCard', 'Twitter Card', $this->TwitterCardTypes()),
            $this->SharingImage(),
            $this->OtherHeadTags()
        ));

        return $fields;
    }
    
    /**
     * Render the Meta preview template for the CMS SEO panel
     *
     * @since version 1.0
     *
     * @return string
     **/
    private function preview()
    {
        return LiteralField::create('Preview', Controller::curr()->renderWith('MetaPreview'));
    }
    
    /**
     * Creates our social sharing upload field
     *
     * @since version 1.0
     *
     * @return UploadField Return the Social image UploadField object
     **/
    private function SharingImage()
    {
        $image = new UploadField('SocialImage');

        $image->getValidator()->setAllowedMaxFileSize($this->getMaxImageSize());
        $image->setFolderName($this->image_folder);
        $image->setAllowedFileCategories('image');

        return $image;
    }
    
    /**
     * Creates our social sharing upload field
     *
     * @since version 1.0
     *
     * @return GridField Return the Social image GridField object
     **/
    private function OtherHeadTags()
    {
        $grid = new GridField(
            'HeadTags',
            'Other Meta Tags',
            $this->owner->HeadTags(),
            GridFieldConfig_RelationEditor::create()
        );

        // remove the autocompleter so existing tags cannot be attached to the current page
        $grid->getConfig()->removeComponentsByType('GridFieldAddExistingAutocompleter');

        return $grid;
    }
    
    /**
     * Returns the maximum upload image size
     *
     * @since version 1.0
     *
     * @return Int Returns the maximum image size in KB
     **/
    private function getMaxImageSize()
    {
        return $this->image_size * 1024;
    }
    
    /**
     * Returns an array of sitemap change frequencies used in a sitemap.xml file
     *
     * @since version 1.0
     *
     * @return array Returns an array of change frequency values
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
     * Returns an array of robots crawling rules used in a robots Meta tag
     *
     * @since version 1.0
     *
     * @return array Returns an array of robots index rule values
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
     * Return an array of Facebook Open Graph locales
     *
     * @since version 1.0
     *
     * @return array Returns an array of open graph locale values
     **/
    private function OGlocale()
    {
        return array(
            'en_GB' => 'English - United Kingdom',
            'en_US' => 'English - United States',
            'da_DK' => 'Danish - Denmark',
            'nl_NL' => 'Dutch - Netherlands',
            'fr_FR' => 'French - France',
            'de_DE' => 'German - Germany',
            'el_GR' => 'Greek - Greece',
            'hu_HU' => 'Hungarian - Hungary',
            'is_IS' => 'Icelandic - Iceland',
            'id_ID' => 'Indonesian - Indonesia',
            'it_IT' => 'Italian - Italy',
            'ja_JP' => 'Japanese - Japan',
            'ko_KR' => 'Korean - Korea',
            'lv_LV' => 'Latvian - Latvia',
            'lt_LT' => 'Lithuanian - Lithuania',
            'mk_MK' => 'Macedonian - Macedonia',
            'no_NO' => 'Norwegian - Norway',
            'fa_IN' => 'Persian - India',
            'fa_IR' => 'Persian - Iran',
            'pl_PL' => 'Polish - Poland',
            'pt_PT' => 'Portuguese - Portugal',
            'ro_RO' => 'Romanian - Romania',
            'ru_RU' => 'Russian - Russia',
            'sk_SK' => 'Slovak - Slovakia',
            'sl_SI' => 'Slovenian - Slovenia',
            'es_ES' => 'Spanish - Spain',
            'sv_SE' => 'Swedish - Sweden',
            'tr_TR' => 'Turkish - Turkey',
            'uk_UA' => 'Ukrainian - Ukraine',
            'vi_VN' => 'Vietnamese - Vietnam'
        );
    }
    
    /**
     * Return an array of Facebook Open Graph Types
     *
     * @since version 1.0
     *
     * @return array Returns an array of open graph type values
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
     * Returns an array of Twitter card types
     *
     * @since version 1.0
     *
     * @return array Returns an array of twitter card type values
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


