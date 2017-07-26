<?php
/**
 * SEO_ModelAdmin
 *
 * Class which creates the SEO CMS section for SEO management across pages
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_ModelAdmin extends ModelAdmin
{
    /**
     * Hide the import form for SEO admin
     *
     * @since version 1.0.0
     *
     * @config boolean $showImportForm
     **/
    public $showImportForm = false;

    /**
     * The main menu title
     *
     * @since version 1.0.0
     *
     * @config string $menu_title 
     **/
    private static $menu_title = 'SEO';

    /**
     * The CMS SEO admin URL segment
     *
     * @since version 1.0.0
     *
     * @config string $url_segment
     **/
    private static $url_segment = 'seo-admin';

    /**
     * The main menu icon
     *
     * @since version 1.0.0
     *
     * @config string $menu_icon 
     **/
    private static $menu_icon = 'seo/assets/img/menu-icons/16x16/seo.png';

    /**
     * Menu priority
     *
     * @since version 1.0.0
     *
     * @config int $menu_priority 
     **/
    private static $menu_priority = 101;

    /**
     * Set to 50 to easily examine a large set of pages
     *
     * @since version 1.0.0
     *
     * @config int $page_length 
     **/
    private static $page_length = 50;

    /**
     * Default none as they are set later
     *
     * @since version 1.0.0
     *
     * @config array $managed_models 
     **/
    private static $managed_models = [];

    /**
     * Disable model imports in SEO admin
     *
     * @since version 1.0.0
     *
     * @config string $model_importers 
     **/
    private static $model_importers = null;
}