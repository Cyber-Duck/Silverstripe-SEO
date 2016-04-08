<?php

/**
 * Create dynamic Meta
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/

class SEO_DynamicMeta {

	private $text;

	private $object;

	private $seperator;

	private $placeholders;

	/**
     * Set a dynamic Meta tag populated with an object properties
     *
     * @since version 1.0
     *
     * @param string $text   Meta text with placeholders [Value]
     * @param object $object The object to use
     * @param string $seperator    Separator to use before the last value when using multiple values
     *
     * @return string Returns text with the placeholders replaced with object properties
     **/
	function __construct($text, $object, $seperator)
	{
		$this->text = $text;
		$this->object = $object;
		$this->seperator = $seperator;
	}

    public function create()
    {
    	$object = $this->object;

        if(!is_object($object)) return;

        foreach($this->placeholders() as $value){
            // check for relation placeholders with a .
            if(strpos($value,".") !== false){
                $relations = explode('.',$value);

                // get the relation name
                $many = $relations[0];

                // get the relation property name
                $property = $relations[1];

                // loop the relation and assign the necessary property to an array
                if($object->hasMany($many) || $object->manyMany($many)){
                    $values = array();
                    foreach($object->$many() as $one){
                        $values[] = trim($one->$property);
                    }
                    $last = array_pop($values);
                    $first = implode(', ',$values);

                    // if only one property use it otherwise add the "and" separator
                    if($first == NULL){
                        $result = $last;
                    } else {
                        $result = array();
                        $result[] = $first;
                        $result[] = ', '.$this->seperator.' ';
                        $result[] = $last;
                        $result = implode($result);
                    }
                } else {
                    user_error('Invalid relations in dynamic SEO tag');
                }
                
            } else {
                $result = trim($object->$value);
            }
            // replace the placeholder with the new value
            $this->text = trim(str_replace('['.$value.']', htmlspecialchars($result), $this->text));
        }
        return $this->text;
    }

    private function placeHolders()
    {
    	preg_match_all("/\[([^\]]*)\]/", $this->text, $matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }
}