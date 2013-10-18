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
?>
	</div> <!-- /#bootnovacontent -->
</div> <!-- /container -->
<div class="container">
	<footer class="clearfix row">
		<div class="col-md-7">
			<ul id="footernav" class="list-inline">
				<li><a href="<?php print $App->getWWWPrefix() ?>/">Home</a></li>
				<li><a href="<?php print $App->getWWWPrefix() ?>/legal/privacy.php">Privacy Policy</a></li>
				<li><a href="<?php print $App->getWWWPrefix() ?>/legal/termsofuse.php">Terms of Use</a></li>
				<li><a href="<?php print $App->getWWWPrefix() ?>/legal/copyright.php">Copyright Agent</a></li>
				<li><a href="<?php print $App->getWWWPrefix() ?>/legal/">Legal</a></li>
				<li><a href="<?php print $App->getWWWPrefix() ?>/org/foundation/contact.php">Contact Us</a></li>
			</ul>
		</div>
		<div class="col-md-5">
    	<p id="copyright">Copyright &copy; <?php print date("Y");?> The Eclipse Foundation. All Rights Reserved.</p>
    </div>
	</footer>
</div>

	<!--jQuery -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php print $theme_url;?>components/jquery/jquery.js"><\/script>')</script>

	<!--Bootstrap -->
	<script src="<?php print $theme_url;?>components/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
