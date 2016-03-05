<% with $PageSEO %>

<title>$Up.MetaTitle</title>
<meta name="description" content="$Up.MetaDescription">

<link rel="canonical" href="<% if $Canonical %>$Canonical<% else %>$Up.PageURL<% end_if %>">
<meta name="robots" content="<% if $Robots %>$Robots<% else %>index,follow<% end_if %>">

<% if not $HideSocial %>

<meta property="og:title" content="$Up.MetaTitle">
<meta property="og:description" content="$Up.MetaDescription">
<meta property="og:type" content="<% if $OGtype %>$OGtype<% else %>website<% end_if %>">
<meta property="og:url" content="$Up.PageURL">
<meta property="og:locale" content='<% if $OGtype %>$OGtype<% else %>en_GB<% end_if %>'>

<meta name="twitter:title" content="$Up.MetaTitle">
<meta name="twitter:description" content="$Up.MetaDescription">
<meta name="twitter:card" content="<% if $TwitterCard %>$TwitterCard<% else %>summary<% end_if %>">

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