# Meta

## Setting Meta 

You can change the current page Meta title and description tag values very easily. This can come in handy across certain unique pages throughout your site.

```php
SEO::setTitle("Meta title");
SEO::setDescription("Meta description");
```

If you are looking to set Meta based off an objects values look at setting dynamic Meta.

## Setting Dynamic Meta 

You can use an objects properties to populate a dynamic Meta title or description tag using placeholders [].

The setDynamicTitle and setDynamicDescription functions take 3 arguments, the Meta text (required), the object (required), and the separator (default: and).

Lets assume we have a member object. We can use the properties from it to populate matching placeholders.

```php
$member = Member::currentUser();

SEO::setDynamicTitle("[FirstName] [Surname] - Site Member", $member);
```

You can also access relations using the dot syntax. If a member had a has_many relation to an Areas object and it had a class property Name we could access it as below.

```php
SEO::setDynamicDescription(
"[FirstName] [Surname] is a member of the team and specialises in [Areas.Name].", $member);
```

```
Andrew Mc Cormack is a member of the team and specialises in FirstArea, SecondArea, ThirdArea, and FourthArea
```

Relations are looped with separators (, ) and with an "and" before the last entry although you can use another separator if you want, & for example

```php
SEO::setDynamicDescription(
"[FirstName] [Surname] is a member of the team and specialises in [Areas.Name].", $member, '&');
```

```
Andrew Mc Cormack is a member of the team and specialises in FirstArea, SecondArea, ThirdArea, & FourthArea
```

## Setting Meta from objects

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

## Setting the Meta tags URL

By default the canonical, pagination meta tags, and social meta will use the current page protocol, domain, and path (no query string) for their URL. 

```
https://www.cyber-duck.co.uk/catalogue;
```

If you wish to use a custom URL across these various tags you can set one.

```php
SEO::setPageURL('https://www.cyber-duck.co.uk/catalogue2');
```

Next: [Pagination](../pagination)