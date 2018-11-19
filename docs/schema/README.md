# Schema

This module allows you to add schema.org JSON to your webpage and auto generates schema for Blog Posts

## Displaying Schema on Your Page

Add a PageSchema template variable to your page (preferably before the closing body tag)

```html
    $PageSchema
</body>
```

## Auto Generated BlogPost Schema

The below snippet is auto generated form your BlogPost data and some site config
settings

```javascript
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "NewsArticle",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://google.com/article"
    },
    "headline": "Article headline",
    "datePublished": "2015-02-05T08:00:00+08:00",
    "dateModified": "2015-02-05T09:20:00+08:00",
    "description": "A most wonderful article",
    "author": {
        "@type": "Person",
        "name": "John Doe"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Google",
        "logo": {
            "@type": "ImageObject",
            "url": "https://google.com/logo.jpg",
            "width": 100,
            "height": 100
        }
    },
    "image": [
        "https://example.com/photos/1x1/photo.jpg"
    ]
}
</script>
```

In your CMS settings add your organisation name and image to populate the data:

publisher.name
publisher.logo.url
publisher.logo.width
publisher.logo.height

The following data is pulled from the actual BlogPost:

headline - Title
datePublished - PublishDate
dateModified - LastEdited
description - Summary
author.name - First Author FirstName and Surname
image - FeaturedImage.URL

## Manually Adding Schema

Every page apart from Blog Posts have a Schema tab with a textarea field where you can add custom schema to that page.