<?php
/**
 * SEO_FieldValuesTest
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_FieldValuesTest extends FunctionalTest
{
	public function testObjectInstance()
	{
        $obj = new SEO_FieldValues();

        $this->assertInstanceOf('SEO_FieldValues', $obj);
	}

	public function testArrayGet()
	{
		$this->assertInternalType('array', SEO_FieldValues::SitemapChangeFrequency());
		$this->assertInternalType('array', SEO_FieldValues::IndexRules());
		$this->assertInternalType('array', SEO_FieldValues::OGlocale());
		$this->assertInternalType('array', SEO_FieldValues::OGtype());
		$this->assertInternalType('array', SEO_FieldValues::TwitterCardTypes());
		$this->assertInternalType('array', SEO_FieldValues::YesNo());
		$this->assertInternalType('array', SEO_FieldValues::SummaryFields());
		$this->assertInternalType('array', SEO_FieldValues::SearchableFields());
	}
}