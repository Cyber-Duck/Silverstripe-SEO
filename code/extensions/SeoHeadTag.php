<?php

class SeoMetaTag extends DataObject {

	private static $db = array(
		'Name'      => 'Varchar(512)',
		'Attribute' => 'Varchar(512)',
		'Value'     => 'Varchar(512)',
		'Type'      => 'Varchar(512)'
	);

    private static $default_sort = 'Name';

    private static $singular_name = 'Meta Tag';
    
    private static $plural_name = 'Meta Tags';

    public function getCMSFields() 
    {
        $fields = parent::getCMSFields();

        return $fields;
    }
}