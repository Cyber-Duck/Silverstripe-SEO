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
		return array(
			'ID'              => 'ID',
			'Created'         => 'Created',
			'Title'           => 'Title',
			'Robots'          => 'Robots',
			'Priority'        => 'Priority',
			'ChangeFrequency' => 'ChangeFrequency'
		);
	}

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

	public function getList()
	{
		$list = parent::getList();

		$params = $this->getRequest()->requestVar('q'); // use this to access search parameters

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

		$list = Versioned::get_by_stage('Page', 'Live')->filter($filters)->sort('Priority', 'DESC');

		return $list;
	}
}