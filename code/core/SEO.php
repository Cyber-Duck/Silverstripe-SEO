<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
final class SEO {

    /**
     * @static object $instance The SEO instance
     **/
	private static $instance;

    /**
     * @static object $title Page Meta title
     **/
	private static $title;

    /**
     * @static object $description Page Meta description
     **/
	private static $description;

    /**
     * @static object $pageURL Page URL
     **/
	private static $pageURL;

    /**
     * @static object $page Current Page object
     **/
	private static $page;

    /**
     * @static object $subsites Has subsites
     **/
	private static $subsites;

    /**
     * @static object $tags Other head tags object
     **/
	private static $tags;

    /**
     * @static object $paginaton Pagination Meta object
     **/
	private static $paginaton;

    /**
     * @static object $html SEO instance
     **/
	private static $html;

    /**
     * Initialise the SEO object
     *
     * @return object
     **/
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

    /**
     * Render and return all head tags
     *
     * @return string
     **/
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

    /**
     * Set the current page URL
     *
     * @param string  $url The URL to set
     * @param boolean $url Escape the output
     *
     * @return void
     **/
    public static function setPageURL($url, $escape = true)
    {
    	self::$pageURL = $escape === false ? $url : htmlspecialchars($url);
    }

    /**
     * Set the current page
     *
     * @param object $page The object to set as the current page
     *
     * @return void
     **/
    public static function setPage(object $page)
    {
    	self::$page = $page;
    }

    /**
     * Set the current Meta title
     *
     * @param string $title The Meta title to set
     *
     * @return void
     **/
	public static function setTitle($title)
	{
    	self::$title = $title;
	}

    /**
     * Set the current Meta description
     *
     * @param string $description The Meta description to set
     *
     * @return void
     **/
	public static function setDescription($description)
	{
    	self::$description = $description;
	}

    /**
     * Set a dynamic Meta title tag using an object and placeholders
     *
     * @param string $text   The meta text string
     * @param object $object The object to use
     *
     * @return void
     **/
	public static function setDynamicTitle($text, $object)
	{
    	self::$title = self::setDynamic($text, $object);
	}

    /**
     * Set a dynamic Meta description tag using an object and placeholders
     *
     * @param string $text   The meta text string
     * @param object $object The object to use
     *
     * @return void
     **/
	public static function setDynamicDescription($text, $object)
	{
    	self::$description = self::setDynamic($text, $object);
	}

    /**
     * Set rel and prev Meta tags
     *
     * @param string $total   Pagination total
     * @param object $perPage Pagination items per page
     * @param object $param   Pagination URL param
     *
     * @return void
     **/
	public static function Pagination($total = 0, $perPage = 12, $param = 'start')
	{
		self::$paginaton
			->setTotal($total)
			->setPerPage($perPage)
			->setParam($param);
	}

    /**
     * Get an HTML list of the current site pages
     *
     * @return string
     **/
	public static function SitemapHTML()
	{
		$sitemap = new SEOSitemap();

		return $sitemap->get()->html();
	}

    /**
     * Set whether this is a subsite
     *
     * @return void
     **/
    public static function setSubsites($subsites = false)
    {
    	self::$subsites = $subsites;
    }

    /**
     * Get the current page SEO URL
     *
     * @return string
     **/
    public static function getPageURL()
    {
    	return self::$pageURL;
    }

    /**
     * Get the current page object
     *
     * @return object
     **/
    public static function getPage()
    {
    	return self::$page;
    }

    /**
     * Get the current page title
     *
     * @return string
     **/
	public static function getTitle()
	{
		if(isset(self::$title)){
			return self::$title;
		}
		return self::$page->MetaTitle;
	}

    /**
     * Get the current page description
     *
     * @return string
     **/
	public static function getDescription()
	{
		if(isset(self::$description)){
			return self::$description;
		}
		return self::$page->MetaDescription;
	}

    /**
     * Get the page pagination Meta tags
     *
     * @return string
     **/
	public static function getPaginationHTML()
	{
		return self::$paginaton
			->setURL(self::$pageURL)
			->get()
			->html();
	}

    /**
     * Get the current Grid Field Meta tags
     *
     * @return string
     **/
	public static function getOtherHTML()
	{
		return self::$tags->setPage(self::$page)->get()->html();
	}

    /**
     * Check for instance of current page and return
     *
     * @return object
     **/
	private static function getCurrentPage()
	{
		if(self::$page == null) self::$page = Controller::curr();
	}

    /**
     * Set a dynamic meta tag
     *
     * @return string
     **/
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