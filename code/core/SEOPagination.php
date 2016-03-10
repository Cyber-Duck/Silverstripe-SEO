<?php

/**
 * Created pagiantion rel and prev Meta tags and validates their values
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOPagination {

    /**
     * @since version 1.0
     *
     * @var string $url The pagination URL
     **/
    private $url;

    /**
     * @since version 1.0
     *
     * @var string $html The admin SEO panel heading
     **/
    private $html;

    /**
     * @since version 1.0
     *
     * @var int $total The total number of items across all pages
     **/
    private $total = 0;

    /**
     * @since version 1.0
     *
     * @var int $perPage The number of paginated items per page
     **/
    private $perPage = 12;

    /**
     * @since version 1.0
     *
     * @var string $param The admin SEO panel heading
     **/
    private $param = 'start';

    /**
     * @since version 1.0
     *
     * @var int $countParam The URL pagination param value
     **/
    private $countParam = 0;

    /**
     * @since version 1.0
     *
     * @var int $currentPage The current pagination page number
     **/
    private $currentPage = 1;

    /**
     * @since version 1.0
     *
     * @var int $pages The number of paginated pages
     **/
    private $pages = 0;

    /**
     * Set the pagination URL
     *
     * @since version 1.0
     *
     * @param string $url
     *
     * @return self Returns the current class instance
     **/
    public function setURL($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the pagination total
     *
     * @since version 1.0
     *
     * @param int $total The total number of pages to set
     *
     * @return self Returns the current class instance
     **/
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Set the pagination items per page
     *
     * @since version 1.0
     *
     * @param int $perPage The number of items per page to set
     *
     * @return self Returns the current class instance
     **/
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set the pagination URL paramater
     *
     * @since version 1.0
     *
     * @param string $param The pagination URL param to set
     *
     * @return self Returns the current class instance
     **/
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get the pagination HTML
     *
     * @since version 1.0
     *
     * @return self Returns the pagination Meta tags HTML
     **/
    public function html()
    {
        return $this->html;
    }

    /**
     * Set values, check validation, and build the pagination
     *
     * @since version 1.0
     *
     * @return self Returns the current class instance
     **/
    public function get()
    {
        if($this->total === 0) return $this;

        $this->setCurrentPage();
        $this->setCountParam();
        $this->setPages();

        $this->checkModulus();
        $this->checkCeiling();

        $this->setPrev();
        $this->setNext();

        return $this;
    }

    /**
     * Validate and set the pagination GET URL page parameter
     *
     * @since version 1.0
     *
     * @return void | SS_HTTPResponse Set the count value of 404 if the value is not valid
     **/
    private function setCountParam()
    {
        $param = Controller::curr()->request->getVar($this->param);

        if($param === NULL || $param == 0){
            $this->countParam = 0;
            return;
        }
        if(is_string($param) && $param > 0){
            $this->countParam = (int) $param;
            return;
        }
        $this->redirect404();
    }

    /**
     * Set the current pagination page
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function setCurrentPage()
    {
        $this->currentPage = ($this->countParam / $this->perPage) + 1;
    }

    /**
     * Set the total number of pages
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function setPages()
    {
        $this->pages = ceil($this->total / $this->perPage);
    }

    /**
     * Check the current page is not greater than the total pages
     *
     * @since version 1.0
     *
     * @return void | SS_HTTPResponse Redirect to a 404 if the current value is not as expected
     **/
    private function checkCeiling()
    {
        if($this->currentPage > $this->pages){
            $this->redirect404();
        }
    }

    /**
     * Check the modules of the URL param
     *
     * @since version 1.0
     *
     * @return void | SS_HTTPResponse Redirect to a 404 if the current value is not as expected
     **/
    private function checkModulus()
    {
        if($this->countParam % $this->perPage !== 0){
            $this->redirect404();
        }
    }

    /**
     * 404 redirect
     *
     * @since version 1.0
     *
     * @throws SS_HTTPResponse_Exception Return a 404 response
     **/
    private function redirect404()
    {
        Controller::curr()->response->removeHeader('Location');

        throw new SS_HTTPResponse_Exception(ErrorPage::response_for(404), 404);
    }

    /**
     * Set the pagination rel prev Meta tag
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function setPrev()
    {
        if($this->currentPage > 1){
            if($this->currentPage == 2){
                $this->html .= '<link rel="prev" href="'. $this->getURL().'">'.PHP_EOL;
            } else {
                $prev = '?'.$this->param.'='.(($this->currentPage - 2) * $this->perPage);

                $this->html .= '<link rel="prev" href="'. $this->getURL($prev).'">'.PHP_EOL;
            }
        }
    }

    /**
     * Set the pagination rel next Meta tag
     *
     * @since version 1.0
     *
     * @return void
     **/
    private function setNext()
    {
        if($this->pages > 1){
            if($this->currentPage < $this->pages) {
                $next = '?'.$this->param.'='.($this->currentPage * $this->perPage);

                $this->html .= '<link rel="next" href="'.$this->getURL($next).'">'.PHP_EOL;
            }
        }
    }

    /**
     * Build the pagination URL
     *
     * @since version 1.0
     *
     * @return string Return the URL string to use in the pagination Meta tags
     **/
    private function getURL($param = '')
    {
        return $this->url.$param;
    }
}