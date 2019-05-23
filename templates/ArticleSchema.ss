<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "NewsArticle",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://google.com/article"
    },
    "headline": "$Title",
    "datePublished": "$SchemaPublishDate",
    "dateModified": "$SchemaLastEditedDate",
    "description": "$SchemaSummary",
    "author": {
        "@type": "Person",
        "name": "$Authors.First.FirstName $Authors.First.Surname"
    },
    "publisher": {
        "@type": "Organization",
        "name": "$SiteConfig.SchemaOrganisationName",
        "logo": {
            "@type": "ImageObject",
            "url": "$SiteConfig.SchemaOrganisationImage.AbsoluteURL",
            "width": $SiteConfig.SchemaOrganisationImage.Width,
            "height": $SiteConfig.SchemaOrganisationImage.Height
        }
    },
    "image": [
        "$FeaturedImage.AbsoluteURL"
    ]
}
</script>