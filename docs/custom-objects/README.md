# Custom objects

## Using an object as a page

By default the page Meta will be generated off the current Page object. If you wish to have an object as a Page and render out its Meta attach the SEO extension to it.

In your config.yml file add an entry for every object you wish to have as a page.

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

If you look at the HTML meta tags within your current page you will see they will be populated with the tags from your object record. All the fields which are available in the CMS will populate the current page Meta.

## Setting the page URL

By default the canonical, pagination meta tags, and social meta will use the current page protocol, domain, and path (no query string) for their URL. 

```
https://www.cyber-duck.co.uk/catalogue;
```

If you wish to use a custom URL across these various tags you can set one.

```php
SEO::setPageURL('https://www.cyber-duck.co.uk/catalogue2');
```

Next: [SEO Admin](../seo-admin)