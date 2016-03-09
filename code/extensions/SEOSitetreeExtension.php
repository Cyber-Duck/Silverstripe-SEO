<?php

/**
 * Creates an SEO admin panel
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEOSitetreeExtension extends DataExtension {

	private static $default_sort = 'Created';

	private $summary_fields = array(
		'ID'                   => 'ID',
		'GridCreated'          => 'Created',
		'GridTitle'            => 'Title',
		'Robots'               => 'Robots',
		'Priority'             => 'Priority',
		'ChangeFrequency'      => 'Change Freq',
		'GridMetaTitle'        => 'T',
		'GridMetaDescription'  => 'D',
		'GridSocial'           => 'Social'
	);

	private static $searchable_fields = array(
        'Robots' => array(
            'title'  => 'Robots:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter',
            'options' => array('1' => '1')
        ),
        'ChangeFrequency' => array(
            'title'  => 'Change frequency:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter'
        ),
        'HideSocial' => array(
            'title'  => 'Social Meta:',
            'field'  => 'DropdownField',
            'filter' => 'ExactMatchFilter'
        )
    );

	public function updateSummaryFields(&$fields)
	{
		Config::inst()->update($this->owner->class, 'summary_fields', $this->summary_fields);

        $fields = Config::inst()->get($this->owner->class, 'summary_fields');
    }

    public function GridCreated()
    {
    	return date('dS M Y', strtotime($this->owner->Created));
    }

    public function GridTitle()
    {
    	$meta = HTMLText::create();
        $meta->setValue('<span class="seo-pagename">'.$this->owner->Title.'</span>');
        return $meta;
    }

    public function GridMetaTitle($color = 'false')
    {
    	$color = $this->owner->MetaTitle != NULL ? 'true' : 'false';

    	$meta = HTMLText::create();
        $meta->setValue('<span class="seo-light '.$color.'"></span>');
        return $meta;
    }

    public function GridMetaDescription($color = 'false')
    {
    	$color = $this->owner->MetaDescription != NULL ? 'true' : 'false';

    	$meta = HTMLText::create();
        $meta->setValue('<span class="seo-light '.$color.'"></span>');
        return $meta;
    }

    public function GridSocial()
    {
    	$checked = $this->owner->HideSocial == 1 ? 0 : 1;

    	return CheckboxField::create('Done')->setValue($checked)->performReadonlyTransformation();
    }
}