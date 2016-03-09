<?php

class SEOAdmin extends ModelAdmin {

	public $showImportForm = false;

	private static $menu_title = 'SEO';

	private static $url_segment = 'seo-admin';

	private static $menu_icon = 'seo/images/menu-icons/16x16/seo.png';

    private static $menu_priority = 80;

	private static $page_length = 50;

	private static $managed_models = array('Page');

	private static $model_importers = null;

	public function init()
	{
		parent::init();
	}

	public function getExportFields()
	{
		return array();
	}

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));

        $grid->getConfig()->removeComponentsByType('GridFieldAddNewButton');

        $list = $grid->getList();

        $grid->setList($list->sort('Priority', 'DESC'));
        
        return $form;
    }
}