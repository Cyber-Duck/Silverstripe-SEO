<?php

namespace CyberDuck\SEO\Model;

use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataObject;

/**
 * SeoHeadTag
 *
 * Object representing a non standard Meta tag which can be added to the current page through
 * the extra meta tags GridField
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SeoHeadTag extends DataObject
{
    /**
     * Table name
     *
     * @since version 4.0.0
     *
     * @config string $table_name
     **/
    private static $table_name = 'SeoHeadTag';

    /**
     * Simple name, value, and type fields for a Meta tag
     *
     * @since version 1.0.0
     *
     * @config array $db 
     **/
    private static $db = [
        'Title' => 'Varchar(255)',
        'Value' => 'Text',
        'Type'  => 'Text'
    ];

    /**
     * Simple name, value, and type fields for a Meta tag
     *
     * @since version 2.0.0
     *
     * @config array $db 
     **/
    private static $has_one = [
        'Page' => DataObject::class
    ];

    /**
     * Show all fields in the Grid field
     *
     * @since version 1.0.0
     *
     * @config array $summary_fields
     **/
    private static $summary_fields = [
        'Title' => 'Title',
        'Value' => 'Value',
        'Type'  => 'Type'
    ];

    /**
     * Sort tags by name by default
     *
     * @since version 1.0.0
     *
     * @config string $default_sort 
     **/
    private static $default_sort = 'Title';

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
     * @return FieldList
     **/
    public function getCMSFields() 
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', HeaderField::create(false, 'Head Tag', 2));

        $fields->addFieldToTab('Root.Main', DropdownField::create('Type', 'Tag type', $this->getTagTypes()));
        $fields->addFieldToTab('Root.Main', TextField::create('Title'));
        $fields->addFieldToTab('Root.Main', TextField::create('Value'));
        $fields->addFieldToTab('Root.Main', HiddenField::create('PageID'));

        return $fields;
    }
    
    /**
     * Return an array of Meta tag types
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    private function getTagTypes()
    {
        return [
            'name'     => '<meta name="name" content="value">',
            'link'     => '<link rel="name" href="value">',
            'property' => '<meta property="name" content="value">'
        ];
    }
}
