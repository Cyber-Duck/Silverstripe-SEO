# SEO Admin

![Silverstripe SEO admin gridfield](/docs/images/seo-admin.jpg "Silverstripe SEO admin gridfield")

Manage groups of specific pages or objects and get a quick overview of the SEO status of your objects. By default it shows all your pages. Columns include:
  - Title
  - Description
  - Social meta
  - Created
  - Title
  - Robots rules
  - Priority
  - Change frequency

## Customising the SEO Admin models

By default the grid display 2 tabs, the first shows all site pages, the second shows error pages only, and is ordered by page priority descending.

The following fields are shown:
  - The page created date
  - The page title
  - Robots crawling rules
  - XML sitemap page priority
  - XML sitemap change frequency
  - (T) Meta title status
  - (D) Meta description status
  - (S) Whether social meta is enabled

If you have a model or page that that has the SEO extension attached and you would like to display it in its own tab you can.
In your config.yml add some objects to the SEOAdmin models config.

```yml
SEOAdmin:
  models:
    - MyPageClass
    - MyObject
```

Segmenting your different page types and objects here allows you to keep track of your Meta easier across a large set of pages.

Next: [Sitemap](../sitemap)