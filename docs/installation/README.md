# Installation

## Composer

Add the following to your composer.json file

```json
{  
    "require": {  
        "cyber-duck/silverstripe-seo": "v1.1"
    },  
    "repositories": [  
        {  
            "type": "vcs",  
            "url": "https://github.com/cyber-duck/silverstripe-seo"  
        }  
    ]  
}
```

## Extension

In your config.yml file add the SEO extension to your Page object

```yml
Page:
  extensions:
    - SEO_Extension
```

## Controller

Next add the SEO::init() function to your Page Controller init function and also add the MetaTags method to the Page Controller.

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

Run dev/build and flush to initialise the module

Next: [Setting Meta](../setting-meta)