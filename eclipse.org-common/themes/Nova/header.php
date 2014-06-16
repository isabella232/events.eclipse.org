<?php
/*******************************************************************************
 * Copyright (c) 2008-2013 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *     Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

$www_prefix = "";
global $App;
if(isset($App)) {
	$www_prefix = $App->getWWWPrefix();
}

print $this->getDoctype();
?>
<head>
	<title><?= $pageTitle ?></title><meta name="author" content="<?= $pageAuthor ?>" />
	<?php
	if ($App->OGTitle != "") {
		echo($App->getOGTitle());
	}
	echo($App->getOGDescription());
	echo($App->getOGImage());
	?>
	<meta name="keywords" content="<?= $pageKeywords ?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/yui/2.6.0/build/reset-fonts-grids/reset-fonts-grids.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/yui/2.6.0/build/menu/assets/skins/sam/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/reset.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/layout.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/header.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/footer.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/visual.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/print.css" media="print" />
	<!--[if lte IE 7]> 	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/ie_style.css" media="screen"/> <![endif]-->
	<!--[if IE 6]> 	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Nova/css/ie6_style.css" media="screen"/> <![endif]-->
	<!-- Dependencies -->
	<!-- Source File -->
<?php if($App->getjQuery()) echo $App->getjQuery(); ?>
<?php if( isset($extraHtmlHeaders) ) echo $extraHtmlHeaders; ?>
</head>
<body>
	<div id="novaWrapper"<?php if ($App->OutDated == TRUE) print ' class="deprecated"';?>><?//This Div is closed in footer.php?>
		<div id="clearHeader">
			<div id="logo">
				<?php if ($App->Promotion == FALSE) { ?>
					 <img src="/eclipse.org-common/themes/Nova/images/eclipse-800x426.png" alt="Eclipse.org" width="171" height="91"/>
				<?php } else {
						if ($App->CustomPromotionPath != "") {
							include($App->CustomPromotionPath);
						}
						else {
							include($App->getPromotionPath($theme));
						}
					}
				?>
			</div>
<div id="otherSites"><div id="sites"><ul id="sitesUL">
<li><a href='//marketplace.eclipse.org'><img alt="Eclipse Marketplace" src="//dev.eclipse.org/custom_icons/marketplace.png"/>&nbsp;<span>Eclipse Marketplace</span></a></li>
<li><a href='//www.youtube.com/user/EclipseFdn' target="_blank"><img alt="Eclipse YouTube Channel" src="//dev.eclipse.org/custom_icons/audio-input-microphone-bw.png"/>&nbsp;<span>Eclipse YouTube Channel</span></a></li>
<li><a href='https://bugs.eclipse.org/bugs/'><img alt="Bugzilla" src="//dev.eclipse.org/custom_icons/system-search-bw.png"/>&nbsp;<span>Bugzilla</span></a></li>
<li><a href='//www.eclipse.org/forums/'><img alt="Forums" src="//dev.eclipse.org/large_icons/apps/internet-group-chat.png"/>&nbsp;<span>Eclipse Forums</span></a></li>
<li><a href='//www.planeteclipse.org/'><img alt="Planet Eclipse" src="//dev.eclipse.org/large_icons/devices/audio-card.png"/>&nbsp;<span>Planet Eclipse</span></a></li>
<li><a href='//wiki.eclipse.org/'><img alt="Eclipse Wiki" src="//dev.eclipse.org/custom_icons/accessories-text-editor-bw.png"/>&nbsp;<span>Eclipse Wiki</span></a></li>
<li><a href='https://portal.eclipse.org'><img alt="MyFoundation Portal" src="//dev.eclipse.org/custom_icons/preferences-system-network-proxy-bw.png"/><span>My Foundation Portal</span></a></li>
</ul></div></div></div>