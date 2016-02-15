<?php

class SEOPagination {

	private $param;

	private $total;

	private $perPage;

	function __construct($param = 'start', $total = 0, $perPage = 12)
	{
		$this->param = $param;
		$this->total = $total;
		$this->perPage = $perPage;
	}
}