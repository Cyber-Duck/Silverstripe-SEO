<?php
/**
 * SEO_Extension
 *
 * Core extension used to attach SEO fields to a DataObject
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Extension extends DataExtension
{
    /**
     * Our page fields
     *
     * @since version 1.0.0
     *
     * @config array $db 
     **/
    private static $db = [
        'Title'           => 'Varchar(512)',
        'MetaTitle'       => 'Varchar(512)',
        'MetaDescription' => 'Varchar(512)',
        'Canonical'       => 'Varchar(512)',
        'Robots'          => 'Varchar(100)',
        'Priority'        => 'Decimal(3,2)',
        'ChangeFrequency' => 'Varchar(20)',
        'SitemapHide'     => 'Boolean',
        'HideSocial'      => 'Boolean',
        'OGtype'          => 'Varchar(100)',
        'OGlocale'        => 'Varchar(10)',
        'TwitterCard'     => 'Varchar(100)'
    ];

    /**
     * Social image and other has_one relations
     *
     * @since version 1.0.0
     *
     * @config array $has_one 
     **/
    private static $has_one = [
        'SocialImage' => 'Image'
    ];

    /**
     * Has many extra Meta tags
     *
     * @since version 1.0.0
     *
     * @config array $many_many 
     **/
    private static $many_many = [
        'HeadTags'      => 'SEO_HeadTag',
        'SitemapImages' => 'Image'
    ];

    /**
     * Sitemap defaults
     *
     * @since version 1.0.0
     *
     * @config array $defaults 
     **/
    private static $defaults = [
        'Robots'          => 'index,follow',
        'Priority'        => 0.50,
        'ChangeFrequency' => 'weekly'
    ];

    /**
     * Adds our SEO Meta fields to the page field list. The tab is divided into
     * logical sections controlling various aspects of page SEO.
     *
     * @since version 1.0.0
     *
     * @param FieldList $fields The fields object
     *
     * @return FieldList
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        $fields->removeByName('HeadTags');
        $fields->removeByName('SitemapImages');

        if(!$this->owner instanceof Page) {
            $fields->addFieldToTab('Root.Page', HeaderField::create(false, 'Page', 2));
            $fields->addFieldToTab('Root.Page', TextField::create('Title','Page name'));
        }
        // SERP
        $fields->addFieldToTab('Root.PageSEO', MetaPreviewField::create($this->owner));
        $fields->addFieldToTab('Root.PageSEO', TextField::create('MetaTitle'));
        $fields->addFieldToTab('Root.PageSEO', TextareaField::create('MetaDescription'));

        // Indexing
        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Indexing', 2));
        $fields->addFieldToTab('Root.PageSEO', TextField::create('Canonical'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('Robots', 'Robots')
            ->setSource($this->getRobotsIndexingRules())
            ->setEmptyString('- please select - '));

        // Social Sharing
        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Social Sharing', 2));
        $fields->addFieldToTab('Root.PageSEO', CheckboxField::create('HideSocial', 'Hide Social Meta?'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('OGtype', 'Open Graph Type')
            ->setSource($this->getOGtypes())
            ->setEmptyString('- please select - '));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('OGlocale', 'Open Graph Locale')
            ->setSource($this->getOGlocales())
            ->setEmptyString('- please select - '));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('TwitterCard', 'Twitter Card')
            ->setSource($this->getTwitterCardTypes())
            ->setEmptyString('- please select - '));
        $image = UploadField::create('SocialImage');
        $image->getValidator()->setAllowedMaxFileSize(Config::inst()->get('SocialImage')->image_size * 1024);
        $image->setFolderName(Config::inst()->get('SocialImage')->image_folder);
        $image->setAllowedFileCategories('image');
        $fields->addFieldToTab('Root.PageSEO', $image);

        // Extra Meta Tags
        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Extra Meta Tags', 2));
        $grid = GridField::create('HeadTags', 'Other Meta Tags', $this->owner->HeadTags(), GridFieldConfig_RelationEditor::create());
        $grid->getConfig()->removeComponentsByType('GridFieldAddExistingAutocompleter');
        $fields->addFieldToTab('Root.PageSEO', $grid);

        // Sitemap
        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Sitemap', 2));
        $fields->addFieldToTab('Root.PageSEO', CheckboxField::create('SitemapHide', 'Hide in sitemap? (XML and HTML)'));
        $fields->addFieldToTab('Root.PageSEO', NumericField::create('Priority'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('ChangeFrequency', 'Change Frequency')
            ->setSource($this->getSitemapChangeFrequency())
            ->setEmptyString('- please select - '));
        $grid = GridField::create('SitemapImages', 'Sitemap Images', $this->owner->SitemapImages(), GridFieldConfig_RelationEditor::create());
        $grid->getConfig()
            ->removeComponentsByType('GridFieldAddNewButton')
            ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            ->addComponent(new SEO_SitemapImageAutocompleter('before'));
        $fields->addFieldToTab('Root.PageSEO', $grid);

        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Version', 3));
        $fields->addFieldToTab('Root.PageSEO', LiteralField::create(false, Config::inst()->get('SEO')->version));

        return $fields;
    }

    /**
     * Returns an array of sitemap change frequencies used in a sitemap.xml file
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getSitemapChangeFrequency()
    {
        return [
            'always'  => 'Always',
            'hourly'  => 'Hourly',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
            'never'   => 'Never'
        ];
    }

    /**
     * Returns an array of robots crawling rules used in a robots Meta tag
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getRobotsIndexingRules()
    {
        return [
            'index,follow'     => 'index,follow',
            'noindex,nofollow' => 'noindex,nofollow',
            'noindex,follow'   => 'noindex,follow',
            'index,nofollow'   => 'index,nofollow'
        ];
    }

    /**
     * Return an array of Facebook Open Graph Types used in Meta tags
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getOGtypes()
    {
        return [
            'website' => 'Website',
            'article' => 'Article',
            'book'    => 'Book',
            'profile' => 'Profile',
            'music'   => 'Music',
            'video'   => 'Video'
        ];
    }

    /**
     * Return an array of Facebook Open Graph locales used in Meta tags
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getOGlocales()
    {
        return [
            'en_GB' => 'English - United Kingdom',
            'en_US' => 'English - United States',
            'da_DK' => 'Danish - Denmark',
            'hr_HR' => 'Croatian - Croatia',
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
        ];
    }

    /**
     * Returns an array of Twitter card types used in Meta tags
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getTwitterCardTypes()
    {
        return [
            'summary'             => 'Summary',
            'summary_large_image' => 'Summary Large Image',
            'photo'               => 'Photo',
            'gallery'             => 'Gallery',
            'app'                 => 'App',
            'product'             => 'Product'
        ];
    }
    
    /**
     * Get the current page Meta title
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageMetaTitle()
    {
        if($this->owner->MetaTitle) {
            return $this->owner->MetaTitle;
        }
        if(class_exists('BlogPost')) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Blog()->DefaultPostMetaTitle == 1) {
                    return $this->owner->Title;
                }
            }
        }
    }
    
    /**
     * Get the current page Meta description
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageMetaDescription()
    {
        if($this->owner->MetaDescripion) {
            return $this->owner->MetaDescripion;
        }
        if(class_exists('BlogPost')) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Blog()->DefaultPostMetaDescription == 1) {
                    return $this->owner->Summary;
                }
            }
        }
    }
    
    /**
     * Get the current page canonical tag URL
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageCanonical()
    {
        if($this->owner->Canonical) {
            return $this->owner->Canonical;
        }
        return $this->getPageURL();
    }
    
    /**
     * Get the current page Meta robots rules
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageRobots()
    {
        if($this->owner->Robots) {
            return $this->owner->Robots;
        }
        return 'index,follow';
    }
    
    /**
     * Get the current page URL // todo getAbsoluteURL()?
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageURL()
    {
        return Director::absoluteBaseURL().substr($this->owner->link(), 1);
    }

    /**
     * Get the current page Meta open graph type
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageOgType()
    {
        if($this->owner->OGtype) {
            return $this->owner->OGtype;
        }
        return 'website';
    }

    /**
     * Get the current page Meta open graph locale
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageOgLocale()
    {
        if($this->owner->OGlocale) {
            return $this->owner->OGlocale;
        }
        return str_replace('-', '_', Controller::curr()->ContentLocale());
    }

    /**
     * Get the current page Meta Twitter card type
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageTwitterCard()
    {
        if($this->owner->TwitterCard) {
            return $this->owner->TwitterCard;
        }
        return 'summary';
    }

    /**
     * Get the current page Meta social sharing image
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageSocialImage()
    {
        if($this->owner->SocialImage()) {
            return $this->owner->SocialImage();
        }
        if(class_exists('BlogPost')) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->FeaturedImage()) {
                    return $this->owner->FeaturedImage();
                }
            }
        }
    }

    /**
     * Get the current site Facebook app ID
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getSiteFacebookAppID()
    {
        return SiteConfig::current_site_config()->FacebookAppID;
    }

    /**
     * Get the current site open graph site name
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getSiteOgSiteName()
    {
        if(SiteConfig::current_site_config()->OGSiteName) {
            return SiteConfig::current_site_config()->OGSiteName;
        }
        return SiteConfig::current_site_config()->Title;
    }

    /**
     * Get the current site Twitter handle
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getSiteTwitterHandle()
    {
        return '@'.SiteConfig::current_site_config()->TwitterHandle;
    }

    /**
     * Get the current site Twitter creator handle
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getSiteCreatorTwitterHandle()
    {
        return '@'.SiteConfig::current_site_config()->CreatorTwitterHandle;
    }

    /**
     * Get the SilverStripe page generator tag value
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageGenerator()
    {
        $generator = trim(Config::inst()->get('SiteTree', 'meta_generator'));

        if(!empty($generator)) return Convert::raw2att($generator);
    }

    /**
     * Get the current page Meta charset value
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPageCharset()
    {
        return Config::inst()->get('ContentNegotiator', 'encoding');
    }

    /**
     * Returns true when the current page is a CMS preview
     *
     * @since version 2.0.0
     *
     * @return boolean
     **/
    public function isCMSPreviewPage()
    {
        return Permission::check('CMS_ACCESS_CMSMain')
            && in_array('CMSPreviewable', class_implements($this))
            && !$this instanceof ErrorPage
            && $this->owner->ID > 0;
    }

    /**
     * Get the current page ID
     *
     * @since version 2.0.0
     *
     * @return int
     **/
    public function getCMSPageID()
    {
        return $this->owner->ID;
    }

    /**
     * Get the current page edit link
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getCMSPageEditLink()
    {
        return Controller::curr()->CMSEditLink();
    }

    private $pagination; // todo

    /**
     * Sets a Paginated list object which the prev and next rel tags will be 
     * calculated off. This method validates the current $_GET param used for 
     * pagination and will return a 404 response if the $_GET var is outside
     * of the expected range. e.g start=100 but only 99 items in the list
     *
     * @since version 2.0.0
     *
     * @param PaginatedList $list   Paginated list object
     * @param array         $params Array of $_GET params to allow in the URL // todo
     *
     * @return string|404 response
     **/
    public function setPaginationTags(PaginatedList $list, $params = [])
    {
        $start = (int) $this->owner->request->getPaginationGetVar();

        if($list->CurrentPage() > $list->TotalPages()){
            return $this->owner->httpError(404)
        }
        if($start % $list->getPageLength() !== 0){
            return $this->owner->httpError(404)
        }
        if(!preg_match('/^[0-9]+$/', $start)){
            return $this->owner->httpError(404)
        }
        $this->pagination = $list;
    }

    /**
     * Get the current page prev pagination link
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPaginationPrevTag()
    {
        if($this->pagination) {
            if($this->pagination->TotalPages() > 1) {
                if($this->pagination->CurrentPage() === 2) {
                    return $this->getPageURL();
                } else {
                    $start = $this->pagination->getPageStart() - $this->pagination->getPageLength();

                    return $this->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
                }
            }
        }
    }

    /**
     * Get the current page next pagination link
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPaginationNextTag()
    {
        if($this->pagination) {
            if($this->pagination->TotalPages() > 1 && $this->pagination->NotLastPage()) {
                $start = $this->pagination->getPageStart() + $this->pagination->getPageLength();

                return $this->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
            }
        }
    }
}