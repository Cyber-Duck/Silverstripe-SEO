<?php
/**
 * SEO_FieldValues
 *
 * Returns array values used in the SEO admin search and CMS form field dropdowns
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_FieldValues
{
    /**
     * Returns an array of sitemap change frequencies used in a sitemap.xml file
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of change frequency values
     **/
    public static function SitemapChangeFrequency()
    {
        return [
            'always'  => 'Always',
            'hourly'  => 'Hourly',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
            'never'   => 'Never'
        ];
    }
    
    /**
     * Returns an array of robots crawling rules used in a robots Meta tag
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of robots index rule values
     **/
    public static function IndexRules()
    {
        return [
            'index,follow'     => 'index,follow',
            'noindex,nofollow' => 'noindex,nofollow',
            'noindex,follow'   => 'noindex,follow',
            'index,nofollow'   => 'index,nofollow'
        ];
    }
    
    /**
     * Return an array of Facebook Open Graph locales
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of open graph locale values
     **/
    public static function OGlocale()
    {
        return [
            'en_GB' => 'English - United Kingdom',
            'en_US' => 'English - United States',
            'da_DK' => 'Danish - Denmark',
            'nl_NL' => 'Dutch - Netherlands',
            'fr_FR' => 'French - France',
            'de_DE' => 'German - Germany',
            'el_GR' => 'Greek - Greece',
            'hu_HU' => 'Hungarian - Hungary',
            'is_IS' => 'Icelandic - Iceland',
            'id_ID' => 'Indonesian - Indonesia',
            'it_IT' => 'Italian - Italy',
            'ja_JP' => 'Japanese - Japan',
            'ko_KR' => 'Korean - Korea',
            'lv_LV' => 'Latvian - Latvia',
            'lt_LT' => 'Lithuanian - Lithuania',
            'mk_MK' => 'Macedonian - Macedonia',
            'no_NO' => 'Norwegian - Norway',
            'fa_IN' => 'Persian - India',
            'fa_IR' => 'Persian - Iran',
            'pl_PL' => 'Polish - Poland',
            'pt_PT' => 'Portuguese - Portugal',
            'ro_RO' => 'Romanian - Romania',
            'ru_RU' => 'Russian - Russia',
            'sk_SK' => 'Slovak - Slovakia',
            'sl_SI' => 'Slovenian - Slovenia',
            'es_ES' => 'Spanish - Spain',
            'sv_SE' => 'Swedish - Sweden',
            'tr_TR' => 'Turkish - Turkey',
            'uk_UA' => 'Ukrainian - Ukraine',
            'vi_VN' => 'Vietnamese - Vietnam'
        ];
    }
    
    /**
     * Return an array of Facebook Open Graph Types
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of open graph type values
     **/
    public static function OGtype()
    {
        return [
            'website' => 'Website',
            'article' => 'Article',
            'book'    => 'Book',
            'profile' => 'Profile',
            'music'   => 'Music',
            'video'   => 'Video'
        ];
    }
    
    /**
     * Returns an array of Twitter card types
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of twitter card type values
     **/
    public static function TwitterCardTypes()
    {
        return [
            'summary'             => 'Summary',
            'summary_large_image' => 'Summary Large Image',
            'photo'               => 'Photo',
            'gallery'             => 'Gallery',
            'app'                 => 'App',
            'product'             => 'Product'
        ];
    }
    
    /**
     * Returns an array of robots crawling rules used in a robots Meta tag
     *
     * @since version 1.0.0
     *
     * @return array Returns an array of robots index rule values
     **/
    public static function YesNo()
    {
        return [
            '1' => 'Yes',
            '0' => 'No'
        ];
    }
    
    /**
     * Returns an array of summary fields used in the SEO Admin section of the CMS
     *
     * @since version 1.0.2
     *
     * @return array Returns an array of SEO admin summary fields
     **/
    public static function SummaryFields()
    {
        return [
            'GridMetaTitle'        => 'T',
            'GridMetaDescription'  => 'D',
            'GridSocial'           => 'S',
            'MetaTitle'            => 'Title',
            'MetaDescription'      => 'Description',
            'Robots'               => 'Robots',
            'Priority'             => 'Priority',
            'ChangeFrequency'      => 'Change Freq'
        ];
    }
    
    /**
     * Returns an array of searchable fields used in the SEO Admin section of the CMS
     *
     * @since version 1.0.2
     *
     * @return array Returns an array of SEO Admin searchable fields
     **/
    public static function SearchableFields()
    {
        return [
            'Title' => [
                'title'  => 'Title:',
                'field'  => 'TextField',
                'filter' => 'PartialMatchFilter'
            ],
            'URLSegment' => [
                'title'  => 'URL segment:',
                'field'  => 'TextField',
                'filter' => 'PartialMatchFilter'
            ],
            'Robots' => [
                'title'  => 'Robots:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ],
            'ChangeFrequency' => [
                'title'  => 'Change frequency:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ],
            'HideSocial' => [
                'title'  => 'Social Meta:',
                'field'  => 'DropdownField',
                'filter' => 'ExactMatchFilter'
            ]
        ];
    }
}