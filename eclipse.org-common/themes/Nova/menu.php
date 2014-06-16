<?php
/*******************************************************************************
 * Copyright (c) 2008-2012 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
	global $App;
	$www_prefix = "";
	$pageRSS = "";

	if(isset($App)) {
		$www_prefix = $App->getWWWPrefix();

		if(isset($App->PageRSS)) {
			if($App->PageRSS != "") {
				$pageRSS = $App->PageRSS;
			}
		}
	}

	ob_start();
	for($i = 0; $i < $Menu->getMenuItemCount(); $i++) {
		$MenuItem = $Menu->getMenuItemAt($i);

		?>
		<li><a href="<?= $MenuItem->getURL(); ?>" target="<?= $MenuItem->getTarget(); ?>"><?= $MenuItem->getText(); ?></a></li>
	<?php }
	$menuHTML = ob_get_clean();
	?>
<div id="header">
	<div id="menu"><ul><?=$menuHTML;?></ul></div>
	<div id="search">
		<form action="//www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s">
		<fieldset><input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
		<input id="searchBox" type="text" name="q" size="25" />
		<input id="searchButton" type="submit" name="sa" value="Search" />
		</fieldset></form>
		<script type="text/javascript" src="//www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>
	</div>
</div>
	<?php if ($Nav == NULL) { ?>
	<div id="novaContent">
	<?php }
	else { ?>
	<div id="novaContent" class="faux"><br id="faux-br" style="clear:both;height:1em;"/>
	<?php } ?>
	<?php if ($App->OutDated == TRUE) {?>
		<div class="message-box-container">
			<div class="message-box error">This page is deprecated and may contain some information that is no longer relevant or accurate.</div>
		</div>
	<?php } ?>