<?php

class SitemapHTML {

	private $html;

	private $indent = 10;

	private $currentIndent;

	public function sitemap()
	{
		$this->html .= '<ul>';

		$pages = DataObject::get_one('Page',"URLSegment = 'home'")->getChildren();

		$this->html .= '<li>'.Director::absoluteURL();

		$this->getPageChildren($pages);

		$this->html .= '</li>';

		$this->html .= '</ul>';
	}

	private function getPageChildren($pages)
	{
		foreach($pages as $page):
			$this->html .= '<ul>';
			$this->html .= '<li>'.$page->URLSegment;

			$children = DataObject::get_one('Page',"ParentID = '".$page->ID."'");

			if($children):
				$this->getPageChildren($children);
			endif;

			$this->html .= '</li>';
			$this->html .= '</ul>';
		endforeach;
	}
}