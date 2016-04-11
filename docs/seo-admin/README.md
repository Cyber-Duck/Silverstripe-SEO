# SEO Admin

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