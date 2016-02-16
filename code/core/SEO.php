<?php

final class SEO {

	private static $instance;

	private static $page;

	private static $pageURL;

	private static $subsites;

	private static $tags;

	private static $paginaton;

	private static $html;

	public static function init()
    {
        if (null === static::$instance) {
            static::$instance = new static();

            self::setPageURL(Director::AbsoluteBaseURL().substr(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),1));

            self::$tags = new SEOHeadTags();
            self::$paginaton = new SEOPagination();
        }
        return static::$instance;
    }

    public static function subsites($subsites = false)
    {
    	self::$subsites = $subsites;
    }

    public static function setPageURL($url, $escape = true)
    {
    	self::$pageURL = $escape === true ? htmlspecialchars($url) : $url;;
    }

    public static function setPage(object $page)
    {
    	self::$page = $page;
    }

    public static function pageURL()
    {
    	return self::$pageURL;
    }

	public static function HeadTags()
	{
		self::getCurrentPage();

		$pagination = self::$paginaton
			->setURL(self::$pageURL)
			->get()
			->html();

		$tags = new ArrayData(array(
			'PageURL'    => self::$pageURL,
            'PageSEO'    => self::$page,
            'Pagination' => $pagination,
            'OtherTags'  => self::$tags->setPage(self::$page)->get()->html()
        ));
        return $tags->renderWith('HeadTags');
	}

	public static function Pagination($total = 0, $perPage = 12, $param = 'start')
	{
		self::$paginaton
			->setTotal($total)
			->setPerPage($perPage)
			->setParam($param);
	}

	public static function SitemapHTML()
	{
		$sitemap = new SEOSitemap();

		return $sitemap->get()->html();
	}

	private static function getCurrentPage()
	{
		if(self::$page == null) self::$page = Controller::curr();
	}

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}