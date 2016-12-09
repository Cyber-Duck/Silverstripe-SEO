<?php
/**
 * SEO_SitemapImageExtension
 *
 * Extension for images which adds compatibility for XML image sitemaps
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_SitemapImageExtension extends DataExtension
{
    /**
     * Use better custom summary fields
     *
     * @since version 1.0.0
     *
     * @config array $summary_fields 
     **/
    private static $summary_fields = array(
        'Thumbnail' => '',
        'Name'      => 'Name',
        'Created'   => 'Created',
        'Title'     => 'Title'
    );

    /**
     * Add extra fields to a File object if in SEO Admin
     *
     * @since version 1.0.0
     *
     * @param object $fields The current FieldList object
     *
     * @return object
     **/
    public function updateCMSFields(FieldList $fields) 
    {
        if(Controller::curr() instanceof SEO_ModelAdmin){
            $fields->removeByName('Name');
            $fields->removeByName('ParentID');
            $fields->removeByName('OwnerID');
        }
        return $fields;
    }

    /**
     * Change the class summary fields when in SEO Admin
     *
     * @since version 1.0.0
     *
     * @param array $fields The current summary fields
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
     * Add an image preview to the grid field
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getThumbnail()
    {
        return $this->owner->CroppedImage(20,20);
    }
}