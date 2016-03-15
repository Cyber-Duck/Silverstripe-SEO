<?php

/**
 * Creates an Other Meta Tags GridField within the CMS for an object / page
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_HeadTag extends DataObject {

    /**
     * @since version 1.0
     *
     * @static array $db Simple name, value, and type fields for a Meta tag
     **/
    private static $db = array(
        'Name'      => 'Varchar(512)',
        'Value'     => 'Varchar(512)',
        'Type'      => 'Varchar(512)'
    );

    /**
     * @since version 1.0
     *
     * @static array $summary_fields Show all fields in the Grid field
     **/
    private static $summary_fields = array(
        'Name'      => 'Name',
        'Value'     => 'Value',
        'Type'      => 'Type'
    );

    /**
     * @since version 1.0
     *
     * @static string $default_sort Sort tags by name by default
     **/
    private static $default_sort = 'Name';

    /**
     * @since version 1.0
     *
     * @static string $singular_name Singular English name
     **/
    private static $singular_name = 'Meta Tag';

    /**
     * @since version 1.0
     *
     * @static string $plural_name Plural English name
     **/
    private static $plural_name = 'Meta Tags';
    
    /**
     * Add the Meta tag CMS fields
     *
     * @since version 1.0
     *
     * @return FieldList Return the current page fields
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
     * @since version 1.0
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