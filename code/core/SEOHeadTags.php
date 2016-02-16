<?php

class SEOHeadTags {

	private $model;

	private $html;

	public function setPage(object $page)
	{
		$this->model = $page;

		return $this;
	}

	public function html()
	{
		return $this->html;
	}

	public function get()
	{
		if(method_exists($this->model,'HeadTags')){
			foreach($this->model->HeadTags() as $tag){

				if($tag->Type == 'name'){
					$this->getMetaTag($tag->Name,$tag->Value);
					break;
				}
				if($tag->Type == 'link'){
					$this->getLinkTag($tag->Name,$tag->Value);
					break;
				}
				if($tag->Type == 'property'){
					$this->getPropertyTag($tag->Name,$tag->Value);
					break;
				}
			}
		}
		return $this;
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