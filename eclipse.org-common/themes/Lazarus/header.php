<?
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation) - initial API and implementation
 * 	  Matt Chapman (contributor) - modification and skin (bug 144911)
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
<meta name="keywords" content="<?= $pageKeywords ?>" />
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Lazarus/css/small.css" title="small" />
<link rel="alternate stylesheet" type="text/css" href="/eclipse.org-common/themes/Lazarus/css/large.css" title="large" />
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Lazarus/css/visual.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Lazarus/css/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/Phoenix/css/print.css" media="print" />
<script type="text/javascript" src="/eclipse.org-common/themes/Phoenix/styleswitcher.js"></script>
	<?php if( isset($extraHtmlHeaders) ) echo $extraHtmlHeaders; ?>
</head>
<body>
<div id="header">
</div>
<div id="headerlogo">
	<a href="/"><img src="/eclipse.org-common/themes/Lazarus/images/header_logo.gif" alt="Eclipse Logo" class="logo" border="0" height="75" width="241" /></a>
</div>