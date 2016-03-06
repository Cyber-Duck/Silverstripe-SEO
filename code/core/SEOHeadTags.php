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
}