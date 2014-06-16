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

function solstice_variables(&$variables) {
	$App = $variables['page']['App'];

	$base_url = $App->getWWWPrefix() . '/';
	$Nav =  $variables['page']['Nav'];
	$Menu =  $variables['page']['Menu'];
	$Breadcrumb = $variables['page']['Breadcrumb'];
	$Session = $App->useSession();

	$variables['session'] = array(
		'Friend' => NULL,
		'name' => '',
		'last_name' => '',
	);

	$variables['url'] = $base_url;

	$variables['session']['Friend'] = $Session->getFriend();
	$variables['session']['create_account_link'] = '<a href="https://dev.eclipse.org/site_login/createaccount.php"><i class="fa fa-user fa-fw"></i> Create account</a>';
	$variables['session']['my_account_link'] = '<a href="https://dev.eclipse.org/site_login/?takemeback=' . $App->getWWWPrefix() . $_SERVER['REQUEST_URI'] . '"><i class="fa fa-sign-in fa-fw"></i> Sign in</a>';

	if ($Session->getGID() > 0) {
		$variables['session']['name'] = $variables['session']['Friend']->getFirstName();
		$variables['session']['last_name'] = $variables['session']['Friend']->getLastName();
		$variables['session']['create_account_link'] = 'Welcome, ' . $variables['session']['name'] . ' ' . $variables['session']['last_name'];
		$variables['session']['my_account_link'] = '<a href="https://dev.eclipse.org/site_login/myaccount.php" class=""><i class="fa fa-edit fa-fw"></i> Edit my account</a>';
	}

	// Breadcrumbs
	$crumb_list = $Breadcrumb->getCrumbList();

	// fetch key of the last element of the array.
	$crumb_last_key = $Breadcrumb->getCrumbCount()-1;

	$variables['breadcrumbs'] = '<ol class="breadcrumb">';
	$request_uri = explode('/', $_SERVER['REQUEST_URI']);

	foreach ($crumb_list as $k => $v) {
		// add .active class to the last item of the breadcrumbs
		if($k == $crumb_last_key) {
			if (count($request_uri) >= 3 && ($request_uri[2] != "" && $request_uri[2] != "index.php")) {
				$variables['breadcrumbs'] .= '<li class="active">' . $v->getText() . '</li>';
			}
		}
		else {
			$variables['breadcrumbs'] .= '<li><a href="' . $v->getURL() . '">' . $v->getText() . '</a></li>';
		}
	}
	$variables['breadcrumbs'] .= "</ol>";

	// If the main menu is custom, do not change it
	$NewMenu = new $Menu();
	$main_menu = $Menu->getMenuArray();
	if ($NewMenu->getMenuArray() == $main_menu) {
		$Menu = new $Menu();
		$Menu->setMenuItemList(array());
		$Menu->addMenuItem("Getting Started ", $variables['url'] . "users/", "_self");
		$Menu->addMenuItem("Members", $variables['url'] . "membership/", "_self");
		$Menu->addMenuItem("Projects", $variables['url'] . "projects/", "_self");
		$main_menu = $Menu->getMenuArray();
	}

	$theme = $variables['page']['theme'];
	$variables['theme_variables'] = $App->getThemeVariables();

	if (!empty($variables['theme_variables']['breadcrumbs_html'])) {
		$variables['theme_variables']['breadcrumbs_classes'] = 'large-breadcrumbs hidden-print';
	} else {
		$variables['theme_variables']['breadcrumbs_classes'] = 'defaut-breadcrumbs hidden-print';
	}

	$classes = array();
	$deprecated = "";
	$items = array();

	$variables['theme_url'] = '/eclipse.org-common/themes/solstice/';

	$variables['page']['extra_js_footer'] = $App->ExtraJSFooter;

	// HTML headers
	$variables['head']['og_title'] = $App->getOGTitle();
	$variables['head']['og_description'] = $App->getOGDescription();
	$variables['head']['og_image'] = $App->getOGImage();

	// Deprecated message
	if ($App->getOutDated()) {
		$classes[] =  "deprecated";
		$deprecated = '<div class="message-box-container">';
		$deprecated .= '<div class="message-box error">This page is deprecated and may contain some information that is no longer relevant or accurate.</div>';
		$deprecated .= '</div>';
	}
	$variables['deprecated'] =  $deprecated;

	// Body
	$variables['body']['classes'] = implode($classes, ' ');
	$variables['body']['id'] = 'body_solstice';

	// Logos
	$variables['logo']['default'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-800x188.png" alt="Eclipse.org logo" class="logo-eclipse-default img-responsive"/>';
	$variables['logo']['white'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-logo-bw-800x188.png" alt="Eclipse.org black and white logo" width="166" height="39" id="logo-eclipse-white"/>';
	$variables['logo']['mobile'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-800x188.png" alt="Eclipse.org logo" width="174" class="logo-eclipse-default"/>';

	// Main-menu
	foreach ($main_menu as $item) {
		$items[] = '<li><a href="' . $item->getURL() .'" target="' . $item->getTarget() .'">' . $item->getText() . '</a></li>';
	}

	$variables['menu']['main_menu'] = implode($items, '');

	$variables['menu']['more'] = array();

	$variables['menu']['more']['Community'][] = array(
		'url' => 'http://marketplace.eclipse.org',
		'caption' => 'Marketplace'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => 'http://events.eclipse.org',
		'caption' => 'Events'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => '//www.planeteclipse.org/',
		'caption' => 'Planet Eclipse'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => $variables['url'] . 'community/eclipse_newsletter/',
		'caption' => 'Newsletter'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => 'https://www.youtube.com/user/EclipseFdn',
		'caption' => 'Videos'
	);

	$variables['menu']['more']['Participate'][] = array(
		'url' => 'https://bugs.eclipse.org/bugs/',
		'caption' => 'Report a Bug'
	);

	$variables['menu']['more']['Participate'][] = array(
		'url' => $variables['url'] . 'forums/',
		'caption' => 'Forums'
	);

	$variables['menu']['more']['Participate'][] = array(
		'url' => $variables['url'] . 'mail/',
		'caption' => 'Mailing Lists'
	);

	$variables['menu']['more']['Participate'][] = array(
		'url' => 'https://wiki.eclipse.org/',
		'caption' => 'Wiki'
  );

	$variables['menu']['more']['Participate'][] = array(
		'url' => 'https://wiki.eclipse.org/IRC',
		'caption' => 'IRC'
	);

	$variables['menu']['more']['Participate'][] = array(
		'url' => $variables['url'] . 'contribute/',
		'caption' => 'How to Contribute'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://wiki.eclipse.org/Auto_IWG',
		'caption' => 'Automotive'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://iot.eclipse.org',
		'caption' => 'Internet of Things'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://locationtech.org',
		'caption' => 'LocationTech'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://lts.eclipse.org',
		'caption' => 'Long-Term Support'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://polarsys.org',
		'caption' => 'PolarSys'
	);

	$variables['menu']['more']['Working Groups'][] = array(
		'url' => 'http://science.eclipse.org',
		'caption' => 'Science'
	);

	$variables['menu']['mobile_more'] = "";
	$variables['menu']['desktop_more'] = '';
	foreach ($variables['menu']['more'] as $key => $value) {
		$first = TRUE;
		foreach ($value as $link){
			if ($first) {
				$first = FALSE;
				$variables['menu']['desktop_more'] .= '<ul class="col-sm-8 list-unstyled"><li><p><strong>' . $key . '</strong></p></li>';
				$variables['menu']['mobile_more'] .= '<li class="dropdown visible-xs"><a href="#" data-toggle="dropdown" class="dropdown-toggle">' . $key . ' <b class="caret"></b></a><ul class="dropdown-menu">';
			}
			$l = '<li><a href="' . $link['url'] . '">' . $link['caption'] . '</a></li>';
			$variables['menu']['desktop_more'] .= $l;
			$variables['menu']['mobile_more'] .= $l;
		}
		$variables['menu']['mobile_more'] .= '</ul></li>';
		$variables['menu']['desktop_more'] .= '</ul>';
	}

	// Nav menu
	if ($Nav != NULL) {
		// add faux class to #novaContent
		$variables['theme_variables']['main_container_classes'] .= " background-image-none";

		$variables['menu']['nav']['link_count'] = $Nav->getLinkCount();
		$variables['menu']['nav']['img_separator'] = '<img src="' . $variables['theme_url'] . 'public/images/template/separator.png"/>';

		for ($i = 0; $i < $variables['menu']['nav']['link_count']; $i++) {
			$variables['menu']['nav']['#items'][] = $Nav->getLinkAt($i);
		}
	}

	// Ads and promotions
	ob_start();
	if ($App->Promotion == FALSE) {
		print '<a href="' . $variables['url'] . '">' . $variables['logo']['default'] . '</a>';
	} else {
		if ($App->CustomPromotionPath != "") {
			include($App->CustomPromotionPath);
		} else {
			include($App->getPromotionPath($theme));
		}
	}

	$variables['promotion'] = ob_get_clean();

	// Always show the eclipse logo for release day
	$variables['promotion'] = '<a href="' . $variables['url'] . '">' . $variables['logo']['default'] . '</a>';
	$variables['logo_mobile'] =  '<a href="' . $variables['url'] . '" class="navbar-brand visible-xs">' . $variables['logo']['mobile'] . '</a>';

	$variables['uri'] = parse_url($_SERVER['REQUEST_URI']);

	$variables['footer']['copyright'] = 'Copyright &copy; ' . date("Y") . ' The Eclipse Foundation. All Rights Reserved.';
}

global $App;
$variables = array();
$variables['page']['App'] = $App;
$variables['page']['author'] = $pageAuthor;
$variables['page']['keywords'] = $pageKeywords;
$variables['page']['title'] = $pageTitle;
$variables['page']['theme'] = $theme;
$variables['page']['Nav'] = $Nav;
$variables['page']['Menu'] = $Menu;
$variables['page']['html'] = $html;
$variables['page']['Breadcrumb'] = $Breadcrumb;
$variables['page']['extra_headers'] = (isset($extraHtmlHeaders)) ? $extraHtmlHeaders : "";
solstice_variables($variables);
