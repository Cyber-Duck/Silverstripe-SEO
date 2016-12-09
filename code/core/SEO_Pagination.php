<?php
/**
 * SEO_Pagination
 *
 * Creates pagination rel and prev Meta tags and validates their values
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Pagination
{
    /**
     * The pagination URL
     *
     * @since version 1.0.0
     *
     * @var string $url 
     **/
    private $url;

    /**
     * The admin SEO panel heading
     *
     * @since version 1.0.0
     *
     * @var string $html 
     **/
    private $html;

    /**
     * The total number of items across all pages
     *
     * @since version 1.0.0
     *
     * @var int $total 
     **/
    private $total = 0;

    /**
     * The number of paginated items per page
     *
     * @since version 1.0.0
     *
     * @var int $perPage 
     **/
    private $perPage = 12;

    /**
     * The admin SEO panel heading
     *
     * @since version 1.0.0
     *
     * @var string $param 
     **/
    private $param = 'start';

    /**
     * The URL pagination param value
     *
     * @since version 1.0.0
     *
     * @var int $paramCount 
     **/
    private $paramCount = 0;

    /**
     * The current pagination page number
     *
     * @since version 1.0.0
     *
     * @var int $currentPage
     **/
    private $currentPage = 1;

    /**
     * The number of paginated pages
     *
     * @since version 1.0.0
     *
     * @var int $pages 
     **/
    private $pages = 0;

    /**
     * An array of URL params to whitelist for inclusion use within pagination URLs
     *
     * @since version 1.0.0
     *
     * @var array $allowed 
     **/
    private $allowed = array();

    /**
     * An array of query strings to use within pagination URLs
     *
     * @since version 1.0.0
     *
     * @var array $queryStrings 
     **/
    private $queryStrings = array();

    /**
     * Set the pagination URL
     *
     * @since version 1.0.0
     *
     * @param string $url
     *
     * @return object Returns the current class instance
     **/
    public function setURL($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the pagination total
     *
     * @since version 1.0.0
     *
     * @param int $total The total number of pages to set
     *
     * @return object Returns the current class instance
     **/
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Set the pagination items per page
     *
     * @since version 1.0.0
     *
     * @param int $perPage The number of items per page to set
     *
     * @return object Returns the current class instance
     **/
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set the pagination URL parameter
     *
     * @since version 1.0.0
     *
     * @param string $param The pagination URL param to set
     *
     * @return object Returns the current class instance
     **/
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Set URL parameters and their values to whitelist and include in pagination URLs
     *
     * @since version 1.0.0
     *
     * @param string|array $param The pagination URL param(s) name(s)
     *
     * @return void
     **/
    public function allowedParams($param)
    {
        if(is_array($param)){
            $this->allowed = array_merge($this->allowed,$param);
        } else {
            $this->allowed[$param] = '';
        }
    }

    /**
     * Get the pagination HTML
     *
     * @since version 1.0.0
     *
     * @return object Returns the pagination Meta tags HTML
     **/
    public function html()
    {
        return $this->html;
    }

    /**
     * Set values, check validation, and build the pagination
     *
     * @since version 1.0.0
     *
     * @return object Returns the current class instance
     **/
    public function get()
    {
        if($this->total === 0) return $this;

        $this->setParamCount();
        $this->setCurrentPage();
        $this->setPages();

        if($this->checkParams() === true){
            $this->paramCount = (int) $this->paramCount;

            $this->setQueryString();
            $this->setPrev();
            $this->setNext();
        } else {
            $this->redirect404();
        }
        return $this;
    }

    /**
     * Set the pagination GET URL page parameter
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setParamCount()
    {
        $this->paramCount = Controller::curr()->request->getVar($this->param);
    }

    /**
     * Set the current pagination page
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setCurrentPage()
    {
        $this->currentPage = ($this->paramCount / $this->perPage) + 1;
    }

    /**
     * Set the total number of pages
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setPages()
    {
        $this->pages = ceil($this->total / $this->perPage);
    }

    /**
     * Validate and set the pagination GET URL page parameter
     *
     * Protects against unexpected values in the page pagination URL parameter. 
     * Checks: 
     * If the count URL param is NULL or "0" (we are on the first page)
     * If the count param number is not greater than the total pages
     * If the count param number is a multiple of the per page number
     * If the count param is a number
     *
     * @since version 1.0.0
     *
     * @return boolean True for success or false to initiate 404 redirect
     **/
    private function checkParams()
    { 
        if($this->paramCount === NULL){
            return true;
        }
        if($this->paramCount === "0"){
            return true;
        }
        if($this->currentPage > $this->pages){
            return false;
        }
        if($this->paramCount % $this->perPage !== 0){
            return false;
        }
        if(!preg_match('/^[0-9]+$/', $this->paramCount)){
            return false;
        }
        return true;
    }

    /**
     * Set the pagination URL query string
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setQueryString()
    {
        foreach($this->allowed as $param => $value){
            $getVar = Controller::curr()->request->getVar($param);

            if($getVar !== NULL){
                if(is_string($getVar)){
                    $this->queryStrings[] = $param.'='.htmlspecialchars($getVar);
                }
            }
        }
    }

    /**
     * Set the pagination rel prev Meta tag
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setPrev()
    {
        if($this->currentPage > 1){
            if($this->currentPage == 2){
                $this->html .= '<link rel="prev" href="'. $this->getURL().'">'.PHP_EOL;
            } else {
                $prev = $this->param.'='.(($this->currentPage - 2) * $this->perPage);

                $this->html .= '<link rel="prev" href="'. $this->getURL($prev).'">'.PHP_EOL;
            }
        }
    }

    /**
     * Set the pagination rel next Meta tag
     *
     * @since version 1.0.0
     *
     * @return void
     **/
    private function setNext()
    {
        if($this->pages > 1){
            if($this->currentPage < $this->pages) {
                $next = $this->param.'='.($this->currentPage * $this->perPage);

                $this->html .= '<link rel="next" href="'.$this->getURL($next).'">'.PHP_EOL;
            }
        }
    }

    /**
     * Build the pagination URL
     *
     * @param string $param The pagination URL query string
     *
     * @since version 1.0.0
     *
     * @return string Return the URL string to use in the pagination Meta tags
     **/
    private function getURL($param = '')
    {
        if($param == '' && empty($this->allowed)){
            return $this->url;
        }
        $qs = $this->queryStrings;

        if($param != '') array_unshift($qs,$param);

        return $this->url . '?' . implode('&amp;',$qs);
    }

    /**
     * 404 redirect
     *
     * @since version 1.0.0
     *
     * @throws SS_HTTPResponse_Exception Return a 404 response
     * @return void
     **/
    private function redirect404()
    {
        Controller::curr()->response->removeHeader('Location');

        throw new SS_HTTPResponse_Exception(ErrorPage::response_for(404), 404);
    }
}
