<?php
/**
 * SEOmeta
 * Generate our meta tags
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOmeta {

	private $pageID;

	private $meta;
	
	function __construct(int $pageID)
	{
		$this->pageID = $pageID;

		$this->meta = SEO::get()->byId($this->pageID);
	}

	public function tags()
	{

	}
}