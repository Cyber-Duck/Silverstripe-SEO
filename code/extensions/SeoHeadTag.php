<?php

class SeoMetaTag extends DataObject {

    private static $db = array(
        'Name'      => 'Varchar(512)',
        'Value'     => 'Varchar(512)',
        'Type'      => 'Varchar(512)'
    );

    private static $has_one = array(
        'Page'      => 'Page',
    );

    private static $summary_fields = array(
        'Name'      => 'Name',
        'Value'     => 'Value',
        'Type'      => 'Type'
    );

    private static $default_sort = 'Name';

    private static $singular_name = 'Meta Tag';
    
    private static $plural_name = 'Meta Tags';

    public function getCMSFields() 
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Main');

        $fields->addFieldsToTab('Root.SEO', array(
            HeaderField::create('Meta Tag'),
            DropdownField::create('Type','Tag type',$this->tagTypes()),
            TextField::create('Name'),
            TextField::create('Value'),
            HiddenField::create('PageID')
        ));

        return $fields;
    }

    private function tagTypes()
    {
        return array(
            'name'     => '<meta name="name" content="value">',
            'property' => '<meta property="name" content="value">',
        );
    }
}