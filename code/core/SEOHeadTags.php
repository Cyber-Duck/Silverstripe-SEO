<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOHeadTags {

    /**
     * @var object $model The current object
     **/
    private $model;

    /**
     * @var string $html The head tag HTML
     **/
    private $html;

    /**
     * Set the current page object
     *
     * @param object $page
     *
     * @return object
     **/
    public function setPage($page)
    {
        $this->model = $page;

        return $this;
    }

    /**
     * Get the Meta tag HTML
     *
     * @return string
     **/
    public function html()
    {
        return $this->html;
    }

    /**
     * Build the Meta tag HTML
     *
     * @return object
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
     * Initialise the SEO object
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     **/
    private function getMetaTag($name,$value)
    {
        $this->html .= '<meta name="'.$name.'" content="'.htmlspecialchars($value).'">'.PHP_EOL;
    }

    /**
     * Initialise the SEO object
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     **/
    private function getLinkTag($name,$value)
    {
        $this->html .= '<link rel="'.$name.'" href="'.htmlspecialchars($value).'">'.PHP_EOL;
    }

    /**
     * Initialise the SEO object
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     **/
    private function getPropertyTag($name,$value)
    {
        $this->html .= '<meta property="'.$name.'" content="'.htmlspecialchars($value).'">'.PHP_EOL;
    }
}