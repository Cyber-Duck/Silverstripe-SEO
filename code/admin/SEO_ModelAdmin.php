<?php

/**
 * SEO Model Admin class which creates the SEO CMS section for SEO management across pages
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_ModelAdmin extends ModelAdmin {

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
    private static $menu_priority = 101;

    /**
     * @since version 1.2
     *
     * @config int $page_length Set to 50 to easily examine a large set of pages
     **/
    private static $page_length = 50;

    /**
     * @since version 1.2
     *
     * @config array $managed_models Default none as they are set later
     **/
    private static $managed_models = array();

    /**
     * @since version 1.2
     *
     * @config string $model_importers Disable model imports in SEO admin
     **/
    private static $model_importers = null;

    /**
     * Update the managed models array with objects listed in the YML config files
     *
     * @since version 1.2
     *
     * @return void
     **/
    public function init()
    {
        $models = Config::inst()->get($this->class, 'models');

        Config::inst()->update($this->class, 'managed_models', $models);

        parent::init();
    }

    /**
     * Key SEO fields are contained within the CSV export
     *
     * @since version 1.2
     *
     * @return array
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
     * The SEO admin area is for managing page SEO, not for page creation. Some grid
     * field components are removed from the SEO admin by default.
     *
     * @param mixed $id
     * @param mixed $fields
     * 
     * @since version 1.2
     *
     * @return FieldList
     **/
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $form
            ->Fields()
            ->fieldByName($this->sanitiseClassName($this->modelClass))
            ->getConfig()
            ->getComponentByType('GridFieldDetailForm')
            ->setItemRequestClass('SEO_PublishPageRequest');

        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));

        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');

        $this->extend('updateEditForm',  $grid);
        
        return $form;
    }

    /**
     * Using this method we can populate the SEO grid search filters with various
     * SEO options
     *
     * @since version 1.2
     *
     * @return object
     **/
    public function getSearchContext()
    {
        $context = parent::getSearchContext();

        $context->getFields()->fieldByName('q[Robots]')
            ->setEmptyString('- select -')
            ->setSource(SEO_FieldValues::IndexRules());

        $context->getFields()->fieldByName('q[ChangeFrequency]')
            ->setEmptyString('- select -')
            ->setSource(SEO_FieldValues::SitemapChangeFrequency());

        $context->getFields()->fieldByName('q[HideSocial]')
            ->setTitle('Social Meta hidden:')
            ->setEmptyString('- select -')
            ->setSource(SEO_FieldValues::YesNo());

        return $context;
    }

    /**
     * Using getList you can filter the grid by any passed GET param filters or
     * you can filter by model class
     *
     * @since version 1.2
     *
     * @return object
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