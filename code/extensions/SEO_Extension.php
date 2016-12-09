<?php
/**
 * SEO_Extension
 *
 * Core extension used to transform an object into an SEO object
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
        'ChangeFrequency' => 'Varchar(100)',
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
        'SocialImage'     => 'Image'
    ];

    /**
     * Has many extra Meta tags
     *
     * @since version 1.0.0
     *
     * @config array $many_many 
     **/
    private static $many_many = [
        'HeadTags'        => 'SEO_HeadTag',
        'SitemapImages'   => 'File'
    ];

    /**
     * Sitemap defaults
     *
     * @since version 1.0.0
     *
     * @config array $defaults 
     **/
    private static $defaults = [
        'Priority'        => 0.50,
        'ChangeFrequency' => 'weekly'
    ];
    
    /**
     * Adds our SEO Meta fields to the page field list
     *
     * @since version 1.0.0
     *
     * @param string $fields The current FieldList object
     *
     * @return object Return the FieldList object
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        $fields->removeByName('HeadTags');
        $fields->removeByName('SitemapImages');

        if(!$this->owner instanceof Page) {
            $fields->addFieldToTab('Root.Page', HeaderField::create('Page'));
            $fields->addFieldToTab('Root.Page', TextField::create('Title','Page name'));
        }

        $fields->addFieldToTab('Root.PageSEO', $this->preview());
        $fields->addFieldToTab('Root.PageSEO', TextField::create('MetaTitle'));
        $fields->addFieldToTab('Root.PageSEO', TextareaField::create('MetaDescription'));

        $fields->addFieldToTab('Root.PageSEO', HeaderField::create(false, 'Indexing', 2));
        $fields->addFieldToTab('Root.PageSEO', TextField::create('Canonical'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('Robots', 'Robots', SEO_FieldValues::IndexRules()));
        $fields->addFieldToTab('Root.PageSEO', NumericField::create('Priority'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('ChangeFrequency', 'Change Frequency', SEO_FieldValues::SitemapChangeFrequency()));
        $fields->addFieldToTab('Root.PageSEO', CheckboxField::create('SitemapHide', 'Hide in sitemap? (XML and HTML)'));

        $fields->addFieldToTab('Root.PageSEO', HeaderField::create('Social Meta'));
        $fields->addFieldToTab('Root.PageSEO', CheckboxField::create('HideSocial','Hide Social Meta?'));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('OGtype', 'Open Graph Type', SEO_FieldValues::OGtype()));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('OGlocale', 'Open Graph Locale', SEO_FieldValues::OGlocale()));
        $fields->addFieldToTab('Root.PageSEO', DropdownField::create('TwitterCard', 'Twitter Card', SEO_FieldValues::TwitterCardTypes()));
        $fields->addFieldToTab('Root.PageSEO', $this->SharingImage());

        $fields->addFieldToTab('Root.PageSEO', HeaderField::create('Other Meta Tags'));
        $fields->addFieldToTab('Root.PageSEO', $this->OtherHeadTags());

        $fields->addFieldToTab('Root.PageSEO', HeaderField::create('Sitemap Images'));
        $fields->addFieldToTab('Root.PageSEO', $this->SitemapImagesGrid());

        $fields->addFieldToTab('Root.PageSEO', LiteralField::create(false, '<br><br>Silverstripe SEO v1.0'));

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
        if(Controller::curr() instanceof SEO_ModelAdmin) {
            Config::inst()->remove($this->owner->class, 'summary_fields');

            $class = new $this->owner->class;
            $fields = SEO_FieldValues::SummaryFields();

            if($class instanceof Page) {
                $fields = array_merge(['SEOPageStatus' => 'Status'], $fields);
            }
            Config::inst()->update($this->owner->class, 'summary_fields', $fields);

            $fields = Config::inst()->get($this->owner->class, 'summary_fields');
        }
    }

    /**
     * Get the CMS grid HTML page status icon
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getSEOPageStatus()
    {
        if($this->owner->isPublished()){
            $color = '#11C823';
            $status = 'Live';
        } else {
            $color = '#898C89';
            $status = 'Draft';
        }
        $obj= HTMLText::create();
        $obj->setValue('<span style="color:'.$color.'">'.$status.'</span>');
        return $obj;
    }
    
    /**
     * Render the Meta preview template for the CMS SEO panel
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    private function preview()
    {
        $title = Config::inst()->get('SEO_ModelAdmin','meta_title');
        $description = Config::inst()->get('SEO_ModelAdmin','meta_description');

        $preview = Controller::curr()->customise([
            'DefaultTitle' => $title['default'],
            'DefaultPath' => $this->SERPLink(),
            'DefaultDescription' => $description['default']
        ])->renderWith('MetaPreview');

        return LiteralField::create('Preview', $preview);
    }
    
    /**
     * Get the SERP link
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    private function SERPLink()
    {
        if($this->owner instanceof Page){
            return Director::absoluteBaseURL().substr($this->owner->link(),1);
        }
        return Director::absoluteBaseURL().$this->owner->URLSegment;
    }
    
    /**
     * Creates our social sharing upload field
     *
     * @since version 1.0.0
     *
     * @return object Return the Social image UploadField object
     **/
    private function SharingImage()
    {
        $image = new UploadField('SocialImage');

        $image->getValidator()->setAllowedMaxFileSize($this->getMaxSocialImageSize());
        $image->setFolderName(Config::inst()->get('SEO_Extension','social_image_folder'));
        $image->setAllowedFileCategories('image');

        return $image;
    }
    
    /**
     * Creates our social sharing upload field
     *
     * @since version 1.0.0
     *
     * @return object Return the Social image GridField object
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
     * Creates our social sharing upload field
     *
     * @since version 1.0.0
     *
     * @return object Return the Social image GridField object
     **/
    private function SitemapImagesGrid()
    {
        $grid = new GridField(
            'SitemapImages',
            'Sitemap Images',
            $this->owner->SitemapImages(),
            GridFieldConfig_RelationEditor::create()
        );

        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');
        $grid->getConfig()->removeComponentsByType('GridFieldAddExistingAutocompleter');
        $grid->getConfig()->addComponent(new SEO_SitemapImageAutocompleter('before'));

        return $grid;
    }
    
    /**
     * Returns the maximum upload image size
     *
     * @since version 1.0.0
     *
     * @return int Returns the maximum image size in KB
     **/
    private function getMaxSocialImageSize()
    {
        return Config::inst()->get('SEO_Extension','social_image_size') * 1024;
    }

    /**
     * Return a span styled reflecting Meta title length validation
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function GridMetaTitle()
    {
        $meta = Config::inst()->get('SEO_ModelAdmin','meta_title');

        return $this->getGridLight($this->owner->MetaTitle, $meta['min'], $meta['max']);
    }

    /**
     * Return a span styled reflecting Meta description length validation
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function GridMetaDescription()
    {
        $meta = Config::inst()->get('SEO_ModelAdmin','meta_description');

        return $this->getGridLight($this->owner->MetaDescription, $meta['min'], $meta['max']);
    }

    /**
     * Return a span styled reflecting the current status of social meta
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function GridSocial()
    {
        $class = $this->owner->HideSocial == 1 ? 'delete' : 'accept';

        return $this->getGridSpan('<span class="ui-button-icon-primary ui-icon btn-icon-'.$class.'"></span>');
    }

    /**
     * Check the length of a string and generates a span styled reflecting Meta status
     *
     * @since version 1.0.0
     * 
     * @param string $text The text to check
     * @param int    $min  The minimum string length
     * @param int    $max  The maximum string length
     *
     * @return object
     **/
    private function getGridLight($text, $min, $max)
    {
        $characters = strlen($text);

        if(trim($text) == ''){
            $class = "cross";
        } elseif($characters < $min || $characters > $max) {
            $class = "delete";
        } else {
            $class = "accept";
        }
        return $this->getGridSpan('<span class="ui-button-icon-primary ui-icon btn-icon-'.$class.'"></span>');
    }

    /**
     * Return a HTMLText object for use within a grid field 
     *
     * @since version 1.0.0
     * 
     * @param string $span The HTML span
     *
     * @return object
     **/
    private function getGridSpan($span)
    {
        $html = HTMLText::create();
        $html->setValue($span);
        return $html;
    }
}