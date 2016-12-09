<?php
/**
 * SEO
 *
 * The core SEO class is where module methods are called from. 
 * Creates our page Meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
final class SEO
{
    /**
     * The SEO instance
     *
     * @since version 1.0.0
     *
     * @static self $instance 
     **/
    private static $instance;

    /**
     * Page Meta title
     *
     * @since version 1.0.0
     *
     * @static string $title
     **/
    private static $title;

    /**
     * Page Meta description
     *
     * @since version 1.0.0
     *
     * @static string $description
     **/
    private static $description;

    /**
     * Page URL
     *
     * @since version 1.0.0
     *
     * @static string $pageURL
     **/
    private static $pageURL;

    /**
     * Current Page object
     *
     * @since version 1.0.0
     *
     * @static object $page 
     **/
    private static $page;

    /**
     * Other head tags object
     *
     * @since version 1.0.0
     *
     * @static SEO_HeadTags $tags 
     **/
    private static $tags;

    /**
     * Sitemap object
     *
     * @since version 1.0.0
     *
     * @static SEO_Sitemap $sitemap 
     **/
    private static $sitemap;

    /**
     * Pagination Meta object
     *
     * @since version 1.0.0
     *
     * @static SEO_Pagination $pagination 
     **/
    private static $paginaton;

    /**
     * The Meta tags HTML output
     *
     * @since version 1.0.0
     *
     * @static string $html 
     **/
    private static $html;

    /**
     * Initialise and return the SEO object
     *
     * @since version 1.0.0
     *
     * @return self Return the SEO instance
     **/
    public static function init()
    {
        if (null === static::$instance) {
            static::$instance = new static();

            // set the default URL for Meta tags like canonical
            self::setPageURL(Director::AbsoluteBaseURL().substr(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),1));

            // Initialise core objects
            self::$tags = new SEO_HeadTags();
            self::$sitemap = new SEO_Sitemap();
            self::$paginaton = new SEO_Pagination();
        }
        return static::$instance;
    }

    /**
     * Render and return all head tags. All the different Meta tags are populated
     * into the HeadTags .ss template
     *
     * @since version 1.0.0
     *
     * @return string The render Meta tags HTML
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
     * Return the HTML sitemap
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    public static function getSitemapHTML()
    {
        return self::$sitemap->getSitemapHTML();
    }

    /**
     * Return the XML sitemap
     *
     * @since version 1.0.0
     *
     * @return string
     **/
    public static function getSitemapXML()
    {
        return self::$sitemap->getSitemapXML();
    }

    /**
     * Set the current page URL for use within tags
     *
     * @since version 1.0.0
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
     * Set the current page object
     *
     * @since version 1.0.0
     *
     * @param object $page The object to set as the current page
     *
     * @return void
     **/
    public static function setPage($page)
    {
        self::$page = $page;
    }

    /**
     * Set the current Meta title value
     *
     * @since version 1.0.0
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
     * Set the current Meta description value
     *
     * @since version 1.0.0
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
     * @since version 1.0.0
     *
     * @param string $text      The Meta text string
     * @param object $object    The object to use
     * @param string $separator Separates the last 2 items
     *
     * @return void
     **/
    public static function setDynamicTitle($text, $object, $separator = 'and')
    {
        $meta = new SEO_DynamicMeta($text, $object, $separator);
        self::setTitle($meta->create());
    }

    /**
     * Set a dynamic Meta description tag using an object and placeholders
     *
     * @since version 1.0.0
     *
     * @param string $text      The Meta text string
     * @param object $object    The object to use
     * @param string $separator Separates the last 2 items
     *
     * @return void
     **/
    public static function setDynamicDescription($text, $object, $separator = 'and')
    {
        $meta = new SEO_DynamicMeta($text, $object, $separator);
        self::setDescription($meta->create());
    }

    /**
     * Set rel and prev Meta tags
     *
     * @since version 1.0.0
     *
     * @param string $total   Pagination total
     * @param int    $perPage Pagination items per page
     * @param string $param   Pagination URL param
     *
     * @return SEO_Pagination Returns the pagination class
     **/
    public static function setPagination($total = 0, $perPage = 12, $param = 'start')
    {
        return self::$paginaton
            ->setTotal($total)
            ->setPerPage($perPage)
            ->setParam($param);
    }

    /**
     * Get the current page URL
     *
     * @since version 1.0.0
     *
     * @return string Returns the full page URL
     **/
    public static function getPageURL()
    {
        return self::$pageURL;
    }

    /**
     * Get the current page object
     *
     * @since version 1.0.0
     *
     * @return object Returns the current page object
     **/
    public static function getPage()
    {
        return self::$page;
    }

    /**
     * Get the current page title
     *
     * @since version 1.0.0
     *
     * @return string Returns an escaped Meta title value
     **/
    public static function getTitle()
    {
        if(isset(self::$title)){
            return htmlspecialchars(self::$title);
        }
        return htmlspecialchars(self::$page->MetaTitle);
    }

    /**
     * Get the current page description
     *
     * @since version 1.0.0
     *
     * @return string Returns an escaped Meta description value
     **/
    public static function getDescription()
    {
        if(isset(self::$description)){
            return htmlspecialchars(self::$description);
        }
        return htmlspecialchars(self::$page->MetaDescription);
    }

    /**
     * Get the page pagination Meta tags
     *
     * @since version 1.0.0
     *
     * @return string Returns the rel prev and next Meta tags HTML
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
     * @since version 1.0.0
     *
     * @return string Returns tags generated from the other Meta tags Grid Field
     **/
    public static function getOtherHTML()
    {
        return self::$tags->setPage(self::$page)->get()->html();
    }

    /**
     * Check if there is a current page object, if not use the current controller page
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private static function getCurrentPage()
    {
        if(self::$page == null) self::$page = Controller::curr();
    }

    /**
     * Private constructor
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function __construct(){}

    /**
     * Private clone
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function __clone(){}

    /**
     * Private wakeup
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function __wakeup(){}
}