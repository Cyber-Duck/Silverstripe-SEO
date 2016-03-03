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

	public static function HeadTags()
	{
		self::getCurrentPage();

		$tags = new ArrayData(array(
			'MetaTitle'       => self::getTitle(),
			'MetaDescription' => self::getDescription(),
			'PageURL'         => self::getPageURL(),
            'PageSEO'         => self::getPage(),
            'Pagination'      => self::getPaginationHTML(),
            'OtherTags'       => self::getOtherHTML()
        ));
        return $tags->renderWith('HeadTags');
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

	public static function setDynamicTitle($placeholder, $object)
	{
    	self::$title = self::setDynamic($placeholder, $object);
	}

	public static function setDynamicDescription($placeholder, $object)
	{
    	self::$description = self::setDynamic($placeholder, $object);
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

    public static function setSubsites($subsites = false)
    {
    	self::$subsites = $subsites;
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
		if(isset(self::$title)){
			return self::$title;
		}
		return self::$page->MetaTitle;
	}

	public static function getDescription()
	{
		if(isset(self::$description)){
			return self::$description;
		}
		return self::$page->MetaDescription;
	}

	public static function getPaginationHTML()
	{
		return self::$paginaton
			->setURL(self::$pageURL)
			->get()
			->html();
	}

	public static function getOtherHTML()
	{
		return self::$tags->setPage(self::$page)->get()->html();
	}

	private static function getCurrentPage()
	{
		if(self::$page == null) self::$page = Controller::curr();
	}

	private static function setDynamic($text, $object, $and = ' and ')
	{
		preg_match_all("/\[([^\]]*)\]/",$text,$matches, PREG_PATTERN_ORDER);

		$placeholders = $matches[1];

		foreach($placeholders as $value){
			if(strpos($value,".") !== false){
				$relations = explode('.',$value);

				$many = $relations[0];
				$property = $relations[1];

				foreach($object->$many() as $one){
					$values[] = trim($one->$property);
				}
				$last = array_pop($values);
				$first = implode(', ',$values);

				$result = array();
				$result[] = $first;
				$result[] = ','.$and;
				$result[] = $last;
				$result = implode($result);
			} else {
				$result = trim($object->$value);
			}
			$text = trim(str_replace('['.$value.']', $result, $text));
		}
		return $text;
	}

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}