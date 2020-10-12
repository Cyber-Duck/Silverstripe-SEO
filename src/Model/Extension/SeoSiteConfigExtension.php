<?php

namespace CyberDuck\SEO\Model\Extension;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * SeoSiteConfigExtension
 *
 * Updates the CMS site config with custom fields for SEO and Social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SeoSiteConfigExtension extends DataExtension
{
    /**
     * Array of extra CMS settings fields
     *
     * @since version 1.0.6
     *
     * @config array $db 
     **/
    private static $db = [
        'OGSiteName'             => 'Text',
        'TwitterHandle'          => 'Text',
        'CreatorTwitterHandle'   => 'Text',
        'FacebookAppID'          => 'Text',
        'UseTitleAsMetaTitle'    => 'Boolean',
        'SchemaOrganisationName' => 'Text'
    ];

    /**
     * has_one relations
     *
     * @since version 1.0.0
     *
     * @config array $has_one 
     **/
    private static $has_one = [
        'SchemaOrganisationImage' => Image::class,
        'DefaultSocialImage' => Image::class
    ];

    /**
     * ownership relations
     *
     * @since version 1.0.0
     *
     * @config array $has_one 
     **/
    private static $owns = [
        'SchemaOrganisationImage',
        'DefaultSocialImage'
    ];
    
    /**
     * Adds extra fields for social config across networks
     *
     * @since version 1.0.6
     *
     * @param FieldList $fields The current FieldList object
     *
     * @return FieldList
     **/
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.SEO', HeaderField::create(false, 'Meta'));
        $fields->addFieldToTab('Root.SEO', CheckboxField::create('UseTitleAsMetaTitle', 'Default Meta title to page title when Meta title empty?'));

        $fields->addFieldToTab('Root.SEO', HeaderField::create(false, 'Social Meta'));
        $fields->addFieldToTab('Root.SEO', TextField::create('OGSiteName', 'Open Graph Site Name'));
        $fields->addFieldToTab('Root.SEO', TextField::create('TwitterHandle', 'Twitter handle (no @)'));
        $fields->addFieldToTab('Root.SEO', TextField::create('CreatorTwitterHandle', 'Twitter creator handle (no @)'));
        $fields->addFieldToTab('Root.SEO', TextField::create('FacebookAppID', 'Facebook APP ID'));
        $uploader = UploadField::create('DefaultSocialImage', 'Default Social Image')
            ->setFolderName(Config::inst()->get('SocialImage', 'image_folder'))
            ->setAllowedFileCategories('image', 'image/supported')
            ->setDescription('Minimum size - 1200w x 630h pixels. Used in og:image and twitter:image meta when social image not set on page / model');
        $fields->addFieldToTab('Root.SEO', $uploader);

        $fields->addFieldToTab('Root.SEO', HeaderField::create(false, 'Schema'));
        $fields->addFieldToTab('Root.SEO', HeaderField::create(false, 'Organisation (used in blog post schema)', 4));
        $fields->addFieldToTab('Root.SEO', TextField::create('SchemaOrganisationName', 'Name'));
        $uploader = UploadField::create('SchemaOrganisationImage', 'Image')
            ->setFolderName(Config::inst()->get('SocialImage', 'image_folder'))
            ->setAllowedFileCategories('image', 'image/supported');
        $fields->addFieldToTab('Root.SEO', $uploader);
        
        return $fields;
    }
}
