<?php

/**
 * Add fields to the Page object and changes the CMS GridField
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


    private static $default_sort = 'Priority DESC';

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
     * @since version 1.2
     *
     * @static 
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
     * @static 
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
        $fields->addFieldsToTab('Root.SEO', array(
            HeaderField::create($this->title),
            $this->preview(),
            TextField::create('MetaTitle'),
            TextareaField::create('MetaDescription'),
            HeaderField::create('Indexing'),
            TextField::create('Canonical'),
            DropdownField::create('Robots', 'Robots', SEOFieldValues::IndexRules()),
            HeaderField::create('Sitemap'),
            NumericField::create('Priority'),
            DropdownField::create('ChangeFrequency', 'Change Frequency', SEOFieldValues::SitemapChangeFrequency()),
            HeaderField::create('Social'),
            CheckboxField::create('HideSocial','Hide Social Meta?'),
            DropdownField::create('OGtype', 'Open Graph Type', SEOFieldValues::OGtype()),
            DropdownField::create('OGlocale', 'Open Graph Locale', SEOFieldValues::OGlocale()),
            DropdownField::create('TwitterCard', 'Twitter Card', SEOFieldValues::TwitterCardTypes()),
            $this->SharingImage(),
            $this->OtherHeadTags()
        ));

        return $fields;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function updateSummaryFields(&$fields)
    {
        if(Controller::curr() instanceof SEOAdmin){
            Config::inst()->update($this->owner->class, 'summary_fields', self::$summary_fields);

            $fields = Config::inst()->get($this->owner->class, 'summary_fields');
        }
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
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function GridCreated()
    {
        return date('dS M Y', strtotime($this->owner->Created));
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function GridTitle()
    {
        $meta = HTMLText::create();
        $meta->setValue('<span class="seo-pagename">'.$this->owner->Title.'</span>');
        return $meta;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function GridMetaTitle()
    {
        return $this->getGridLight($this->owner->MetaTitle, 40, 70);
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function GridMetaDescription()
    {
        return $this->getGridLight($this->owner->MetaDescription, 120, 170);
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function GridSocial()
    {
        $color = $this->owner->HideSocial != 1 ? 'true' : 'false';

        $meta = HTMLText::create();
        $meta->setValue('<span class="seo-light '.$color.'"></span>');
        return $meta;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    private function getGridLight($text, $min, $max)
    {
        $characters = strlen($text);

        $color = $characters > $min && $characters < $max ? 'true' : 'warning';

        if(trim($text) == '' || $characters > $max) $color = 'false';

        $meta = HTMLText::create();
        $meta->setValue('<span class="seo-light '.$color.'"></span>');
        return $meta;
    }
}