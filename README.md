# Silverstripe SEO
A Silverstripe module to optimise the Meta, crawling, indexing, and sharing of your website content

Author: Andrew Mc Cormack

## Installation

Add the following to your composer.json file

```json
{  
    "require": {  
        "Andrew-Mc-Cormack/Silverstripe-SEO": "dev-master"
    },  
    "repositories": [  
        {  
            "type": "vcs",  
            "url": "https://github.com/Andrew-Mc-Cormack/Silverstripe-SEO"  
        }  
    ]  
}
```

After you composer update / install the module the extra meta fields will be available in the CMS within a page under the SEO tab.

Next add the SEO::init() function to your Page Controller init function.
And also add the MetaTags method to the Page Controller.

```php
class Page_Controller extends ContentController {

    public function init()
    {
        parent::init();

        SEO::init();
    }

    public function MetaTags()
    {
        return SEO::HeadTags();
    }
}
```

This will complete the setup of the SEO module and make the module functionality available throughout your site.

The URL ```/sitemap.xml``` will now also respond and generate your XML sitemap

## Features

- Subsite aware

### CMS features
  - SERP Preview
  - Meta Title, Description, Canonical, Robots, Open Graph, Twitter fields
  - Page social sharing image
  - Sitemap priority and change frequency fields
  - Extra Meta Grid Field (Create link, property, or Meta head tags)
  - Dynamic placeholder meta

### Sitemap
  - XML sitemap generator
  - HTML sitemap generator

## Usage

### Setting the current page Meta

By default the page Meta will be generated off the current Page object. If you wish to have an object as a Page and render out its Meta attach the SEO extension to it.

In your config.yml file add an entry for an object you wish to have as a page.

```yml
MyObject:
  extensions:
    - SEOExtension
```

Within the current controller get your object and pass it into the setPage function;

```php
$page = MyObject::get()->First();

SEO::setPage($page);
```

If you look at the HTML meta tags within your current page you will see they will be populated with the tags from your object record.

### Setting the page URL

By default the canonical and pagination meta tags will use the current page protocol, domain, and path (no query string) for their URL. If you wish to use a custom URL on the current page you can set one.

```php
SEO::setPageURL('http://www.cyber-duck.co.uk/catalogue');
```

### Setting Pagination Meta

To add rel="prev" and rel="next" Meta to a page just pass in the total number of items in the entire data set (the total of all pages items), you can use the SilverStripe Count function.

```php
$list = MyObject::get();

SEO::pagination($list->Count());
```

The pagination method accepts 3 parameters, total (required), per page(default: 12), and page param (default: start)

You can use custom values by passing them as arguments.

```php
$list = MyObject::get();

SEO::pagination($list->Count(), 20, 'page');
```

## About Me
My name is Andy and I work for [Cyber Duck](https://www.cyber-duck.co.uk/)

## License

    Copyright (c) 2015, Andrew Mc Cormack <andrewm@cyber-duck.co.uk>.
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:

        * Redistributions of source code must retain the above copyright
          notice, this list of conditions and the following disclaimer.

        * Redistributions in binary form must reproduce the above copyright
          notice, this list of conditions and the following disclaimer in
          the documentation and/or other materials provided with the
          distribution.

    Neither the name of Andrew Mc Cormack nor the names of his
    contributors may be used to endorse or promote products derived
    from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
    LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
    FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
    INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
    BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
    LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
    ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.