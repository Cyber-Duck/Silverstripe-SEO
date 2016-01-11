<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
	<% loop $Pages %>
	<url>
		<loc>$AbsoluteLink</loc>
		<lastmod>$LastEdited</lastmod>
		<changefreq>$ChangeFrequency</changefreq>
		<priority>$Priority</priority>
	</url>
	<% end_loop %>
</urlset>