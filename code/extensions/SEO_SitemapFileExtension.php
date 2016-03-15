<?php

/**
 * Extension for images which adds compatibility for XML image sitemaps
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_SitemapFileExtension extends DataExtension {

    /**
     * @since version 1.2
     *
     * @config array $db Add extra fields to the image object
     **/
    private static $db = array(
        'Caption' => 'Varchar(512)'
    );

    /**
     * @since version 1.2
     *
     * @config array $summary_fields Use better custom summary fields
     **/
    private static $summary_fields = array(
        'Thumbnail' => '',
        'Name'      => 'Name',
        'Created'   => 'Created',
        'Title'     => 'Title',
        'Caption'   => 'Caption'
    );

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        if(Controller::curr() instanceof SEO_ModelAdmin){
            $fields->removeByName('Name');
            $fields->removeByName('ParentID');
            $fields->removeByName('OwnerID');

            $fields->addFieldToTab('Root.Main', TextField::create('Caption'));
        }
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
        if(Controller::curr() instanceof SEO_ModelAdmin){
            Config::inst()->update($this->owner->class, 'summary_fields', self::$summary_fields);

            $fields = Config::inst()->get($this->owner->class, 'summary_fields');
        }
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function getThumbnail()
    {
        return $this->owner->CroppedImage(20,20);
    }
}