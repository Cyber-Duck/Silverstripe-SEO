<?php

namespace CyberDuck\SEO\Model\Extension;

use Page;
use CyberDuck\SEO\Model\SeoHeadTag;
use CyberDuck\SEO\Forms\MetaPreviewField;
use CyberDuck\SEO\Admin\SEOAdmin;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\NumericField;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Permission;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 *
 * SeoPageExtension
 *
 * Core extension to add detailed SEO configuration.
 * Attaches by default to the Page object.
 * Maps all properties in HeadTags.ss to methods within this class.
 * e.g $PageMetaTitle => getPageMetaTitle()
 * The mapped methods handle a class property and return a value based on conditions
 * within the configuration such as returning a default value when no value is set.
 * Whether attaching the extension to existing or new page no manual setting of
 * properties in the CMS should be required if you want to go with a standard config.
 *
 * Standard config is:
 * Canonical       => The current full page URL
 * Robots          => 'index,follow'
 * Priority        => '0.5'
 * ChangeFrequency => 'weekly'
 * SitemapHide     => false
 * HideSocial      => false
 * OGtype          => 'website'
 * OGlocale        => The current website locale
 * TwitterCard     => 'summary'
 *
 * Class properties in the $db array here should not be called directly, rather
 * the class methods should be called to take advantage of SubSite detection, Blog
 * related DataObject detection and other features. You can subclass, override, or
 * use YML config to compliment this class and create your own detailed Meta strategies.
 **/
class SeoPageExtension extends DataExtension
{
    /**
     * Our page fields
     *
     * @since version 1.0.0
     *
     * @config array $db
     **/
    private static $db = [
        'MetaTitle'       => 'Varchar(512)',
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
        'SocialImage' => Image::class
    ];

    /**
     * Has many extra Meta tags
     *
     * @since version 1.0.0
     *
     * @config array $many_many
     **/
    private static $many_many = [
        'HeadTags'      => SeoHeadTag::class,
        'SitemapImages' => Image::class
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
        'ChangeFrequency' => 'weekly',
        'OGtype'          => 'website',
        'TwitterCard'     => 'summary'
    ];

    /**
     * A PaginatedList instance used for rel Meta tags
     *
     * @since version 2.0.0
     *
     * @var PaginatedList $pagination
     **/
    private $pagination;

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

