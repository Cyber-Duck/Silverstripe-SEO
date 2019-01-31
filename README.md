# SilverStripe 4 SEO

[![Latest Stable Version](https://poser.pugx.org/cyber-duck/silverstripe-seo/v/stable)](https://packagist.org/packages/cyber-duck/silverstripe-seo)
[![Latest Unstable Version](https://poser.pugx.org/cyber-duck/silverstripe-seo/v/unstable)](https://packagist.org/packages/cyber-duck/silverstripe-seo)
[![Total Downloads](https://poser.pugx.org/cyber-duck/silverstripe-seo/downloads)](https://packagist.org/packages/cyber-duck/silverstripe-seo)
[![License](https://poser.pugx.org/cyber-duck/silverstripe-seo/license)](https://packagist.org/packages/cyber-duck/silverstripe-seo)

Author: [Andrew Mc Cormack](https://github.com/Andrew-Mc-Cormack)

## Features

A SilverStripe module to enhance and optimize your website SEO. 
  - SEO Extension for Pages and DataObjects
  - Meta Title, Description, Twitter, Facebook (OG Graph), Canonical, Robots Meta
  - Ability to add other Meta tags
  - Pagination Meta tags for page sets
  - Dynamic Meta generation from Model properties
  - Auto generated Blog Post schema
  - Free text field for Page schema
  - CMS Google SERP Meta preview
  - SEO CMS Admin area
  - SEO CMS Settings configuration
  - Robots.txt controller and auto generation
  - XML Sitemap controller and auto generation
  - Ability to attach images to XML Sitemap pages
  - Nested HTML Sitemap generator
  - Blog module SEO extension

## Installations

SilverStripe 4.0 and 4.1 and over require different versions of this module because of the different public folder structure. Please see the following 2 methods.

### SilverStripe 4.1 installation

Add the following to your composer.json file and run /dev/build?flush=all

```json
{  
    "require": {  
        "cyber-duck/silverstripe-seo": "4.2.*"
    }
}
```

### SilverStripe 4.0 installation

Add the following to your composer.json file and run /dev/build?flush=all

```json
{  
    "require": {  
        "cyber-duck/silverstripe-seo": "4.1.*"
    }
}
```

## Setup

  - [Configuration](/docs/configuration)
  - [Meta](/docs/meta)
  - [Schema](/docs/schema)
  - [Sitemap](/docs/sitemap)
  - [Robots](/docs/robots)
  - [Blog Configuration](/docs/blog-configuration)
  - [CMS Features](/docs/cms-features)

## Todo

  - Automap Sitemap Priority