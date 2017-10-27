<?php

namespace CyberDuck\SEO\Model\Extension;

/**
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 *
 * SeoExtension
 *
 * Core extension to convert a DataObject into a page with detailed SEO configuration.
 * The user should add a URLSegment & Title field to their DataObject as well as a Link() method.
 **/
class SeoExtension extends SeoPageExtension
{
    /**
     * Page Meta fields to add to DataObjects with this extension. 
     *
     * @since version 4.0.0
     *
     * @config array $db 
     **/
    private static $db = [
        'MetaDescription' => 'Varchar(512)'
    ];
}