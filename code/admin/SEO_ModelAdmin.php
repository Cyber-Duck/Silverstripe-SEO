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
    private static $managed_models = ['Page'];

    /**
     * Disable model imports in SEO admin
     *
     * @since version 1.0.0
     *
     * @config string $model_importers 
     **/
    private static $model_importers = null;

    /**
     * The SEO admin area is for managing page SEO, not for page creation. Some grid
     * field components are removed from the SEO admin by default.
     * 
     * @since version 1.0.0
     *
     * @param mixed $id
     * @param mixed $fields
     *
     * @return FieldList
     **/
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $class = new $this->modelClass;
        if($class instanceof Page) {
            $form
                ->Fields()
                ->fieldByName($this->sanitiseClassName($this->modelClass))
                ->getConfig()
                ->getComponentByType('GridFieldDetailForm')
                ->setItemRequestClass('SEO_PublishPageRequest');
        }
        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');
        $grid->getConfig()->removeComponentsByType('GridFieldDeleteAction');
        $grid->getConfig()->removeComponentsByType('GridFieldEditButton');

        $list = $this
            ->getList()
            ->filter($this->getFilters())
            ->sort('Priority', 'DESC');

        $grid->setList($list);

        $this->extend('updateEditForm',  $grid);
        
        return $form;
    }

    /**
     * Get list of CMS grid pages
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getList()
    {
        $class = new $this->modelClass;

        if($class instanceof Page) {
            return $this->getVersionedPages();
        }
        return parent::getList();
    }

    /**
     * Get list of CMS grid filters
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    private function getFilters()
    {
        $request = $this->getRequest()->requestVar('q');

        $filters = [];

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

    /**
     * Get list of CMS grid versioned pages
     *
     * @since version 1.0.0
     *
     * @return object
     **/
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
     * Set the CMS grid search context
     *
     * @since version 1.0.0
     *
     * @return object
     **/
    public function getSearchContext()
    {
        if(!Controller::curr() instanceof SEO_ModelAdmin) return parent::getSearchContext();

        Config::inst()->update($this->modelClass, 'searchable_fields', $this->getSearchableFields());

        $context = parent::getSearchContext();
        $model = $this->modelClass;
        $model = $model::create();

        $context->getFields()->fieldByName('q[Robots]')
            ->setEmptyString('- select -')
            ->setSource($model->getRobotsIndexingRules());

        $context->getFields()->fieldByName('q[ChangeFrequency]')
            ->setEmptyString('- select -')
            ->setSource($model->getSitemapChangeFrequency());

        $context->getFields()->fieldByName('q[HideSocial]')
            ->setTitle('Social Meta hidden:')
            ->setEmptyString('- select -')
            ->setSource([
                '1' => 'Yes',
                '0' => 'No'
            ]);
                
        return $context;
    }

    /**
     * CSV export fields
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    public function getExportFields()
    {
        return [
            'ID'              => 'ID',
            'Created'         => 'Created',
            'Title'           => 'Title',
            'Robots'          => 'Robots',
            'Priority'        => 'Priority',
            'ChangeFrequency' => 'ChangeFrequency'
        ];
    }

    /**
     * Returns an array of searchable fields used in the SEO Admin section of the CMS
     *
     * @since version 1.0.2
     *
     * @return array Returns an array of SEO Admin searchable fields
     **/
    private function getSearchableFields()
    {
        return [
            'Title' => [
                'title'  => 'Title:',
                'field'  => 'TextField',
                'filter' => 'PartialMatchFilter'
            ],
            'URLSegment' => [
                'title'  => 'URL segment:',
                'field'  => 'TextField',
                'filter' => 'PartialMatchFilter'
            ],
            'Robots' => [
                'title'  => 'Robots:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ],
            'ChangeFrequency' => [
                'title'  => 'Change frequency:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ],
            'HideSocial' => [
                'title'  => 'Social Meta:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ]
        ];
    }
}