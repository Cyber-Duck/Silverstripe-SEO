# Meta

## Meta Field List

The SEO Extension attaches a number of fields to your object.
  - MetaTitle - Page Meta title
  - Canonical - Page URL canonical tag
  - Robots - index,follow etc crawling rules
  - Priority - XML Sitemap priority
  - ChangeFrequency - XML Sitemap change frequency
  - SitemapHide - Hide in HTML & XML Sitemap
  - HideSocial - Hide social Meta
  - OGtype - Open graph type
  - OGlocale - Open graph locale
  - TwitterCard -  Summary etc, used in social sharing

## Default Meta Configuration

Some of these fields above have sensible default values applied.

  - Robots - index,follow
  - Priority - 0.50
  - ChangeFrequency - weekly
  - OGtype - website
  - TwitterCard - summary
  - OGlocale - Uses default locale from application

## Overriding Meta

You can override the methods in SEO extension by creating a new method in a sub class.

```php
class MyMemberPage_Controller extends PageController
{
    public function getPageMetaTitle()
    {
        return 'New Meta Title';
    }
}
```

## Dynamic Meta Generation

You can dynamically generate Meta from an object and string with place holders.

```php
use CyberDuck\SEO\Generators\DynamicMetaGenerator;

class MyMemberPage_Controller extends PageController
{
    public function getPageMetaTitle()
    {
        $member = MyObject::get_by_id(1);

        $generator = new DynamicMetaGenerator('[FirstName] [Surname] - My Member', $member);
        return $generator->create();
    }
}
```

Outputs:

```
Andrew Mc Cormack - My Member
```

The above example will override the Meta title with the custom generated one. The place holders in brackets reference the passed object properties.
You can also reference and loop out relation properties with the dot syntax and add a separator for the last value.

```php
use CyberDuck\SEO\Generators\DynamicMetaGenerator;

class MyMemberPage_Controller extends PageController
{
    public function getPageMetaTitle()
    {
        $member = MyObject::get_by_id(1);

        $generator = new DynamicMetaGenerator('[FirstName] [Surname] is a member of the team and specialises in [Areas.Name].', $member, '&');
        return $generator->create();
    }
}
```

Outputs:

```
Andrew Mc Cormack is a member of the team and specialises in FirstArea, SecondArea, ThirdArea, & FourthArea
```

## Setting Pagination Meta Tags

Setting pagination tags is as easy as passing the PaginatedList object into a method. The next and prev tags will be calculated from it.

```php
class MyBlogPage_Controller extends PageController
{
    public function init()
    {
        parent::init();

        $this->setPaginationTags($this->BlogPosts);
    }
}
```

## Adding Extra Head Tags

Within the CMS SEO tab there is a gridfield to adding extra Meta tags to a Page / DataObject.