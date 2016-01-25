<?php

class SitemapHTML {

	private $html;

	public function get()
	{
		$pages = SiteTree::get()->filter(array(
			'ClassName:not' => 'ErrorPage',
			'ParentID'      => 0
		))->Sort('Sort','ASC');

		$this->getPages($pages);
		
		return $this->html;
	}

	private function getPages($pages)
	{
		$this->html .= '<ul>';

		foreach($pages as $page):
			$this->html .= '<li><a href="'.$page->URLSegment.'">'.$page->Title.'</a>';

			$children = SiteTree::get()->filter(array(
				'ParentID' => $page->ID
			))->Sort('ID','ASC');

			$this->getPages($children);

			$this->html .= '</li>';
		endforeach;

		$this->html .= '</ul>';

	}
}