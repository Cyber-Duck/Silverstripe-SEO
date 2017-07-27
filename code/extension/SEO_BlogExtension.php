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
class SEO_BlogExtension extends DataExtension
{
    /**
     * Our page fields
     *
     * @since version 1.0.0
     *
     * @config array $db 
     **/
    private static $db = [
        'DefaultPostMetaTitle'       => 'Boolean',
        'DefaultPostMetaDescription' => 'Boolean',
        'UseFeaturedAsSocialImage'   => 'Boolean'
    ];

    /**
     * Add Blog and Blog Post configuration fields to the page
     *
     * @since version 1.0.0
     *
     * @param FieldList $fields The fields object
     *
     * @return FieldList
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        $fields->addFieldToTab('Root.PostSEO', HeaderField::create(false, 'Blog Post SEO', 2));
        $fields->addFieldToTab('Root.PostSEO', CheckboxField::create('DefaultPostMetaTitle', 'Default Meta title')
        	->setDescription('Use page Title when no Meta title set for Blog Post'));
        $fields->addFieldToTab('Root.PostSEO', CheckboxField::create('DefaultPostMetaDescription', 'Default Meta description')
        	->setDescription('Use page summary when no Meta descripion set for Blog Post'));
        $fields->addFieldToTab('Root.PostSEO', CheckboxField::create('UseFeaturedAsSocialImage', 'Use featured image as social image')
            ->setDescription('Use page featured image as social image'));

        return $fields;
    }
}