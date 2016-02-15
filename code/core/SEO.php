<?php

class SEO {

	public static function SitemapHTML()
	{
		$sitemap = new SEOSitemap();

		return $sitemap->get();
	}

	public static function HeadTags()
	{
		$tags = new HeadTags();
		
		return $tags->get();
	}
}