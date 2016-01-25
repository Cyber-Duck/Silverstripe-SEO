<?php

class HeadTags {

	private $controller;

	private $html;

	function __construct()
	{
		$this->controller = Controller::curr();
	}

	public function get()
	{
		if($this->controller->MetaTitle):
			$title = $this->controller->MetaTitle;

			$this->getTitleTag($title);

			if(!$this->controller->HideSocial):
				$this->getMetaTag('twitter:title',$title);
				$this->getPropertyTag('og:title',$title);
			endif;
		endif;

		if($this->controller->MetaDescription):
			$description = $this->controller->MetaDescription;

			$this->getMetaTag('description',$description);

			if(!$this->controller->HideSocial):
				$this->getMetaTag('twitter:description',$description);
				$this->getPropertyTag('og:description',$description);
			endif;
		endif;

		if($this->controller->Canonical):
			$canonical = $this->controller->Canonical;

			$this->getLinkTag('canonical',$canonical);
			$this->getPropertyTag('og:url',$canonical);
		endif;

		if($this->controller->Robots):
			$robots = $this->controller->Robots;

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