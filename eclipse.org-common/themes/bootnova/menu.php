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
	$pageRSS = "";

	$image_protocol = "http";

	if(isset($_SERVER['HTTPS'])) {
		if($_SERVER['HTTPS']) {
			$image_protocol = "https";
		}
	}
	$logo = '<img src="' . $theme_url . 'assets/images/eclipse.png" alt="Eclipse.org"/>';
	ob_start();
	for($i = 0; $i < $Menu->getMenuItemCount(); $i++) {
		$MenuItem = $Menu->getMenuItemAt($i);?>
		<li><a href="<?php print $MenuItem->getURL(); ?>" target="<?php print $MenuItem->getTarget(); ?>"><?php print $MenuItem->getText(); ?></a></li>
	<?php }
	$menuHTML = ob_get_clean();
	?>

	<div id="main-navbar" class="navbar navbar-inverse navbar-fixed-top navbar-default hidden-sm hidden-md hidden-lg">
    <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php //print $logo;?>Eclipse Foundation</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          	<?php print $menuHTML;?>
          	<li class="dropdown">
    					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Visit other Eclipse Sites<span class="caret"></span></a>
    					<ul class="dropdown-menu">
    						<li><a href="http://marketplace.eclipse.org">Eclipse Marketplace</a></li>
								<li><a href="http://live.eclipse.org">Eclipse Live</a></li>
								<li><a href="https://bugs.eclipse.org/bugs/">Bugzilla</a></li>
								<li><a href="http://www.eclipse.org/forums/">Eclipse Forums</a></li>
								<li><a href="http://www.planeteclipse.org/">Planet Eclipse</a></li>
								<li><a href="http://wiki.eclipse.org/">Eclipse Wiki</a></li>
								<li><a href="http://portal.eclipse.org">My Foundation Portal</a></li>
    					</ul>
  					</li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

  <div class="container">
  	<div class="row hidden-xs">
			<div id="logo" class="col-sm-4">
			  <?php
					if ($App->Promotion == FALSE) {
						print '<a href="http://www.eclipse.org/">' . $logo . '</a>';
					} else {
						if ($App->CustomPromotionPath != "") {
							include($App->CustomPromotionPath);
						} else {
							include($App->getPromotionPath($theme));
						}
					}
				?>
			</div>

			<div class="col-sm-8 hidden-xs" id="other-eclipse">
				<ul id="other-eclipse-sites" class="list-inline pull-right">
					<li><a href="http://marketplace.eclipse.org"><img alt="Eclipse Marketplace" src="<?php print $image_protocol;?>://dev.eclipse.org/custom_icons/marketplace.png"/>&nbsp;<span>Eclipse Marketplace</span></a></li>
					<li><a href="http://live.eclipse.org"><img alt="Eclipse Live" src="<?php print $image_protocol;?>://dev.eclipse.org/custom_icons/audio-input-microphone-bw.png"/>&nbsp;<span>Eclipse Live</span></a></li>
					<li><a href="https://bugs.eclipse.org/bugs/"><img alt="Bugzilla" src="<?php print $image_protocol;?>://dev.eclipse.org/custom_icons/system-search-bw.png"/>&nbsp;<span>Bugzilla</span></a></li>
					<li><a href="http://www.eclipse.org/forums/"><img alt="Forums" src="<?php print $image_protocol;?>://dev.eclipse.org/large_icons/apps/internet-group-chat.png"/>&nbsp;<span>Eclipse Forums</span></a></li>
					<li><a href="http://www.planeteclipse.org/"><img alt="Planet Eclipse" src="<?php print $image_protocol;?>://dev.eclipse.org/large_icons/devices/audio-card.png"/>&nbsp;<span>Planet Eclipse</span></a></li>
					<li><a href="http://wiki.eclipse.org/"><img alt="Eclipse Wiki" src="<?php print $image_protocol;?>://dev.eclipse.org/custom_icons/accessories-text-editor-bw.png"/>&nbsp;<span>Eclipse Wiki</span></a></li>
					<li><a href="http://portal.eclipse.org"><img alt="MyFoundation Portal" src="<?php print $image_protocol;?>://dev.eclipse.org/custom_icons/preferences-system-network-proxy-bw.png"/><span>My Foundation Portal</span></a></li>
				</ul>
			</div>
    </div>

		<div id="header" class="row hidden-xs">
			<div id="menu" class="col-md-8"><ul class="list-inline"><?php print $menuHTML;?></ul></div>
			<div id="search" class="col-md-4 hidden-sm">
				<form action="http://www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s" role="form" class="form-inline">
					<fieldset class="form-group">
						<input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
						<input id="search-box" type="text" name="q" size="25" class="form-control"/>
						<input id="search-button" type="submit" name="sa" value="Search" class="btn btn-default"/>
					</fieldset>
				</form>
				<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>
			</div>
		</div>

		<div id="main-content-container-row" class="main-content-area row<?php if ($Nav != NULL) : print ' visible-leftnavbg'; endif;?>">
			<?php if ($App->OutDated == TRUE) {?>
				<div class="message-box-container">
					<div class="message-box error">This page is deprecated and may contain some information that is no longer relevant or accurate.</div>
				</div>
			<?php } ?>