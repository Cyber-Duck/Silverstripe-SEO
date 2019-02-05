# CMS Features

## SEO Admin

After installing the module a new CMS section will be created called SEO with a gridfield detailing essential Meta.
If you would like to customise the Tabs and models shown use the YML config.

```yml
CyberDuck\SEO\Admin\SEOAdmin:
  managed_models:
    - MyPageClass
    - MyObject
```

The SEO Admin section has modifed field settings which exports all major and minor SEO fields for the CSV export.
The list includes the following:

  - Created
  - ID
  - Class Name
  - Title
  - URL Segment
  - Meta Title
  - Meta Description
  - Canonical
  - Robots
  - Priority
  - Change Frequency
  - Sitemap Hide
  - Hide Social
  - OG Type
  - OG Locale
  - Twitter Card
  - Social Image
  - Head Tags
  - Sitemap Images 

## Site Config Settings

A few new fields will be added to the CMS settings. These are site wide configuration settings.

  - Default Meta title to page title when Meta title empty? - Will use the Page title in the Meta <title></title> tag by default
  - OG Site Name - Used in Open Graph Meta
  - Twitter Handle - @{VALUE} used in Twitter Meta
  - Twitter Creator Handle - @{VALUE} used in Twitter Meta
  - Facebook App ID - Used in Facebook Meta
  - Default Social Image - Used in og:image and twitter:image meta when social image not set on page / model

## Google SERP Preview

Within the SEO tab you can find the Google SERP preview field. This allows you to construct Meta while visually seeing how it will look on Google search results.