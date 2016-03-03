<?php

final class SEO {

	private static $instance;

	private static $title;

	private static $description;

	private static $pageURL;

	private static $page;

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

    public static function setPageURL($url, $escape = true)
    {
    	self::$pageURL = $escape === false ? $url : htmlspecialchars($url);
    }

    public static function setPage(object $page)
    {
    	self::$page = $page;
    }

	public static function setTitle($string)
	{
    	self::$title = $title;
	}

	public static function setDescription($string)
	{
    	self::$description = $description;
	}

    public static function getPageURL()
    {
    	return self::$pageURL;
    }

    public static function getPage()
    {
    	return self::$page;
    }

	public static function getTitle()
	{
    	return self::$title;
	}

	public static function getDescription()
	{
    	return self::$description;
	}

	public static function Pagination($total = 0, $perPage = 12, $param = 'start')
	{
		self::$paginaton
			->setTotal($total)
			->setPerPage($perPage)
			->setParam($param);
	}

    public static function setSubsites($subsites = false)
    {
    	self::$subsites = $subsites;
    }

	public static function HeadTags()
	{
		self::getCurrentPage();

		$tags = new ArrayData(array(
			'MetaTitle'       => self::runTitle(),
			'MetaDescription' => self::runDescription(),
			'PageURL'         => self::runPageURL(),
            'PageSEO'         => self::runPage(),
            'Pagination'      => self::runPagination(),
            'OtherTags'       => self::runOtherMeta()
        ));
        return $tags->renderWith('HeadTags');
	}

	private static function getCurrentPage()
	{
		if(self::$page == null) self::$page = Controller::curr();
	}

	private static function runTitle()
	{
		if(self::$dynamicTitle === true){
			return self::runDynamicMeta();
		}
		return self::$page->MetaTitle;
	}

	private static function runDescription()
	{
		return self::$description;
	}

	private static function runDynamicMeta()
	{
		return self::$description;
	}

	private static function runPageURL()
	{
		return self::$pageURL;
	}

	private static function runPage()
	{
		return self::$page;
	}

	private static function runPagination()
	{
		return self::$paginaton
			->setURL(self::$pageURL)
			->get()
			->html();
	}

	private static function runOtherMeta()
	{
		return self::$tags->setPage(self::$page)->get()->html();
	}

	public static function SitemapHTML()
	{
		$sitemap = new SEOSitemap();

		return $sitemap->get()->html();
	}

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}