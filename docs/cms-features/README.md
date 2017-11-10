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

## Site Config Settings

A few new fields will be added to the CMS settings. These are site wide configuration settings.

  - OG Site Name - Used in Open Graph Meta
  - Twitter Handle - @{VALUE} used in Twitter Meta
  - Twitter Creator Handle - @{VALUE} used in Twitter Meta
  - Facebook App ID - Used in Facebook Meta
  - Use Title as Meta Title - Will use the Page title in the Meta <title></title> tag by default

## Google SERP Preview

Within the SEO tab you can find the Google SERP preview field. This allows you to construct Meta while visually seeing how it will look on Google search results.