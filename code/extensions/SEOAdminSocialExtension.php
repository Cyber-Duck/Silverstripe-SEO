<?php

class SEOAdminExtension extends DataExtension {

	private static $db = array(
		'FacebookURL'           => 'Varchar(512)',
		'FBappID'               => 'Varchar(512)',
		'TwitterURL'            => 'Varchar(512)',
		'TwitterHandle'         => 'Varchar(512)',
		'GooglePlusURL'         => 'Varchar(512)',
		'PinterestURL'          => 'Varchar(512)',
		'InstagramURL'          => 'Varchar(512)',
		'SoundcloudURL'         => 'Varchar(512)',
		'YoutubeURL'            => 'Varchar(512)'
		);

	public static $has_one = array(
		'SocialImage'          => 'Image'
	);

	public function updateCMSFields(FieldList $fields)
	{
		$fields->addFieldToTab('Root.SocialMedia', HeaderField::create('Social Settings'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('FacebookURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('FBappID','Facebook App ID'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('TwitterURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('TwitterHandle'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('GooglePlusURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('PinterestURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('SoundcloudURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('InstagramURL'));
		$fields->addFieldToTab('Root.SocialMedia', TextField::create('YoutubeURL'));

		return $fields;
	}
}