<% with $Page %>
<url>
	
	<loc>$Up.URL{$Link}</loc>
	<lastmod>$LastEdited</lastmod>
	<changefreq>$ChangeFrequency</changefreq>
	<priority>$Priority</priority>

	<% loop $SitemapImages %>
	
    <image:image>
        <image:loc>$Top.URL/{$Filename}</image:loc>
        <image:title>$Top.Encode($Title)</image:title>
        <% if $Caption %>
        <image:caption>$Top.Encode($Caption)</image:caption>
        <% end_if %>
    </image:image>

	<% end_loop %>

</url>
<% end_with %>