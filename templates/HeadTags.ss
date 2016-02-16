<% with $PageSEO %>

<title>$MetaTitle</title>
<meta name="description" content="$MetaDescription">

<% if $Canonical %>
<link rel="canonical" href="$Canonical">
<% else %>
<link rel="canonical" href="$Up.PageURL">
<% end_if %>
<meta name="robots" content="$Robots">

<% if not $HideSocial %>

<meta property="og:title" content="$MetaTitle">
<meta property="og:description" content="$MetaDescription">
<meta property="og:type" content="$OGtype">
<meta property="og:url" content="$Up.PageURL">
<meta property="og:locale" content='$OGlocale'>

<meta name="twitter:title" content="$MetaTitle">
<meta name="twitter:description" content="$MetaDescription">
<meta name="twitter:card" content="$TwitterCard">

<% if $SocialImage %>
<meta property="og:image" content="$SocialImage">
<meta name="twitter:image" content="$SocialImage">
<% end_if %>

<% if $SiteConfig.FacebookAppID %>
<meta property="fb:app_id" content="$SiteConfig.FacebookAppID">
<% end_if %>

<% if $SiteConfig.OGSiteName %>
<meta property="og:site_name" content="$SiteConfig.OGSiteName">
<% else %>
<meta property="og:site_name" content="$SiteConfig.Title">
<% end_if %>

<% if $SiteConfig.TwitterHandle %>
<meta name="twitter:site" content="@$SiteConfig.TwitterHandle">
<meta name="twitter:creator" content="@$SiteConfig.TwitterHandle">
<% end_if %>

<% end_if %>

<% end_with %>

$Pagination

$OtherTags