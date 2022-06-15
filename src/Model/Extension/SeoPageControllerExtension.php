<?php

namespace CyberDuck\SEO\Model\Extension;

use Page;
use Exception;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * SeoPageControllerExtension
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 */
class SeoPageControllerExtension extends Extension
{
    /**
     * A DataObject instance to pull the current page SEO properties from
     *
     * @since version 2.0.0
     *
     * @var DataObject $seo
     **/
    private $seo;

    /**
     * Set the model to use for the current page Meta
     *
     * @since version 2.0.0
     *
     * @param DataObject $object
     *
     * @return void
     **/
    public function setSeoObject(DataObject $object)
    {
        if($object instanceof Page || is_subclass_of($object, Page::class)) {
            if(!$object->hasExtension(SeoPageExtension::class)) {
                throw new Exception('setSeoObject must be passed a Page with the SeoPageExtension applied');
            }
        } else {
            if(!$object->hasExtension(SeoExtension::class)) {
                throw new Exception('setSeoObject must be passed a DataObject with the SeoExtension applied');
            }
        }

        $this->seo = $object;
    }

    /**
     * Return the head tags to use for the current page
     *
     * @since version 2.0.0
     *
     * @return ViewableData
     **/
    public function getPageMetaTags()
    {
        $meta = $this->owner->customise([
            'SeoPageObject' => ($this->seo ? $this->seo : $this->owner)
        ])->renderWith('HeadTags')->RAW();

        $meta = implode("\n", array_filter(explode("\n", $meta)));
        return DBField::create_field('HTMLText', $meta);
    }

    /**
     * Gets the Defaut Page Social Image, this is defined in the CMS
     * 
     * @return null|Image
     */
    public function getDefaultPageSocialImage()
    {
        $siteConfig = SiteConfig::get()->first();

        if ($siteConfig) {
            $image =  Image::get()->byID($siteConfig->DefaultSocialImageID);

            if ($image) {
                return $image;
            }
        }

        return null;
    }
}
