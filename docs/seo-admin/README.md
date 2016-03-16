# SEO Admin

## Customising the SEO Admin models

By default the grid display 2 tabs, the first shows all site pages, the second shows error pages only.
If you have a model or page that that has the SEO extension attached and you would like to display it in its own tab you can.

In your config.yml add some objects to the SEOAdmin models config.

```yml
SEOAdmin:
  models:
    - MyPageClass
    - MyObject
```

Segmenting your different page types and objects here allows you to keep track of your meta easier across a large set of pages.

## SERP preview

## Field breakdown

Next: [Sitemap](../sitemap)