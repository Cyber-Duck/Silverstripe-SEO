<?php

namespace CyberDuck\SEO\Admin;

use Page;
use CyberDuck\SEO\Forms\GridField\PublishPageRequest;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\Versioned\Versioned;

/**
 * SEOAdmin
 *
 * Class which creates the SEO CMS section for SEO management across pages
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOAdmin extends ModelAdmin
{
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
                ->getComponentByType(GridFieldDetailForm::class)
                ->setItemRequestClass(PublishPageRequest::class);
        }
        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $grid->getConfig()->removeComponentsByType(GridFieldAddNewButton::class);
        $grid->getConfig()->removeComponentsByType(GridFieldDeleteAction::class);
        $grid->getConfig()->removeComponentsByType(GridFieldEditButton::class);

        $list = $this
            ->getList()
            ->filter($this->getFilters())
            ->sort('Priority', 'DESC');

        $grid->setList($list);
        $grid->setModelClass($class->ClassName);

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
        $list = ArrayList::create();

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
        if(!Controller::curr() instanceof SEOAdmin) return parent::getSearchContext();

        Config::modify()->set($this->modelClass, 'searchable_fields', $this->getSearchableFields());

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
            'ChangeFrequency' => 'ChangeFrequency' // @todo ENUM
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
                'field'  => TextField::class,
                'filter' => PartialMatchFilter::class
            ],
            'URLSegment' => [
                'title'  => 'URL segment:',
                'field'  => TextField::class,
                'filter' => PartialMatchFilter::class
            ],
            'Robots' => [
                'title'  => 'Robots:',
                'field'  => DropdownField::class,
                'filter' => ExactMatchFilter::class
            ],
            'ChangeFrequency' => [
                'title'  => 'Change frequency:',
                'field'  => DropdownField::class,
                'filter' => ExactMatchFilter::class
            ],
            'HideSocial' => [
                'title'  => 'Social Meta:',
                'field'  => DropdownField::class,
                'filter' => ExactMatchFilter::class
            ]
        ];
    }
}