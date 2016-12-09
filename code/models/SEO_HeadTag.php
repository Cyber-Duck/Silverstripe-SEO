<?php
/**
 * SEO_HeadTag
 *
 * Creates an Other Meta Tags GridField within the CMS for an object / page
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_HeadTag extends DataObject
{
    /**
     * Simple name, value, and type fields for a Meta tag
     *
     * @since version 1.0.0
     *
     * @config array $db 
     **/
    private static $db = array(
        'Name'      => 'Varchar(512)',
        'Value'     => 'Varchar(512)',
        'Type'      => 'Varchar(512)'
    );

    /**
     * Show all fields in the Grid field
     *
     * @since version 1.0.0
     *
     * @config array $summary_fields
     **/
    private static $summary_fields = array(
        'Name'      => 'Name',
        'Value'     => 'Value',
        'Type'      => 'Type'
    );

    /**
     * Sort tags by name by default
     *
     * @since version 1.0.0
     *
     * @config string $default_sort 
     **/
    private static $default_sort = 'Name';

    /**
     * Singular English name
     *
     * @since version 1.0.0
     *
     * @config string $singular_name
     **/
    private static $singular_name = 'Meta Tag';

    /**
     * Plural English name
     *
     * @since version 1.0.0
     *
     * @config string $plural_name 
     **/
    private static $plural_name = 'Meta Tags';
    
    /**
     * Add the Meta tag CMS fields
     *
     * @since version 1.0.0
     *
     * @return object Return the current page fields
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
     * Return an array of Meta tag type values
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of name value Meta tags
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