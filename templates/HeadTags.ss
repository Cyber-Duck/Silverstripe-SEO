<% with $PageSEO %>

<title>$MetaTitle</title>
<meta name="description" content="$MetaDescription">

<link rel="canonical" href="$Canonical">
<meta name="robots" content="$Robots">

<% if $ShowSocial %>

<meta property="og:title" content="$MetaTitle">
<meta property="og:description" content="$MetaDescription">
<meta property="og:type" content="$OGtype">
<meta property="og:url" content="$Canonical">
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
<% end_if %>
<% if $SiteConfig.TwitterHandle %>
<meta name="twitter:site" content="@$SiteConfig.TwitterHandle">
<% end_if %>
<% if $SiteConfig.TwitterCreator %>
<meta name="twitter:creator" content="@$SiteConfig.TwitterHandle">
<% end_if %>

<% end_if %>

<% end_with %>