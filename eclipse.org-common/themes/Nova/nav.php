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
 *    gbarbier mia-software com - bug 284239
 *******************************************************************************/
?>
<div id="leftcol">
<ul id="leftnav">
<?php
    $level = 0;
	for($i = 0; $i < $Nav->getLinkCount(); $i++) {
		$Link = $Nav->getLinkAt($i);
		if( $Link->getURL() == "" ) {
			if($Link->getTarget() == "__SEPARATOR") {
			   
			   ?><li class="separator"><a class="separator"><?php
			   ?><?= $Link->getText() ?><img src="/eclipse.org-common/themes/Nova/images/separator.png" alt="navigation image"/></a></li>
<?php
			} else {
				?><li><a class="nolink" href="#"><?= $Link->getText() ?></a></li>
<?php
			}
		} elseif (stripos($Link->getURL(), 'project_summary.php') !== FALSE) { 
				?><li class="about"><a href="<?= $Link->getURL() ?>"><?=$Link->getText();?></a></li> <?
		} else {
			if($Link->getTarget() == "__SEPARATOR") {
				?><li class="separator"><a class="separator" href="<?= $Link->getURL() ?>">
				<?= $Link->getText() ?><img src="/eclipse.org-common/themes/Nova/images/separator.png" alt="navigation image"/></a></li>
<?php
			} else {
				?><li><a href="<?= $Link->getURL() ?>" target="<?= ($Link->getTarget() == "_blank") ? "_blank" : "_self" ?>"><?= $Link->getText() ?></a></li>
<?php
			}
		}
		
	}
	?>
</ul>
<? echo $Nav->getHTMLBlock(); ?>
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
