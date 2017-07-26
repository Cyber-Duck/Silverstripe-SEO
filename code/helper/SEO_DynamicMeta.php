<?php
/**
 * SEO_DynamicMeta
 *
 * Hlper class for creating dynamic Meta strings using placeholders and object properties
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_DynamicMeta
{
    /**
     * A string of Meta text
     *
     * @since version 1.0.0
     *
     * @var string $text 
     **/
	private $text;

    /**
     * An object with the SEO extension attached
     *
     * @since version 1.0.0
     *
     * @var DataObject $object 
     **/
	private $object;

    /**
     * Seperator string between looped relations
     *
     * @since version 1.0.0
     *
     * @var string $seperator 
     **/
	private $seperator;

	/**
     * Set a dynamic Meta tag populated with an object properties
     *
     * @param string     $text      Meta text with placeholders [Value]
     * @param DataObject $object    The object to use
     * @param string     $seperator Separator to use before the last value when using multiple values
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	function __construct($text, DataObject $object, $seperator = 'and')
	{
		$this->text = $text;
		$this->object = $object;
		$this->seperator = $seperator;
	}

    /**
     * Replace the Meta text placeholders with object properties
     *
     * @since version 1.0.0
     *
     * @return string
     **/
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
                    $values = [];
                    foreach($object->$many() as $one){
                        $values[] = trim($one->$property);
                    }
                    $last = array_pop($values);
                    $first = implode(', ',$values);

                    // if only one property use it otherwise add the "and" separator
                    if($first == NULL){
                        $result = $last;
                    } else {
                        $result = [];
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

    /**
     * This method uses regex to capture any [placeholders]
     *
     * @since version 1.0.0
     *
     * @return array
     **/
    private function placeHolders()
    {
    	preg_match_all("/\[([^\]]*)\]/", $this->text, $matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }
}