# Sitemap

## XML Sitemap Config

You can configure each Page / DataObject Sitemap entry through the CMS. A new tab is added called Sitemap with the following settings:

  - Hide in Sitemap - Will hide in XML and HTML sitemap
  - Priority - 0.0 to 1.0
  - Change Frequency - Daily, monthly etc

## XML Sitemap

After installing the module you can visit /sitemap.xml to see the generated XML sitemap. By default the sitemap will be populated with all your Page objects.

## Using DataObjects as XML Sitemap Pages

If you have applied the SeoExtension to a DataObject you can easily include these in your XML Sitemap.
These Objects REQUIRE a Link() method to return the DataObject Page URL.
If you are generating a HTML sitemap you also need to include the parent ID to correctly nest within the generated <ul> HTML list.

```yml
CyberDuck\SEO\Generators\SitemapGenerator:
  objects:
    MyObject: 
      parent_id: 14
```

## XML Sitemap Images

Within the CMS Page / DataObject Sitemap tab you can add images that will be automatically populated into your XMl sitemap under the respective entry.

## HTML Sitemap Generation

You can access the sitemap HTML by calling the sitemap generator and referencing the output in your template.
In your Sitemap Page controller:

```php
use CyberDuck\SEO\Generators\SitemapGenerator;

class SitemapPage_Controller extends PageController
{
    public function getSitemap()
    {
        $generator = new SitemapGenerator();
        return $generator->getSitemapHTML();
    }
}
```

In your template:

```html
$Sitemap.RAW
```