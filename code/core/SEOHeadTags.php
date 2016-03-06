<?php

/**
 * Responsible for creating Meta tags with user generated names and values
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOHeadTags {

    /**
     * @since version 1.0
     *
     * @var object $model The current page object
     **/
    private $model;

    /**
     * @since version 1.0
     *
     * @var string $html The Meta tag HTML
     **/
    private $html;

    /**
     * Set the current page object
     *
     * @since version 1.0
     *
     * @param object $page A page object to set
     *
     * @return self Returns the current instance
     **/
    public function setPage($page)
    {
        $this->model = $page;

        return $this;
    }

    /**
     * Get the Meta tag HTML
     *
     * @since version 1.0
     *
     * @return string Returns the other Meta tags GridField HTMl
     **/
    public function html()
    {
        return $this->html;
    }

    /**
     * Build the Meta tags HTML
     *
     * @since version 1.0
     *
     * @return self Returns the current instance
     **/
    public function get()
    {
        if(method_exists($this->model,'HeadTags')){
            foreach($this->model->HeadTags() as $tag){

                if($tag->Type == 'name'){
                    $this->getMetaTag($tag->Name,$tag->Value);
                    break;
                }
                if($tag->Type == 'link'){
                    $this->getLinkTag($tag->Name,$tag->Value);
                    break;
                }
                if($tag->Type == 'property'){
                    $this->getPropertyTag($tag->Name,$tag->Value);
                    break;
                }
            }
        }
        // Add SilverStripe generated tags

        // generator tag
        $generator = trim(Config::inst()->get('SiteTree', 'meta_generator'));

        if(!empty($generator)) {
            $this->getMetaTag('generator', Convert::raw2att($generator));
        }
        // charset tag
        $charset = Config::inst()->get('ContentNegotiator', 'encoding');

        $this->getHttpEquivTag('Content-type', 'text/html; charset='.$charset);

        // CMS preview
        if(Permission::check('CMS_ACCESS_CMSMain')
            && in_array('CMSPreviewable', class_implements($this))
            && !$this instanceof ErrorPage
            && $this->ID > 0
        ) {
            $this->getMetaTag('x-page-id', $this->ID);
            $this->getMetaTag('x-cms-edit-link', Controller::curr()->CMSEditLink());
        }
        return $this;
    }

    /**
     * Create a <meta> tag with a name attribute
     *
     * @since version 1.0
     *
     * @param string $name  The name of the tag
     * @param string $value The value of the tag
     *
     * @return void
     **/
    private function getMetaTag($name,$value)
    {
        $this->html .= '<meta name="'.$name.'" content="'.htmlspecialchars($value).'">'.PHP_EOL;
    }

    /**
     * Create a <link> tag
     *
     * @since version 1.0
     *
     * @param string $name  The name of the tag
     * @param string $value The value of the tag
     *
     * @return void
     **/
    private function getLinkTag($name,$value)
    {
        $this->html .= '<link rel="'.$name.'" href="'.htmlspecialchars($value).'">'.PHP_EOL;
    }

    /**
     * Create a <meta> tag with a property attribute
     *
     * @since version 1.0
     *
     * @param string $name  The name of the tag
     * @param string $value The value of the tag
     *
     * @return void
     **/
    private function getPropertyTag($name,$value)
    {
        $this->html .= '<meta property="'.$name.'" content="'.htmlspecialchars($value).'">'.PHP_EOL;
    }

    /**
     * Create a <meta> tag with a http-equiv attribute
     *
     * @since version 1.2
     *
     * @param string $name  The name of the tag
     * @param string $value The value of the tag
     *
     * @return void
     **/
    private function getHttpEquivTag($name,$value)
    {
        $this->html .= '<meta http-equiv="'.$name.'" content="'.htmlspecialchars($value).'">'.PHP_EOL;
    }
}