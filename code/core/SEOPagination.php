<?php

/**
 * Page SEO fields
 * Creates our page meta tags to deal with content, crawling, indexing, sitemap, and social sharing
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOPagination {

    /**
     * @var string $url The pagination URL
     **/
    private $url;

    /**
     * @var string $html The admin SEO panel heading
     **/
    private $html;

    /**
     * @var int $total The total numebr of items across all pages
     **/
    private $total = 0;

    /**
     * @var string $perPage The number of paginated items per page
     **/
    private $perPage = 12;

    /**
     * @var string $param The admin SEO panel heading
     **/
    private $param = 'start';

    /**
     * @var string $countParam The URL pagination param value
     **/
    private $countParam = 0;

    /**
     * @var int $currentPage The current pagination page number
     **/
    private $currentPage = 1;

    /**
     * @var string $pages The admin SEO panel heading
     **/
    private $pages;

    /**
     * Set the pagination URL
     *
     * @param string $url
     *
     * @return object
     **/
    public function setURL($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the pagination total
     *
     * @param int $total
     *
     * @return object
     **/
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Set the pagination items per page
     *
     * @param int $perPage
     *
     * @return object
     **/
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set the pagination URL paramater
     *
     * @param string $param
     *
     * @return object
     **/
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get the pagination HTML
     *
     * @return string
     **/
    public function html()
    {
        return $this->html;
    }

    /**
     * Build the pagination
     *
     * @return object
     **/
    public function get()
    {
        if($this->total === 0) return $this;

        $this->setCountParam();
        $this->setCurrentPage();
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
     * @return SS_HTTPResponse | void
     **/
    private function setCountParam()
    {
        $param = Controller::curr()->request->getVar($this->param);

        if($param === NULL){
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
     * @return void
     **/
    private function setCurrentPage()
    {
        $this->currentPage = ($this->countParam / $this->perPage) + 1;
    }

    /**
     * Set the total number of pages
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
     * @return SS_HTTPResponse | void
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
     * @return SS_HTTPResponse | void
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
     * @throws SS_HTTPResponse_Exception
     **/
    private function redirect404()
    {
        Controller::curr()->response->removeHeader('Location');

        throw new SS_HTTPResponse_Exception(ErrorPage::response_for(404), 404);
    }

    /**
     * Set the pagination rel prev Meta tag
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
     * @return void
     **/
    private function setNext()
    {
        if($this->pages > 1){
            if($this->currentPage < $this->pages) {
                $next = '?'.$this->param.'='.($this->currentPage * $this->perPage);

                $this->html .= '<link rel="next" href="'.$this->getURL($next).'" />'.PHP_EOL;
            }
        }
    }

    /**
     * Build the pagination URL
     *
     * @return string
     **/
    private function getURL($param = '')
    {
        return $this->url.$param;
    }
}