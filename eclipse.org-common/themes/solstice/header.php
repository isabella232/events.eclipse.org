<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

include_once 'app.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow, noimageindex" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="<?php print $variables['page']['author']; ?>" />
    <meta name="keywords" content="<?php print $variables['page']['keywords']; ?>" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,700,300,600,100" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="<?php print $variables['theme_url'];?>public/images/favicon.ico"/>
    <title><?php print $variables['page']['title']; ?></title>
    <?php print $variables['head']['og_title']?>
    <?php print $variables['head']['og_description'];?>
    <?php print $variables['head']['og_image'];?>

    <link rel="stylesheet" href="<?php print $variables['theme_url'];?>public/stylesheets/styles.min.css">

    <?php print $variables['page']['extra_headers'];?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="<?php print $variables['theme_variables']['body_classes'];?>" id="<?php print $variables['body']['id'];?>">
	  <a class="sr-only" href="#content">Skip to main content</a>
