<?php

class MetaPreviewField extends LiteralField
{
	private static $meta_title = 'Your Meta Title Here - What Your Page is About';

  	private static $meta_description = 'This is a preview of your Meta description and how it will look in the Search Engine Results Page. Always try to keep it short and make it fit into the space provided.';

	public function __construct(DataObject $seo)
	{
		$this->seo = $seo;

		Requirements::javascript(Director::absoluteBaseURL().'seo/javascript/serp.js');

		parent::__construct('SEO_MetaPreviewField', $this->getSerpContent());
	}

	private function getSerpContent()
	{
		return Controller::curr()->customise([
			'DefaultTitle' => $this->getSerpTitle(),
            'DefaultPath'  => $this->getSERPLink(),
            'DefaultDescription' => $this->getSerpDescription()
		])->renderWith('MetaPreview');
	}

    private function getSerpTitle()
    {
    	if($this->seo->MetaTitle) {
    		return $this->seo->MetaTitle;
    	}
    	return self::$meta_title;
    }

    private function getSERPLink()
    {
        return Director::absoluteBaseURL().substr($this->seo->link(), 1);
    }

    private function getSerpDescription()
    {
    	if($this->seo->MetaDescription) {
    		return $this->seo->MetaDescription;
    	}
    	return self::$meta_description;
    }
}