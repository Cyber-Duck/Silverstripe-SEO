<?php

class SEOSitemap {

	private $url;

	private $html;

	public function setURL($url)
	{
		$this->url = $url;
	}

	public function html()
	{
		return $this->html;
	}

	public function get()
	{
		$pages = SiteTree::get()->filter(array(
			'ClassName:not' => 'ErrorPage',
			'ParentID'      => 0
		))->Sort('Sort','ASC');

		$this->getPages($pages);
	}

	private function getPages($pages)
	{
		$this->html .= '<ul>';

		foreach($pages as $page):
			$this->html .= '<li><a href="'.$this->URL.$page->URLSegment.'">'.$page->Title.'</a>';

			$children = SiteTree::get()->filter(array(
				'ParentID' => $page->ID
			))->Sort('ID','ASC');

			$this->getPages($children);

			$this->html .= '</li>';
		endforeach;

		$this->html .= '</ul>';

	}
}