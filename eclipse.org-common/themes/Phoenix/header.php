<? 
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
 
	$www_prefix = "";
		
	global $App;

	if(isset($App)) {
		$www_prefix = $App->getWWWPrefix();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title><?= $pageTitle ?></title><meta name="author" content="<?= $pageAuthor ?>" />
<meta name="keywords" content="<?= $pageKeywords ?>" /><link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/small.css" title="small" /><link rel="alternate stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/large.css" title="large" /><link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/visual.css" media="screen" /><link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/layout.css" media="screen" />
<!--[if IE]> 	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/ie_style.css" media="screen"/> <![endif]-->
<!--[if IE 6]> 	<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/ie6_style.css" media="screen"/> <![endif]-->
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/print.css" media="print" />
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/header.css" media="screen" />
<script type="text/javascript" src="/eclipse.org-common/themes/Phoenix/styleswitcher.js"></script>
<?php if( isset($extraHtmlHeaders) ) echo $extraHtmlHeaders; ?></head>
<body>
<div id="header">
	<div id="header-graphic" class="eclipse-main">
		<a href="/"><img src="/eclipse.org-common/themes/Phoenix/images/eclipse_home_header.jpg" alt="" /></a><h1>Eclipse</h1>	
	</div>
	<div id="header-global-holder" class="eclipse-main-global">
		<div id="header-global-links"><ul>
<li><a href="/org/foundation/contact.php" class="first_one">Contact</a></li><li><a href="/legal/">Legal</a></li>
			</ul>
		</div>
		<div id="header-icons">
<a href="http://www.youtube.com/user/EclipseFdn" target="_blank"><img src="/eclipse.org-common/themes/Phoenix/images/Icon_Live.png" width="28" height="28" alt="Eclipse YouTube Channel" title="Eclipse YouTube Channel" /></a>
<a href="http://marketplace.eclipse.org"><img src="/eclipse.org-common/themes/Phoenix/images/Icon_plugin.png" width="28" height="28" alt="Eclipse Marketplace" title="Eclipse Marketplace" /></a>
<a href="http://www.planeteclipse.org"><img src="/eclipse.org-common/themes/Phoenix/images/Icon_planet.png" width="28" height="28" alt="Planet Eclipse" title="Planet Eclipse" /></a>
		</div>
	</div></div>