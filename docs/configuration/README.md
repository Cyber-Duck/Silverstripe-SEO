# Configuration

## Generating the Page Meta

To generate the Page / DataObject Meta tags add the following include within the <head> of your page.

```html
<head>
    $PageMetaTags
</head>
```

## Page SEO Extension

By default the SeoPageExtension is applied to the Page object. An SEO and Sitemap tab will be available within the CMS on each Page.

## Adding the SEO Extension to a DataObject

DataObjects that wish to act as pages need the SeoExtension applied to them.

```yml
MyObject:
  extensions:
    - CyberDuck\SEO\Model\Extension\SeoExtension
```

This extension is the same as the SeoPageExtension apart from it contains a few extra db fields.

  - Title
  - URLSegment
  - MetaDescription

If you apply the SeoExtension to a DataObject and wish to auto generate the Meta from it you need to set it as the SEO page object in your controller:

```php

class MyPage_Controller extends PageController
{
    public function init()
    {
        parent::init();

        $page = MyObject::get_by_id(1);

        $this->setSeoObject($page);
    }
}
``` 