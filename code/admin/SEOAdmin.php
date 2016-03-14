<?php

/**
 * Build the CMS SEO admin panel
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOAdmin extends ModelAdmin {

    /**
     * @since version 1.2
     *
     * @config boolean $showImportForm Hide the import form for SEO admin
     **/
    public $showImportForm = false;

    /**
     * @since version 1.2
     *
     * @config string $menu_title The main menu title
     **/
    private static $menu_title = 'SEO';

    /**
     * @since version 1.2
     *
     * @config string $url_segment The CMS SEO admin URL segment
     **/
    private static $url_segment = 'seo-admin';

    /**
     * @since version 1.2
     *
     * @config string $menu_icon The main menu icon
     **/
    private static $menu_icon = 'seo/images/menu-icons/16x16/seo.png';

    /**
     * @since version 1.2
     *
     * @config int $menu_priority Menu priority
     **/
    private static $menu_priority = 80;

    /**
     * @since version 1.2
     *
     * @config int $page_length Set to 50 to easily examine a large set of pages
     **/
    private static $page_length = 50;

    /**
     * @since version 1.2
     *
     * @config string $managed_models Default Page and ErrorPage
     **/
    private static $managed_models = array();

    /**
     * @since version 1.2
     *
     * @config string $model_importers Disable model imports in SEO admin
     **/
    private static $model_importers = null;

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function init()
    {
        $models = Config::inst()->get($this->class, 'models');

        Config::inst()->update($this->class, 'managed_models', $models);

        parent::init();
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function getExportFields()
    {
        return array(
            'ID'              => 'ID',
            'Created'         => 'Created',
            'Title'           => 'Title',
            'Robots'          => 'Robots',
            'Priority'        => 'Priority',
            'ChangeFrequency' => 'ChangeFrequency'
        );
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $form
            ->Fields()
            ->fieldByName($this->sanitiseClassName($this->modelClass))
            ->getConfig()
            ->getComponentByType('GridFieldDetailForm')
            ->setItemRequestClass('SEOPublishPageRequest');

        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));

        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');

        $this->extend('updateEditForm',  $grid);
        
        return $form;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function getSearchContext()
    {
        $context = parent::getSearchContext();

        $context->getFields()->fieldByName('q[Robots]')
            ->setEmptyString('- select -')
            ->setSource(SEOFieldValues::IndexRules());

        $context->getFields()->fieldByName('q[ChangeFrequency]')
            ->setEmptyString('- select -')
            ->setSource(SEOFieldValues::SitemapChangeFrequency());

        $context->getFields()->fieldByName('q[HideSocial]')
            ->setTitle('Social Meta hidden:')
            ->setEmptyString('- select -')
            ->setSource(SEOFieldValues::YesNo());

        return $context;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @return 
     **/
    public function getList()
    {
        $list = parent::getList();

        $params = $this->getRequest()->requestVar('q');

        $filters = array();

        if(isset($params['Robots']) && $params['Robots']){
            $filters['Robots'] = $params['Robots'];
        }

        if(isset($params['ChangeFrequency']) && $params['ChangeFrequency']){
            $filters['ChangeFrequency'] = $params['ChangeFrequency'];
        }

        if(isset($params['HideSocial']) && $params['HideSocial']){
            $filters['HideSocial'] = $params['HideSocial'];
        }
        if($this->modelClass !== "Page"){
            $filters['ClassName'] = $this->modelClass;
        }

        $list = Page::get()->filter($filters)->sort('Priority', 'DESC');

        return $list;
    }
}