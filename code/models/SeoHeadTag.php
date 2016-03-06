<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOHeadTag extends DataObject {

    /**
     * @static array $db The admin SEO panel heading
     **/
    private static $db = array(
        'Name'      => 'Varchar(512)',
        'Value'     => 'Varchar(512)',
        'Type'      => 'Varchar(512)'
    );

    /**
     * @static array $summary_fields Show all fields in the Grid field
     **/
    private static $summary_fields = array(
        'Name'      => 'Name',
        'Value'     => 'Value',
        'Type'      => 'Type'
    );

    /**
     * @static string $default_sort Sort tags by name by default
     **/
    private static $default_sort = 'Name';

    /**
     * @static string $singular_name Singular English name
     **/
    private static $singular_name = 'Meta Tag';

    /**
     * @static string $plural_name Plural English name
     **/
    private static $plural_name = 'Meta Tags';
    
    /**
     * Add the Head tag object properties
     *
     * @since version 1.0
     *
     * @return object
     **/
    public function getCMSFields() 
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Main');

        $fields->addFieldsToTab('Root.SEO', array(
            HeaderField::create('Meta Tag'),
            DropdownField::create('Type','Tag type',$this->tagTypes()),
            TextField::create('Name'),
            TextField::create('Value'),
            HiddenField::create('PageID')
        ));

        return $fields;
    }
    
    /**
     * Return an array of Meta tag types
     *
     * @since version 1.0
     *
     * @return array
     **/
    private function tagTypes()
    {
        return array(
            'name'     => '<meta name="name" content="value">',
            'link'     => '<link rel="name" href="value">',
            'property' => '<meta property="name" content="value">'
        );
    }
}