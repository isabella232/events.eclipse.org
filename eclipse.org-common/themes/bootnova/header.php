<?php
/*******************************************************************************
 * Copyright (c) 2013 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation of bootnova
 *******************************************************************************/

global $App;
$theme_url = '/eclipse.org-common/themes/bootnova/';

?>
<!DOCTYPE html>
<html>
	<head>
  	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="<?php print $pageAuthor; ?>" />
    <meta name="keywords" content="<?= $pageKeywords ?>" />
    <title><?php print $pageTitle; ?></title>

    <?php
    	if ($App->OGTitle != "") : print $App->getOGTitle(); endif;
    	print($App->getOGDescription());
    	print($App->getOGImage());
    ?>

    <link rel="stylesheet" href="<?php print $theme_url;?>assets/css/bootstrap.min.css">

    <!--jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php print $theme_url;?>components/jquery/jquery.js"><\/script>')</script>

    <!--Bootstrap -->
    <script src="<?php print $theme_url;?>components/bootstrap/js/bootstrap.min.js"></script>

    <?php if( isset($extraHtmlHeaders) ) print $extraHtmlHeaders; ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    	<script src="<?php print $theme_url;?>assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
<body <?php if ($App->OutDated == TRUE) print ' class="deprecated"';?>>
