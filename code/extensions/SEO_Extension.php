<?php

/**
 * Core extension used to transform an object into an SEO object
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Extension extends DataExtension {

    /**
     * @since version 1.0
     *
     * @config array $db Our page fields
     **/
    private static $db = array(
        'Title'           => 'Varchar(512)',
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
     * @config array $has_one Social image and other has_one relations
     **/
    private static $has_one = array(
        'SocialImage'     => 'Image'
    );

    /**
     * @since version 1.0
     *
     * @config array $many_many Has many extra Meta tags
     **/
    private static $many_many = array(
        'HeadTags'        => 'SEO_HeadTag',
        'SitemapImages'   => 'File'
    );


    private static $default_sort = 'Priority DESC';

    /**
     * @since version 1.0
     *
     * @config array $defaults Sitemap defaults
     **/
    private static $defaults = array(
        'Priority'        => 0.50,
        'ChangeFrequency' => 'weekly'
    );

    /**
     * @since version 1.2
     *
     * @config array $summary_fields Show important formatted grid values
     **/
    private static $summary_fields = array(
        'GridCreated'          => 'Created',
        'GridTitle'            => 'Title',
        'Robots'               => 'Robots',
        'Priority'             => 'Priority',
        'ChangeFrequency'      => 'Change Freq',
        'GridMetaTitle'        => 'T',
        'GridMetaDescription'  => 'D',
        'GridSocial'           => 'S'
    );

    /**
     * @since version 1.2
     *
     * @config array $searchable_fields Allow searching against important SEO fields
     **/
    private static $searchable_fields = array(
        'Title' => array(
            'title'  => 'Title:',
            'field'  => 'TextField',
            'filter' => 'PartialMatchFilter'
        ),
        'URLSegment' => array(
            'title'  => 'URL segment:',
            'field'  => 'TextField',
            'filter' => 'PartialMatchFilter'
        ),
        'Robots' => array(
            'title'  => 'Robots:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter'
        ),
        'ChangeFrequency' => array(
            'title'  => 'Change frequency:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter'
        ),
        'HideSocial' => array(
            'title'  => 'Social Meta:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter'
        )
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
        $fields->addFieldToTab('Root.SEO', HeaderField::create(Config::inst()->get('SEO_Extension','title')));
        $fields->addFieldToTab('Root.SEO', $this->preview());
        $fields->addFieldToTab('Root.SEO', TextField::create('MetaTitle'));
        $fields->addFieldToTab('Root.SEO', TextareaField::create('MetaDescription'));

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Indexing'));
        $fields->addFieldToTab('Root.SEO', TextField::create('Canonical'));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('Robots', 'Robots', SEO_FieldValues::IndexRules()));

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Social Meta'));
        $fields->addFieldToTab('Root.SEO', CheckboxField::create('HideSocial','Hide Social Meta?'));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('OGtype', 'Open Graph Type', SEO_FieldValues::OGtype()));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('OGlocale', 'Open Graph Locale', SEO_FieldValues::OGlocale()));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('TwitterCard', 'Twitter Card', SEO_FieldValues::TwitterCardTypes()));
        $fields->addFieldToTab('Root.SEO', $this->SharingImage());

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Other Meta'));
        $fields->addFieldToTab('Root.SEO', $this->OtherHeadTags());

        $fields->addFieldToTab('Root.SEO', HeaderField::create('Sitemap'));
        $fields->addFieldToTab('Root.SEO', NumericField::create('Priority'));
        $fields->addFieldToTab('Root.SEO', DropdownField::create('ChangeFrequency', 'Change Frequency', SEO_FieldValues::SitemapChangeFrequency()));
        $fields->addFieldToTab('Root.SEO', $this->SitemapImagesGrid());

        return $fields;
    }

    /**
     * Change the grid summary field structure is currently in SEO admin
     * 
     * @param array $fields The current summary fields
     *
     * @since version 1.2
     *
     * @return void
     **/
    public function updateSummaryFields(&$fields)
    {
        if(Controller::curr() instanceof SEO_ModelAdmin){
            Config::inst()->update($this->owner->class, 'summary_fields', self::$summary_fields);

            $fields = Config::inst()->get($this->owner->class, 'summary_fields');
        }
    }
    
    /**
     * Render the Meta preview template for the CMS SEO panel
     *
     * @since version 1.0
     *
     * @return LiteralField
     **/
    private function preview()
    {
        $title = Config::inst()->get('SEO_ModelAdmin','meta_title');
        $description = Config::inst()->get('SEO_ModelAdmin','meta_description');

        $preview = Controller::curr()->customise(array(
            'DefaultTitle' => $title['default'],
            'DefaultDescription' => $description['default']
        ))->renderWith('MetaPreview');

        return LiteralField::create('Preview', $preview);
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

        $image->getValidator()->setAllowedMaxFileSize($this->getMaxSocialImageSize());
        $image->setFolderName(Config::inst()->get('SEO_Extension','social_image_folder'));
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
     * Creates our social sharing upload field
     *
     * @since version 1.0
     *
     * @return GridField Return the Social image GridField object
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

        return $grid;
    }
    
    /**
     * Returns the maximum upload image size
     *
     * @since version 1.0
     *
     * @return int Returns the maximum image size in KB
     **/
    private function getMaxSocialImageSize()
    {
        return Config::inst()->get('SEO_Extension','social_image_size') * 1024;
    }

    /**
     * Return a formatted current date
     *
     * @since version 1.2
     *
     * @return string
     **/
    public function GridCreated()
    {
        return date('dS M Y', strtotime($this->owner->Created));
    }

    /**
     * Return a styled span containing a title value
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    public function GridTitle()
    {
        return $this->getGridSpan('<span class="seo-pagename">'.$this->owner->Title.'</span>');
    }

    /**
     * Return a span styled reflecting Meta title length validation
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    public function GridMetaTitle()
    {
        $meta = Config::inst()->get('SEO_ModelAdmin','meta_title');

        return $this->getGridLight($this->owner->MetaTitle, $meta['min'], $meta['max']);
    }

    /**
     * Return a span styled reflecting Meta description length validation
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    public function GridMetaDescription()
    {
        $meta = Config::inst()->get('SEO_ModelAdmin','meta_description');

        return $this->getGridLight($this->owner->MetaDescription, $meta['min'], $meta['max']);
    }

    /**
     * Return a span styled reflecting the current status of social meta
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    public function GridSocial()
    {
        $class = $this->owner->HideSocial != 1 ? 'true' : 'false';

        return $this->getGridSpan('<span class="seo-light '.$class.'"></span>');
    }

    /**
     * Check the length of a string and generates a span styled reflecting Meta status
     * 
     * @param string $text The text to check
     * @param int    $min  The minimum string length
     * @param int    $max  The maximum string length
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    private function getGridLight($text, $min, $max)
    {
        $characters = strlen($text);

        $class = $characters > $min && $characters < $max ? 'true' : 'warning';

        if(trim($text) == '' || $characters > $max) $class = 'false';

        return $this->getGridSpan('<span class="seo-light '.$class.'"></span>');
    }

    /**
     * Return a HTMLText object for use within a grid field 
     * 
     * @param string $span The HTML span
     *
     * @since version 1.2
     *
     * @return HTMLText
     **/
    private function getGridSpan($span)
    {
        $html = HTMLText::create();
        $html->setValue($span);
        return $html;
    }
}