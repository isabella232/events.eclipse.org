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
?>
		  </div>
		</main> <!-- /#main-content-container-row -->
    <p id="back-to-top">
      <a class="visible-xs" href="#top">Back to the top</a>
    </p>
		<footer role="contentinfo">

		  <div class="container">

        <div class="row">
          <section id="footer-eclipse-foundation" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">
            <h2 class="section-title">Eclipse Foundation</h2>
				    <ul class="nav">
				      <li><a href="<?php print $variables['url']; ?>org/">About us</a></li>
				      <li><a href="<?php print $variables['url']; ?>org/foundation/contact.php">Contact Us</a></li>
				      <li><a href="<?php print $variables['url']; ?>donate">Donate</a></li>
				      <li><a href="<?php print $variables['url']; ?>org/documents/">Governance</a></li>
				      <li><a href="<?php print $variables['url']; ?>artwork/">Logo and Artwork</a></li>
              <li><a href="<?php print $variables['url']; ?>org/foundation/directors.php">Board of Directors</a></li>
				    </ul>
			    </section>
          <section id="footer-legal" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print ">
            <h2 class="section-title">Legal</h2>
				    <ul class="nav">
					    <li><a href="<?php print $variables['url']; ?>legal/privacy.php">Privacy Policy</a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/termsofuse.php">Terms of Use</a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/copyright.php">Copyright Agent</a></li>
				    	<li><a href="<?php print $variables['url']; ?>org/documents/epl-v10.php">Eclipse Public License </a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/">Legal Resources </a></li>

				    </ul>
			    </section>

			    <section id="footer-useful-links" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">
            <h2 class="section-title">Useful Links</h2>
				    <ul class="nav">
				      <li><a href="https://bugs.eclipse.org/bugs/">Report a Bug</a></li>
						  <li><a href="//help.eclipse.org/">Documentation</a></li>
					    <li><a href="<?php print $variables['url']; ?>contribute/">How to Contribute</a></li>
					    <li><a href="<?php print $variables['url']; ?>mail/">Mailing Lists</a></li>
					    <li><a href="<?php print $variables['url']; ?>forums/">Forums</a></li>
							<li><a href="//marketplace.eclipse.org">Marketplace</a></li>
				    </ul>
			    </section>

			    <section id="footer-other" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">

			      <h2 class="section-title">Other</h2>
			      <ul class="nav">
			       	<li><a href="<?php print $variables['url']; ?>ide/">IDE and Tools</a></li>
				    	<li><a href="<?php print $variables['url']; ?>projects">Community of Projects</a></li>
					    <li><a href="<?php print $variables['url']; ?>org/workinggroups/">Working Groups</a></li>
					  </ul>

            <ul class="list-inline social-media">
              <li><a href="https://twitter.com/EclipseFdn"><i class="fa fa-twitter-square"></i></a></li>
			        <li><a href="https://plus.google.com/+Eclipse"><i class="fa fa-google-plus-square"></i></a></li>
			        <li><a href="https://www.facebook.com/eclipse.org"><i class="fa fa-facebook-square"></i> </a></li>
					    <li><a href="https://www.youtube.com/user/EclipseFdn"><i class="fa fa-youtube-square"></i></a></li>
				    </ul>

	        </section>
	        <div id="copyright"  class="col-xs-offset-1 col-sm-14 col-md-24 col-md-offset-0">
					  <div>
							<span class="hidden-print"><?php print $variables['logo']['white']; ?></span>
							<p id="copyright-text"><?php print $variables['footer']['copyright'];?></p>
				    </div>
          </div>
        <a href="#" class="scrollup">Back to the top</a>
        </div>
      </div>
		</footer>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php print $variables['theme_url'];?>public/javascript/main.min.js"></script>
    <?php print $variables['page']['extra_js_footer'];?>
    <?php print $google_javascript;?>
  </body>
</html>