        // META TAB
        // Meta
        $fields->addFieldToTab('Root.MetaTags', MetaPreviewField::create($this->owner));
        $title = TextField::create('MetaTitle');
        $description = TextareaField::create('MetaDescription');
        if(class_exists(BlogPost::class)) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Parent()->DefaultPostMetaTitle == 1) {
                    $title->setAttribute('placeholder', 'Using page title');
                }
                if($this->owner->Parent()->DefaultPostMetaDescription == 1) {
                    $description->setAttribute('placeholder', 'Using page summary');
                }
            }
        }
        $fields->addFieldToTab('Root.MetaTags', $title);
        $fields->addFieldToTab('Root.MetaTags', $description);

        // Indexing
        $fields->addFieldToTab('Root.MetaTags', HeaderField::create(false, 'Indexing', 2));
        $canonical = TextField::create('Canonical');
        if(!$this->owner->Canonical) {
            $canonical->setAttribute('placeholder', 'Using page URL');
        }
        $fields->addFieldToTab('Root.MetaTags', $canonical);
        $robots = DropdownField::create('Robots', 'Robots')
            ->setSource($this->getRobotsIndexingRules())
            ->setEmptyString('- please select - ');
        if(!$this->owner->Robots) {
            $robots->setDescription('Using default "index,follow" rule');
        }
        $fields->addFieldToTab('Root.MetaTags', $robots);

        // Social Sharing
        $fields->addFieldToTab('Root.MetaTags', HeaderField::create(false, 'Social Sharing', 2));
        $fields->addFieldToTab('Root.MetaTags', CheckboxField::create('HideSocial', 'Hide Social Meta?'));
        $og = DropdownField::create('OGtype', 'Open Graph Type')
            ->setSource($this->getOGtypes())
            ->setEmptyString('- please select - ');
        if(!$this->owner->OGtype) {
            $og->setDescription('Using default "website" type');
        }
        $fields->addFieldToTab('Root.MetaTags', $og);
        $og = DropdownField::create('OGlocale', 'Open Graph Locale')
            ->setSource($this->getOGlocales())
            ->setEmptyString('- please select - ');
        if(!$this->owner->OGlocale) {
            $locale = str_replace('-', '_', i18n::get_locale());
            $og->setDescription(sprintf('Using default locale from application "%s"', $locale));
        }
        $fields->addFieldToTab('Root.MetaTags', $og);
        $card = DropdownField::create('TwitterCard', 'Twitter Card')
            ->setSource($this->getTwitterCardTypes())
            ->setEmptyString('- please select - ');
        if(!$this->owner->TwitterCard) {
            $card->setDescription('Using default twitter card "summary"');
        }
        $fields->addFieldToTab('Root.MetaTags', $card);
        $uploader = UploadField::create('SocialImage')
            ->setFolderName(Config::inst()->get('SocialImage', 'image_folder'))
            ->setAllowedFileCategories('image', 'image/supported')
            ->setDescription(sprintf("Minimum size: %sw x %sh pixels.<br/>Maximum file size: %sMB<br/>Recommended Aspect Ratio: %s<br/>Used in og:image and twitter:image meta when social image not set on page / model",
                Config::inst()->get('CyberDuck\SEO\SocialImage', 'min_file_width'),
                Config::inst()->get('CyberDuck\SEO\SocialImage', 'min_file_height'),
                Config::inst()->get('CyberDuck\SEO\SocialImage', 'max_file_size'),
                Config::inst()->get('CyberDuck\SEO\SocialImage', 'file_aspect_ratio'),
            ));
        $uploader->getValidator()->setAllowedMaxFileSize(['*' => (int)Config::inst()->get('CyberDuck\SEO\SocialImage', 'max_file_size') * 1024 * 1024]);
    
        if(class_exists(BlogPost::class)) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Parent()->UseFeaturedAsSocialImage == 1) {
                    $uploader->setDescription('Using the page featured image');
                }
            }
        }
        $fields->addFieldToTab('Root.MetaTags', $uploader);

        // Extra Meta Tags
        $grid = GridField::create('HeadTags', 'Other Meta Tags', $this->owner->HeadTags(), GridFieldConfig_RelationEditor::create());
        $grid->getConfig()->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
        $fields->addFieldToTab('Root.MetaTags', $grid);

        // SITEMAP TAB
        // Sitemap
        $fields->addFieldToTab('Root.Sitemap', HeaderField::create(false, 'Sitemap', 2));
        $fields->addFieldToTab('Root.Sitemap', CheckboxField::create('SitemapHide', 'Hide in sitemap? (XML and HTML)'));
        $fields->addFieldToTab('Root.Sitemap', NumericField::create('Priority')->setScale(1)
            ->setDescription('0.1, 0.2, 0.3, ..., 0.9, 1.0.<br >1.0 is your highest priorty, the most important page. Often the homepage.'));
        $fields->addFieldToTab('Root.Sitemap', DropdownField::create('ChangeFrequency', 'Change Frequency')
            ->setSource($this->getSitemapChangeFrequency())
            ->setEmptyString('- please select - '));

        $uploader = UploadField::create('SitemapImages')
            ->setIsMultiUpload(true)
            ->setFolderName('SitemapImages')
            ->setAllowedFileCategories('image', 'image/supported');
        $fields->addFieldToTab('Root.Sitemap', $uploader);

        return $fields;
    }

    /**
     * Change the grid summary field structure is currently in SEO admin
     *
     * @param object $fields The current summary fields
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    public function updateSummaryFields(&$fields)
    {
        if(Controller::curr() instanceof SEOAdmin) {
            Config::inst()->remove($this->owner->class, 'summary_fields');
            Config::modify()->set($this->owner->class, 'summary_fields', $this->getSummaryFields());

            $fields = Config::inst()->get($this->owner->class, 'summary_fields');
        }
    }

    /**
     * Returns an array of summary fields used in the SEO Admin section of the CMS
     *
     * @since version 1.0.2
     *
     * @return array
     **/
    public function getSummaryFields()
    {
        return [
            'ID'              => 'ID',
            'PublishedIcon'   => 'Published',
            'Title'           => 'Title',
            'PageRobots'      => 'Robots',
            'PageOgType'      => 'OGtype',
            'PageOgLocale'    => 'OGlocale',
            'PageTwitterCard' => 'TwitterCard',
            'Priority'        => 'Priority',
            'ChangeFrequency' => 'Change Freq'
        ];
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
        if(class_exists(BlogPost::class)) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Parent()->DefaultPostMetaTitle == 1) {
                    return $this->owner->Title;
                }
            }
        }
        if(SiteConfig::current_site_config()->UseTitleAsMetaTitle) {
            return $this->owner->Title;
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
        if($this->owner->MetaDescription) {
            return $this->owner->MetaDescription;
        }
        if(class_exists(BlogPost::class)) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Parent()->DefaultPostMetaDescription == 1) {
                    return strip_tags($this->owner->Summary);
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
    public function getPageCanonical($query = null)
    {
        if($this->pagination) {
            if($this->pagination->getPageStart() > 0) {
                $query = '?'.$this->pagination->getPaginationGetVar().'='.$this->pagination->getPageStart();
            }
        }
        if($this->owner->Canonical) {
            return $this->owner->Canonical.$query;
        }
        return $this->getPageURL().$query;
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
        return Director::absoluteBaseURL().substr($this->owner->Link(), 1);
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
        return str_replace('-', '_', i18n::get_locale());
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
        if($this->owner->SocialImageID > 0) {
            return $this->owner->SocialImage();
        }
        if(class_exists(BlogPost::class)) {
            if($this->owner instanceof BlogPost) {
                if($this->owner->Parent()->UseFeaturedAsSocialImage == true) {
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

    /**
     * Get the current page published status icon
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPublishedIcon()
    {
        if($this->owner instanceof Page) {
            $status = $this->owner->isPublished() ? 'accept' : 'delete';
        } else {
            $status = 'accept';
        }
        return DBField::create_field('HTMLText', sprintf('<span class="ui-button-icon-primary ui-icon btn-icon-%s"></span>', $status));
    }

    /**
     * Get the LastEdited object property as an ISO foramtted date for XML sitemap
     *
     * @since version 4.0.0
     *
     * @return string
     **/
    public function getSitemapDate()
    {
        return date('c', strtotime($this->owner->LastEdited));
    }

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
    public function setPaginationTags(PaginatedList $list, $params = []) // @todo allowed
    {
        $controller = Controller::curr();
        if($controller->getRequest()->getVar($list->getPaginationGetVar()) !== NULL) {
            if((int) $list->getPageStart() === 0) {
                return $controller->httpError(404);
            }
            if($list->CurrentPage() > $list->TotalPages()){
                return $controller->httpError(404);
            }
            if($list->getPageStart() % $list->getPageLength() !== 0){
                return $controller->httpError(404);
            }
            if(!preg_match('/^[0-9]+$/', $list->getPageStart())){
                return $controller->httpError(404);
            }
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
            if($this->pagination->TotalPages() > 1 && $this->pagination->NotFirstPage()) {
                if((int) $this->pagination->CurrentPage() === 2) {
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
