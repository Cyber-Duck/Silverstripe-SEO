<?php

/**
 * The core SEO class is where module methods are called from. 
 * Creates our page Meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
final class SEO {

    /**
     * @since version 1.0
     *
     * @static self $instance The SEO instance
     **/
    private static $instance;

    /**
     * @since version 1.0
     *
     * @static string $title Page Meta title
     **/
    private static $title;

    /**
     * @since version 1.0
     *
     * @static string $description Page Meta description
     **/
    private static $description;

    /**
     * @since version 1.0
     *
     * @static string $pageURL Page URL
     **/
    private static $pageURL;

    /**
     * @since version 1.0
     *
     * @static object $page Current Page object
     **/
    private static $page;

    /**
     * @since version 1.0
     *
     * @static SEO_HeadTags $tags Other head tags object
     **/
    private static $tags;

    /**
     * @since version 1.0
     *
     * @static SEO_Pagination $paginaton Pagination Meta object
     **/
    private static $paginaton;

    /**
     * @since version 1.0
     *
     * @static string $html The Meta tags HTML output
     **/
    private static $html;

    /**
     * Initialise and return the SEO object
     *
     * @since version 1.0
     *
     * @return self Return the SEO instance
     **/
    public static function init()
    {
        if (null === static::$instance) {
            static::$instance = new static();

            // set the default URL for Meta tags like canonical
            self::setPageURL(Director::AbsoluteBaseURL().substr(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),1));

            // Initialise coe objects
            self::$tags = new SEO_HeadTags();
            self::$paginaton = new SEO_Pagination();
        }
        return static::$instance;
    }

    /**
     * Render and return all head tags. All the different Meta tags are populated
     * into the HeadTags .ss template
     *
     * @since version 1.0
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
     * Set the current page URL for use within tags
     *
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
     *
     * @param string $text      The Meta text string
     * @param object $object    The object to use
     * @param string $separator Separates the last 2 items
     *
     * @return void
     **/
    public static function setDynamicTitle($text, $object, $separator = 'and')
    {
        self::$title = self::setDynamic($text, $object);
    }

    /**
     * Set a dynamic Meta description tag using an object and placeholders
     *
     * @since version 1.0
     *
     * @param string $text      The Meta text string
     * @param object $object    The object to use
     * @param string $separator Separates the last 2 items
     *
     * @return void
     **/
    public static function setDynamicDescription($text, $object, $separator = 'and')
    {
        self::$description = self::setDynamic($text, $object);
    }

    /**
     * Set rel and prev Meta tags
     *
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
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
     * @since version 1.0
     *
     * @return void
     **/
    private static function getCurrentPage()
    {
        if(self::$page == null) self::$page = Controller::curr();
    }

    /**
     * Set a dynamic Meta tag populated with an object properties
     *
     * @since version 1.0
     *
     * @param string $text   Meta text with placeholders [Value]
     * @param object $object The object to use
     * @param string $seperator    Separator to use before the last value when using multiple values
     *
     * @return string Returns text with the placeholders replaced with object properties
     **/
    private static function setDynamic($text, $object, $seperator)
    {
        preg_match_all("/\[([^\]]*)\]/",$text,$matches, PREG_PATTERN_ORDER);

        // get all matching placeholders
        $placeholders = $matches[1];

        // loop through placeholders
        foreach($placeholders as $value){
            // check for relation placeholders with a .
            if(strpos($value,".") !== false){
                $relations = explode('.',$value);

                // get the relation name
                $many = $relations[0];

                // get the relation property name
                $property = $relations[1];

                // loop the relation and assign the necessary property to an array
                if($object->hasMany($many) || $object->manyMany($many)){
                    foreach($object->$many() as $one){
                        $values[] = trim($one->$property);
                    }
                    $last = array_pop($values);
                    $first = implode(', ',$values);

                    // if only one property use it otherwise add the "and" separator
                    if($first == NULL){
                        $result = $last;
                    } else {
                        $result = array();
                        $result[] = $first;
                        $result[] = ', '.$seperator;
                        $result[] = $last;
                        $result = implode($result);
                    }
                } else {
                    user_error('Invalid relations in dynamic SEO tag');
                }
                
            } else {
                $result = trim($object->$value);
            }
            // replace the placeholder with the new value
            $text = trim(str_replace('['.htmlspecialchars($value).']', $result, $text));
        }
        return $text;
    }

    /**
     * Private constructor
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function __construct(){}

    /**
     * Private clone
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function __clone(){}

    /**
     * Private wakeup
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function __wakeup(){}
}