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
?>
<div id="leftcol">
<ul id="leftnav">
<?php
    $level = 0;
	for($i = 0; $i < $Nav->getLinkCount(); $i++) {
		$Link = $Nav->getLinkAt($i);
		$newlevel = $Link->getLevel();
		if( $Link->getURL() == "" ) {
			if($Link->getTarget() == "__SEPARATOR") {
			   
			   ?><li class="separator"><a class="separator"><?php
			   	  for($j = 0; $j < (($newlevel - 1) * 4); $j++ ) { ?>&nbsp;<?php } 
			   ?><?= $Link->getText() ?> &#160;&#160;<img src="/eclipse.org-common/themes/Phoenix/images/leftnav_bullet_down.gif" border="0" alt="" /></a></li>
<?php
			} else {
				?><li><a class="nolink" href="#"><?php
			   	  for($j = 0; $j < (($newlevel - 1) * 4); $j++ ) { ?>&nbsp;<?php } 
			   ?><?= $Link->getText() ?></a></li>
<?php
			}
		} else {
			if($Link->getTarget() == "__SEPARATOR") {
				?><li class="separator"><a class="separator" href="<?= $Link->getURL() ?>"><?php
			   	  for($j = 0; $j < (($newlevel - 1) * 4); $j++ ) { ?>&nbsp;<?php } 
			   ?><?= $Link->getText() ?> &#160;&#160;<img src="/eclipse.org-common/themes/Phoenix/images/leftnav_bullet_down.gif" border="0" alt="" /></a></li>
<?php
			} else {
				?><li><a href="<?= $Link->getURL() ?>"><?php
			   	  for($j = 0; $j < (($newlevel - 1) * 4); $j++ ) { ?>&nbsp;<?php } 
			   ?><?= $Link->getText() ?></a></li>
<?php
			}
		}
	}
	?>
<li style="background-image: url(/eclipse.org-common/themes/Phoenix/images/leftnav_fade.jpg); background-repeat: repeat-x; border-style: none;"><br /><br /><br /><br /><br /><br /><br /><br />
</li>
</ul>
<? /*
	<br />
	<div class="sideitem">
		<h6>Did you know?</h6>
		<p>We may be adding neat tips and tricks, and other stuff here.</p>
		<ul>
			<li>Who writes them?</li>
			<li>Do we moderate them?</li>
			<li>Should the style of this box be identical to the boxes on the right?</li>
		</ul>
		<p>
			<a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=117903">go comment on quips! &raquo;</a>
		</p>
		<br />
	</div>
*/  ?>

</div>
<div id="container">