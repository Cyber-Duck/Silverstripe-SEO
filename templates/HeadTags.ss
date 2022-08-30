<% with SeoPageObject %>

    <title>$PageMetaTitle</title>
    <meta name="description" content="$PageMetaDescription">

    <% if $PageCanonical %>
        <link rel="canonical" href="$PageCanonical">
    <% end_if %>
    <meta name="robots" content="$PageRobots">

    <% if not $HideSocial %>

        <meta property="og:title" content="$PageMetaTitle">
        <meta property="og:description" content="$PageMetaDescription">
        <meta property="og:type" content="$PageOgType">
        <meta property="og:url" content="$PageURL">
        <meta property="og:locale" content="$PageOgLocale">

        <meta name="twitter:title" content="$PageMetaTitle">
        <meta name="twitter:description" content="$PageMetaDescription">
        <meta name="twitter:card" content="$PageTwitterCard">
        <% if $PageSocialImage %>
            <meta property="og:image" content="$PageSocialImage.AbsoluteURL">
            <meta name="twitter:image" content="$PageSocialImage.AbsoluteURL">
        <% else_if $DefaultPageSocialImage %>
            <meta property="og:image" content="$DefaultPageSocialImage.AbsoluteURL">
            <meta name="twitter:image" content="$DefaultPageSocialImage.AbsoluteURL">
        <% end_if %>

        <% if $SiteFacebookAppID %>
            <meta property="fb:app_id" content="$SiteFacebookAppID">
        <% end_if %>

        <% if $SiteOgSiteName %>
            <meta property="og:site_name" content="$SiteOgSiteName">
        <% end_if %>

        <% if $SiteTwitterHandle %>
            <meta name="twitter:site" content="$SiteTwitterHandle">
        <% end_if %>

        <% if $SiteCreatorTwitterHandle %>
            <meta name="twitter:creator" content="$SiteCreatorTwitterHandle">
        <% end_if %>

    <% end_if %>

    <% loop HeadTags.Filter('Type', 'name') %>
        <meta name="$Title" content="$Value">
    <% end_loop %>

    <% loop HeadTags.Filter('Type', 'link') %>
        <link rel="$Title" href="$value">
    <% end_loop %>

    <% loop HeadTags.Filter('Type', 'property') %>
        <meta property="$Title" content="$Value">
    <% end_loop %>

    <% if PageGenerator %>
        <meta name="generator" content="$PageGenerator">
    <% end_if %>

    <meta http-equiv="Content-type" content="text/html; charset=$PageCharset">

    <% if isCMSPreviewPage %>
        <meta name="x-page-id" content="$CMSPageID">
        <meta name="x-cms-edit-link" content="$CMSPageEditLink">
    <% end_if %>

    <% if PaginationPrevTag %>
        <link rel="prev" href="$PaginationPrevTag">
    <% end_if %>
    <% if PaginationNextTag %>
        <link rel="next" href="$PaginationNextTag">
    <% end_if %>

<% end_with %>
