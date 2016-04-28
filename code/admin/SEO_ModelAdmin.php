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
     * @since version 1.0.0
     *
     * @config boolean $showImportForm Hide the import form for SEO admin
     **/
    public $showImportForm = false;

    /**
     * @since version 1.0.0
     *
     * @config string $menu_title The main menu title
     **/
    private static $menu_title = 'SEO';

    /**
     * @since version 1.0.0
     *
     * @config string $url_segment The CMS SEO admin URL segment
     **/
    private static $url_segment = 'seo-admin';

    /**
     * @since version 1.0.0
     *
     * @config string $menu_icon The main menu icon
     **/
    private static $menu_icon = 'seo/images/menu-icons/16x16/seo.png';

    /**
     * @since version 1.0.0
     *
     * @config int $menu_priority Menu priority
     **/
    private static $menu_priority = 101;

    /**
     * @since version 1.0.0
     *
     * @config int $page_length Set to 50 to easily examine a large set of pages
     **/
    private static $page_length = 50;

    /**
     * @since version 1.0.0
     *
     * @config array $managed_models Default none as they are set later
     **/
    private static $managed_models = array();

    /**
     * @since version 1.0.0
     *
     * @config string $model_importers Disable model imports in SEO admin
     **/
    private static $model_importers = null;

    /**
     * Update the managed models array with objects listed in the YML config files
     *
     * @since version 1.0.0
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
     * @since version 1.0.0
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
     * @since version 1.0.0
     *
     * @return FieldList
     **/
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $class = new $this->modelClass;
        if($class instanceof Page) {
            $form = $this->setRequestHandler($form);
        }
        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');

        $list = $this
            ->getObjectList()
            ->filter($this->getFilters())
            ->sort('Priority', 'DESC');

        $grid->setList($list);

        $this->extend('updateEditForm',  $grid);
        
        return $form;
    }

    private function setRequestHandler($form)
    {
        $form
            ->Fields()
            ->fieldByName($this->sanitiseClassName($this->modelClass))
            ->getConfig()
            ->getComponentByType('GridFieldDetailForm')
            ->setItemRequestClass('SEO_PublishPageRequest');

        return $form;
    }

    /**
     * Using getList you can filter the grid by any passed GET param filters or
     * you can filter by model class
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getObjectList()
    {
        $class = new $this->modelClass;

        if($class instanceof Page) {
            return $this->getVersionedPages();
        }
        return parent::getList();
    }

    private function getFilters()
    {
        $request = $this->getRequest()->requestVar('q');

        $filters = array();

        if(isset($request['Robots']) && $request['Robots']){
            $filters['Robots'] = $request['Robots'];
        }
        if(isset($request['ChangeFrequency']) && $request['ChangeFrequency']){
            $filters['ChangeFrequency'] = $request['ChangeFrequency'];
        }
        if(isset($request['HideSocial']) && $request['HideSocial']){
            $filters['HideSocial'] = $request['HideSocial'];
        }
        if($this->modelClass !== "Page"){
            $filters['ClassName'] = $this->modelClass;
        }
        return $filters;
    }

    private function getVersionedPages()
    {
        $list = new ArrayList();

        $stage = Versioned::get_by_stage($this->modelClass, 'Stage');
        foreach($stage as $stage) $list->push($stage);

        $live = Versioned::get_by_stage($this->modelClass, 'Live');
        foreach($live as $live) $list->push($live);

        $list->removeDuplicates('ID');

        return $list;
    }

    /**
     * Using this method we can populate the SEO grid search filters with various
     * SEO options
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getSearchContext()
    {
        if(!Controller::curr() instanceof SEO_ModelAdmin) return parent::getSearchContext();

        Config::inst()->update($this->modelClass, 'searchable_fields', SEO_FieldValues::SearchableFields());

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
}