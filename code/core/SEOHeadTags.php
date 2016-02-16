<?php

class SEOHeadTags {

	private $url;

	private $model;

	private $html;

	function __construct($object = null)
	{
		if($object === null){
			$this->model = Controller::curr();
		} else {
			$this->model = $object;
		}
	}

	public function setURL($url)
	{
		$this->url = $url;
	}

	public function setPage(object $page)
	{
		$this->model = $page;
	}

	public function html()
	{
		return $this->html;
	}

	public function get()
	{
		if($this->model->MetaTitle):
			$title = $this->model->MetaTitle;

			$this->getTitleTag($title);

			if(!$this->model->HideSocial):
				$this->getMetaTag('twitter:title',$title);
				$this->getPropertyTag('og:title',$title);
			endif;
		endif;

		if($this->model->MetaDescription):
			$description = $this->model->MetaDescription;

			$this->getMetaTag('description',$description);

			if(!$this->model->HideSocial):
				$this->getMetaTag('twitter:description',$description);
				$this->getPropertyTag('og:description',$description);
			endif;
		endif;

		if($this->model->Canonical):
			$canonical = $this->model->Canonical;

			$this->getLinkTag('canonical',$canonical);
			$this->getPropertyTag('og:url',$canonical);
		else:
			$this->getLinkTag('canonical',$this->url);
		endif;

		if($this->model->Robots):
			$robots = $this->model->Robots;

			$this->getMetaTag('robots',$robots);
		endif;

		return $this->html;
	}

	private function getTitleTag($title)
	{
		$this->html .= '<title>'.$title.'</title>'.PHP_EOL;
	}

	private function getMetaTag($name,$value)
	{
		$this->html .= '<meta name="'.$name.'" content="'.$value.'">'.PHP_EOL;
	}

	private function getLinkTag($name,$value)
	{
		$this->html .= '<link rel="'.$name.'" href="'.$value.'">'.PHP_EOL;
	}

	private function getPropertyTag($name,$value)
	{
		$this->html .= '<meta property="'.$name.'" content="'.$value.'">'.PHP_EOL;
	}
}