<?php

namespace CyberDuck\SEO\Admin;

use Page;
use Exception;
use CyberDuck\SEO\Model\Extension\SeoExtension;
use CyberDuck\SEO\Model\Extension\SeoPageExtension;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Versioned\VersionedGridFieldItemRequest;

class SEOAdmin extends ModelAdmin
{
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        
        $list = $this->getList()->sort('Priority', 'DESC');

        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $grid->setList($list);
        $grid->setModelClass($this->ClassName);

        $singleton = singleton($this->modelClass);
        if($singleton instanceof Page) {
            if(!$singleton->hasExtension(SeoPageExtension::class)) {
                throw new Exception(sprintf('%s must have the SeoPageExtension applied to work in SEO Admin', $this->modelClass));
            }
        } else {
            if(!$singleton->hasExtension(SeoExtension::class)) {
                throw new Exception(sprintf('%s must have the SeoExtension applied to work in SEO Admin', $this->modelClass));
            }
        }
        if(singleton($this->modelClass)->hasExtension(Versioned::class)) {
            $grid
                ->getConfig()
                ->removeComponentsByType(GridFieldDeleteAction::class)
                ->getComponentByType(GridFieldDetailForm::class)
                ->setItemRequestClass(VersionedGridFieldItemRequest::class);
        }
        $this->extend('updateEditForm',  $grid);

        return $form;
    }
}