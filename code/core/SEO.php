<?php

class SEO {

	public static function SitemapHTML()
	{
		$sitemap = new SitemapHTML();

		return $sitemap->get();
	}
}