<?php
/**
 * SEOmeta
 * Generate our meta tags
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOmeta {

	/**
	 * @var array $tags An array of all Meta tags
	 **/
	private $url;

	/**
	 * @var array $tags An array of all Meta tags
	 **/
	private $tags;

	/**
	 * @var array $content An array of all content based Meta tags
	 **/
	private $content;

	/**
	 * @var array $content An array of all indexing based Meta tags
	 **/
	private $indexing;

	/**
	 * Create all the Meta HTML
	 *
	 * @return string
	 **/
	public function tags()
	{
		$this->url = Director::BaseURL();

		$page = substr($_SERVER['REQUEST_URI'],1);
		$page = $page == '' ? 'home' : $page;

		$id = DataObject::get_one('SiteTree',"URLsegment = '".$page."'")->ID;

		$meta = Page::get()->byId($id);

		foreach($meta as $tag) :
			$this->title($tag->Title);
			$this->description($tag->Description);
			$this->keywords($tag->Keywords);
			$this->robots($tag->Robots);
			$this->canonical($tag->Canonical);
		endforeach;

		$this->tags[] = implode(PHP_EOL,$this->content);
		$this->tags[] = implode(PHP_EOL,$this->indexing);

		return implode(PHP_EOL,$this->tags);
	}

	/**
	 * Returns an escaped or un-escaped value. Useful when input source is not trusted.
	 *
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	private function escape($value,$escape)
	{
		return $escape === true ? htmlspecialchars($value) : $value;
	}

	/**
	 * Create a <meta> HTML head tag.
	 *
	 * @param string  $name   The tag name property
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function meta($name,$value,$escape = false,$tag = 'name')
	{
		$value = $this->escape($value,$escape);

		return '<meta '.$tag.'="'.$name.'" value="'.$value.'">';
	}

	/**
	 * Create a <link> HTML head tag.
	 *
	 * @param string  $name   	  The tag name property
	 * @param string  $value  	  The value to return
	 * @param boolean $escape 	  True or false to escape or not
	 * @param array   $attributes An array of extra tag attributes
	 *
	 * @return string
	 **/
	private function link($name,$value,$escape = false,$attributes = array())
	{
		$value = $this->escape($value,$escape);

		$extra = array();
		if(count($attributes) > 0) :
			foreach($attributes as $key => $attr) :
				$extra[] = ' '.$key.'="'.$this->escape($attr,$escape).'"';
			endforeach;
		endif;

		return '<link rel="'.$name.'" href="'.$value.'"'.implode('',$extra).'>';
	}

	/**
	 * Create a <title> HTML head tag.
	 *
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function title($value, $escape = false)
	{
		$this->content[] = '<title>'.$this->escape($value,$escape).'</title>';
	}

	/**
	 * Create a <meta> description HTML head tag.
	 *
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function description($value, $escape = false)
	{
		$this->content[] = $this->meta('description',$value,$escape);
	}

	/**
	 * Create a <meta> keywords HTML head tag. This tag isn't used anymore by 
	 * search engines because of spamming and can be left out of a page.
	 *
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function keywords($value, $escape = false)
	{
		$this->content[] = $this->meta('keywords',$value,$escape);
	}

	/**
	 * Create a <meta> robots HTML head tag. You can use this method to build 
	 * page specific tags and stop most duplicate content issues.
	 *
	 * @param string  $value  The value to return
	 *
	 * @return string
	 **/
	public function robots($value)
	{
		$this->content[] = $this->meta('robots', $value);
	}

	/**
	 * Create a <link> canonical HTML head tag. This should be on every page of
	 * your site and helps with duplicate content issues.
	 *
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 * @param boolean $host   Include the host domain in the href attribute
	 *
	 * @return string
	 **/
	public function canonical($value, $escape = false, $host = true)
	{
		$value = $host === true ? $this->url.$value : $value;

		$this->content[] = $this->link('canonical',$value,$escape)
	}
}