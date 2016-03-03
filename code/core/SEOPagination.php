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

    private $url;

    private $html;

    private $total = 0;

    private $perPage;

    private $param;

    private $countParam;

    private $currentPage;

    private $pages;

    public function setURL($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    public function html()
    {
        return $this->html;
    }

    public function get()
    {
        if($this->total === 0) return $this;

        $this->checkModulus();

        $this->setCountParam();
        $this->setCurrentPage();
        $this->setPages();

        $this->getPrev();
        $this->getNext();

        return $this;
    }

    private function checkModulus()
    {

    }

    private function setCountParam()
    {
        $this->countParam = isset($_GET[$this->param]) ? $_GET[$this->param] : 0;
    }

    private function setCurrentPage()
    {
        $this->currentPage = ($this->countParam / $this->perPage) + 1;
    }

    private function setPages()
    {
        $this->pages = ceil($this->total / $this->perPage);
    }

    private function getPrev()
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

    private function getNext()
    {
        if($this->pages > 1){
            if($this->currentPage < $this->pages) {
                $next = '?'.$this->param.'='.($this->currentPage * $this->perPage);

                $this->html .= '<link rel="next" href="'.$this->getURL($next).'" />'.PHP_EOL;
            }
        }
    }

    private function getURL($param = '')
    {
        return $this->url.$param;
    }
}