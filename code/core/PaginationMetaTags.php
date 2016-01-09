<?php

class PaginationMetaTags {

	private $param;

	private $total;

	private $perPage;

	function __construct($param, $total, $perPage)
	{
		$this->param = $param;
		$this->total = $total;
		$this->perPage = $perPage;
	}
}