<?php

namespace CyberDuck\SEO\Admin;

use Page;
use Exception;
use CyberDuck\SEO\Model\Extension\SeoExtension;
use CyberDuck\SEO\Model\Extension\SeoPageExtension;
use SilverStripe\Admin\ModelAdmin;

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
        if($singleton instanceof Page || is_subclass_of($object, Page::class)) {
            if(!$singleton->hasExtension(SeoPageExtension::class)) {
                throw new Exception(sprintf('%s must have the SeoPageExtension applied to work in SEO Admin', $this->modelClass));
            }
        } else {
            if(!$singleton->hasExtension(SeoExtension::class)) {
                throw new Exception(sprintf('%s must have the SeoExtension applied to work in SEO Admin', $this->modelClass));
            }
        }
        $this->extend('updateEditForm',  $form);

        return $form;
    }

    public function getExportFields()
    {
        $fields = [
            'Created'             => 'Created',
            'ID'                  => 'ID',
            'ClassName'           => 'Class Name',
            'Title'               => 'Title',
            'URLSegment'          => 'URL Segment',
            'MetaTitle'           => 'Meta Title',
            'MetaDescription'     => 'Meta Description',
            'Canonical'           => 'Canonical',
            'Robots'              => 'Robots',
            'Priority'            => 'Priority',
            'ChangeFrequency'     => 'Change Frequency',
            'SitemapHide'         => 'Sitemap Hide',
            'HideSocial'          => 'Hide Social',
            'OGtype'              => 'OG Type',
            'OGlocale'            => 'OG Locale',
            'TwitterCard'         => 'Twitter Card',
            'SocialImage.URL'     => 'Social Image',
            'HeadTags.Count'      => 'Head Tags',
            'SitemapImages.Count' => 'Sitemap Images'
        ];
        $this->extend('updateExportFields',  $fields);
        
        return $fields;
    }
}