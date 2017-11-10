# Robots

## Default Robots.txt

By default the module contains a Robots.txt template that will render a robots file when you visit /robots.txt

```html
User-agent: *

Sitemap https://{YOUR_SITE}/sitemap.xml
```

## Creating a Robots.txt File

If you would like to override the file; create a template called RobotsTxt.ss and place it within your /themes/{YOUR_THEME}/templates/ folder.

