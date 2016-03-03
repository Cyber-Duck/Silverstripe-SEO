# Silverstripe SEO
A Silverstripe module to optimise the Meta, crawling, indexing, and sharing of your website content

Author: Andrew Mc Cormack

## Features

# CMS fields (for pages and objects)
  - Meta Title
  - Meta Description
  - Canonical
  - Robots
  - Open graph and twitter meta
  - Page image

  - Sitemap priority
  - Sitemap change frequency

# Features
  - Extra Meta Grid Field (Create link, property, or Meta head tags)
  - SERP Preview
  - Dynamic placeholder meta
  - Subsite aware
  - XML sitemap generator
  - HTML sitemap generator

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

After you composer update / install the module the extra meta fields will be available in the CMS within a page under the SEO tab.

The URL /sitemap.xml will now also respond and generate your XML sitemap

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