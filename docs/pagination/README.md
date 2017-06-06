# Pagination

## Setting Rel pagination Meta tags

To add rel="prev" and rel="next" Meta to a page just pass in the total number of items in the paginated page collection.
You can use the SilverStripe Count function.

```php
$list = MyObject::get();

SEO::setPagination($list->Count());
```

The pagination method accepts 3 parameters, the total (required), items per page (default: 12), and pagination URL parameter (default: start)

You can use custom values by passing them as arguments.

```php
$list = MyObject::get();

SEO::setPagination($list->Count(), 20, 'page');
```

## Whitelisting URL parameters

If for some reason you are using URL GET parameters to generate unique content and not filter or sort it, you can use the allowedParams method to whitelist parameters and their values for inclusion in pagination URLs.

```php
SEO::setPagination($list->Count())->allowedParams(['first','third']);
```

If we were on the following page in the browser.

```
https://www.cyber-duck.co.uk/catalogue?page=12&start=1&second=2&third=3
```

The pagination URL the would be generated would be as follows.

```
<link rel="next" href="https://www.cyber-duck.co.uk/catalogue?start=24&first=1&third=3">
```

Next: [SEO Admin](../seo-admin)