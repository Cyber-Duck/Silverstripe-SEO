<?php
class SEO {

	private $url;

	private $meta;

	private $tags = array();

	function __construct()
	{
		$this->url = Director::BaseURL();

		$this->meta = new SEOmeta();
	}

	private function charset()
	{

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
		return '<title>'.$this->escape($value,$escape).'</title>';
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
		$this->meta('description',$value,$escape);
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
		$this->meta('keywords',$value,$escape);
	}

	/**
	 * Set the default site-wide robots <meta> tag value. For SEO purposes this 
	 * should be noindex,nofollow and then any pages you wish to be indexed 
	 * should be set as index,follow etc.
	 *
	 * @param boolean $index  Set to true (index) or false (noindex)
	 * @param boolean $follow Set to true (follow) or false (nofollow)
	 *
	 * @return string
	 **/
	public function robotsDefault($index = false,$follow = false)
	{
		$this->robots($index,$follow);
	}

	/**
	 * Create a <meta> robots HTML head tag. You can use this method to build 
	 * page specific tags and stop most duplicate content issues.
	 *
	 * @param boolean $index  Set to true (index) or false (noindex)
	 * @param boolean $follow Set to true (follow) or false (nofollow)
	 *
	 * @return string
	 **/
	public function robots($index = false,$follow = false)
	{
		$index  = $index  === true ? 'index'  : 'noindex';
		$follow = $follow === true ? 'follow' : 'nofollow';

		$this->meta('robots', $index.','.$follow);
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

		$this->link('canonical',$value,$escape)
	}

	/**
	 * Create a <link> alternate HTML head tag. This is used to point to the 
	 * page in another language format.
	 *
	 * @param string $value The value to return (URL)
	 * @param string $lang  The lang code (en) etc.
	 *
	 * @return string
	 **/
	public function alternate($value, $lang)
	{
		$attributes = array('hreflang' => $lang);

		$this->link('alternate',$value,false,$attributes);
	}

	/**
	 * Create a <meta> apple-touch-icon HTML head tag. These are used on apple
	 * devices liek iphones to create a bookmark icon
	 *
	 * @param string $value The value to return (image URL)
	 * @param string $sizes The lang code (en) etc.
	 *
	 * @return string
	 **/
	public function appleIcon($value, $sizes)
	{
		$attributes = array('sizes' => $sizes);

		$this->link('apple-touch-icon',$value,false,$attributes);
	}

	/**
	 * Create <meta> image tags for social sharing
	 *
	 * @param string $url The image URL
	 *
	 * @return string
	 **/
	public function image($url)
	{
		$this->meta('og:image', $url, false, 'property');
		$this->meta('twitter:image',$url, false);
	}

	/**
	 * Create a <meta> open graph HTML head tag.
	 *
	 * @param string  $name   The og: tag name
	 * @param string  $value  The value to return
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function openGraph($name, $value, $escape = false)
	{
		$name = 'og:'.$name;

		$this->meta($name,$value,$escape,'property');
	}

	/**
	 * Create a <meta> twitter HTML head tag.
	 *
	 * @param string  $name   The twitter: tag name
	 * @param string  $value  The tag value
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function twitter($name, $value, $escape = false)
	{
		$name = 'twitter:'.$name;

		$this->meta($name,$value,$escape);
	}

	/**
	 * Create a <meta> validation tag for things like Google Webmaster Tools.
	 * 
	 *
	 * @param string  $name   The tag name
	 * @param string  $value  The tag value
	 * @param boolean $escape True or false to escape or not
	 *
	 * @return string
	 **/
	public function validate($name, $value, $escape = false)
	{
		$this->meta($name,$value,$escape);
	}

	public function rel()
	{
		
	}
}