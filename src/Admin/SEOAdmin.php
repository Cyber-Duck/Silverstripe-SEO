<?php

namespace CyberDuck\SEO\Admin;

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