<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

<% loop $SitemapPages %>

<url>
    
    <loc>{$AbsoluteBaseHref}$Link</loc>
    <lastmod>$LastEdited.Format('c')</lastmod>
    <changefreq>$ChangeFrequency</changefreq>
    <priority>$Priority</priority>

    <% loop $SitemapImages %>
    
    <image:image>
        <image:loc>$Up.Host/{$Filename}</image:loc>
        <image:title>$Top.Encode($Title)</image:title>
    </image:image>

    <% end_loop %>

</url>

<% end_loop %>

</urlset>