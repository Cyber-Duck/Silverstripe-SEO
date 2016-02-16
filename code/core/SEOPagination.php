<?php

class SEOPagination {

	private $url;

	private $html;

	private $total = 0;

	private $perPage = 0;

	private $param;

	private $countParam;

	private $currentPage = 0;

	private $pages = 0;

	function __construct()
	{
		$this->html = $html;
	}

	public function setURL($url)
	{
		$this->url = $url;
	}

	public function setTotal()
	{
		$this->total = $total;
	}

	public function setPerPage()
	{
		$this->perPage = $perPage;
	}

	public function setParam()
	{
		$this->param = $param;
	}

	public function html()
	{
		return $this->html;
	}

	public function get()
	{
		$this->checkModulus();

		$this->setCountParam();
		$this->setCurrentPage();
		$this->setPages();

		$this->getPrev();
		$this->getNext();
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
                $next = '?'.$param.'='.($this->currentPage + $this->perPage);

                $meta .= '<link rel="next" href="'.$this->getURL($next).'" />'.PHP_EOL;
            }
        }
	}

	private function getURL($param)
	{
		return $this->url.$param;
	}
}