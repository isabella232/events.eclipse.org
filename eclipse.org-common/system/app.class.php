<?php
/*******************************************************************************
 * Copyright (c) 2006-2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    Karl Matthias (Eclipse Foundation) - Database access management
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
class App {

	#*****************************************************************************
	#
	# app.class.php
	#
	# Author: 		Denis Roy
	# Date:			2004-08-05
	#
	# Description: Functions and modules related to the application
	#
	# HISTORY:
	#		2007-03-13: added WWW_PREFIX functionality, and default class constructor
	#		2008-09-15:	Added database connectivity and handle management
	#
	#*****************************************************************************

	private $APPVERSION 	= "1.0";
	private $APPNAME		= "Eclipse.org";


	private $DEFAULT_ROW_HEIGHT	= 20;

	private $POST_MAX_SIZE		= 262144;   # 256KB Max post
	private $OUR_DOWNLOAD_URL   = "http://download1.eclipse.org";
	private $PUB_DOWNLOAD_URL   = "http://download.eclipse.org";
	private $DOWNLOAD_BASE_PATH = "/home/data2/httpd/download.eclipse.org";
	private $DB_CLASS_PATH		= "/home/data/httpd/eclipse-php-classes/system/"; # ends with '/'

	private $WWW_PREFIX			= "";  # default is relative
	private $HTTP_PREFIX    = "http"; #default is http

	# Additional page-related variables
	public  $ExtraHtmlHeaders   = "";
	public  $ExtraJSFooter   = "";
	public	$PageRSS			= "";
	public  $PageRSSTitle		= "";
	public  $Promotion			= FALSE;
	public  $CustomPromotionPath = "";
	private $THEME_LIST 		=  array("", "Phoenix", "Miasma", "Lazarus", "Nova", "solstice");

	#Open Graph Protocol Variables
	private $OGTitle            = "";
	private $OGDescription      = "Eclipse is probably best known as a Java IDE, but it is more: it is an IDE framework, a tools framework, an open source project, a community, an eco-system, and a foundation.";
	private $OGImage      	    = "https://www.eclipse.org/eclipse.org-common/themes/Nova/images/eclipse.png";

	#Doctype
	private $doctype 			= FALSE;

	#Google Analytics Variables
	private $projectGoogleAnalyticsCode = "";
	private $googleJavaScript = "";

	#jQuery Variables
	private $jQueryVersion = FALSE;

	# Twitter Follow Widget Variables
	private $twitterScriptInserted = FALSE;

	# Variables for theme customization (Solstice and up).
	private $theme_variables = array();

	# Set to TRUE to disable all database operations
	private $DB_READ_ONLY		= false;

	# Database config and handle cache
	private $databases;

	# Flag to determine whether the "deprecated" theme should be loaded.
	private $OutDated			= false;

	# Flag to determine whether this is development mode or not (for databases)
	public $devmode 			= false;

	# Flag to log SQL even on production systems
	public $logsql				= false;

	# Arbitrary storage hash
	private $hash;

	# SQL Backtrace storage
	public $query_btrace;

	# Default constructor
	function App() {
		# Set value for WWW_PREFIX
		$valid_domains = array(
			'www.eclipse.org',
			'eclipse.org',
			'staging.eclipse.org',
			'eclipse.local',
			'www.eclipse.local'
		);

		// Force http://www.eclipse.org if the serve_name is not whitelisted.
		if (in_array($_SERVER['SERVER_NAME'], $valid_domains)) {
			$this->WWW_PREFIX = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
			$this->WWW_PREFIX .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST') . '/';
		}
		else {
			$this->WWW_PREFIX = "http://www.eclipse.org";
		}

		$this->databases = array();

		# Figure out if we're in devmode by whether the classes are installed or not
		if(!file_exists($this->DB_CLASS_PATH)) {
			$this->devmode = true;
		}

		# Configure databases (not connected)
		$this->configureDatabases();

		# Make it easy to override database and other settings (don't check app-config.php in to CVS!)
		if($this->devmode) {
			if(file_exists(getcwd() . '/app-config.php')) {
				include_once(getcwd() . '/app-config.php');
				# We call a function inside app-config.php and pass it a reference to ourselves because
				# this class is still in the constructor and might not be available externally by name.
				# File just contains a function called app_config() which is called.  Nothing more is needed.
				app_config($this);
			}
			else if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/eclipse.org-common/system/app-config.php'))
			{
				include_once($_SERVER['DOCUMENT_ROOT'] . '/eclipse.org-common/system/app-config.php');
				app_config($this);
			}
		}

		# Initialize backtrace storage
		$this->query_btrace = array();

		# Set server timezone
		date_default_timezone_set("America/Montreal");
	}


	function getAppVersion() {
		return $this->APPVERSION;
	}

	function getHeaderPath($_theme) {
		return $_SERVER["DOCUMENT_ROOT"] . "/eclipse.org-common/themes/" . $_theme . "/header.php";
	}
	function getMenuPath($_theme) {
		return $_SERVER["DOCUMENT_ROOT"] . "/eclipse.org-common/themes/" . $_theme . "/menu.php";
	}
	function getNavPath($_theme) {
		return $_SERVER["DOCUMENT_ROOT"] . "/eclipse.org-common/themes/" . $_theme . "/nav.php";
	}
	function getFooterPath($_theme) {
		return $_SERVER["DOCUMENT_ROOT"] . "/eclipse.org-common/themes/" . $_theme . "/footer.php";
	}
	function getPromotionPath($_theme) {
		return $_SERVER["DOCUMENT_ROOT"] . "/home/promotions/promotion.php";
	}


	function getAppName() {
		return $this->APPNAME;
	}
	function getPostMaxSize() {
		return $this->POST_MAX_SIZE;
	}
	function getDefaultRowHeight() {
		return $this->DEFAULT_ROW_HEIGHT;
	}
	function getDBReadOnly() {
		return $this->DB_READ_ONLY;
	}

	function sendXMLHeader() {
		header("Content-type: text/xml");
	}

	function getOurDownloadServerUrl() {
		return $this->OUR_DOWNLOAD_URL;
	}

	function getDownloadBasePath() {
		return $this->DOWNLOAD_BASE_PATH;
	}

	function getPubDownloadServerUrl() {
		return $this->PUB_DOWNLOAD_URL;
	}

	function getWWWPrefix() {
		return $this->WWW_PREFIX;
	}

	/**
 	* @return: a string
 	*
 	* return https if $_SERVER['HTTPS'] exist otherwise this function returns http
 	*/
	function getHTTPPrefix() {
		$protocol = $this->HTTP_PREFIX;
		if(isset($_SERVER['HTTPS'])) {
			if($_SERVER['HTTPS']){
				$protocol = "https";
			}
		}
		$this->HTTP_PREFIX = $protocol;
		return $this->HTTP_PREFIX ;
	}

	function getUserLanguage() {
		/* @return: String
		 *
		 * Check the browser's default language and return
		 *
		 * 2006-06-28: droy
		 *
		 */

		$validLanguages = array('en', 'de', 'fr');
		$defaultLanguage = "en";

		# get the default browser language (first one reported)
		$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

		if(array_search($language, $validLanguages)) {
				return $language;
		}
		else {
			return $defaultLanguage;
		}
	}

	function getLocalizedContentFilename() {
		/* @return: String
		 *
		 * return the content/xx_filename.php filename, according to availability of the file
		 *
		 * 2006-06-28: droy
		 *
		 */

		$language = $this->getUserLanguage();
		$filename = "content/" . $language . "_" . $this->getScriptName();

		if(!file_exists($filename)) {
			$filename = "content/en_" . $this->getScriptName();
		}

		return $filename;
	}


	function getScriptName() {
		# returns only the filename portion of a script
		return substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
	}

	function getProjectCommon() {
		/** @return: String
		 *
		 * Walk up the directory structure to find the closest _projectCommon.php file
		 *
		 * 2005-12-06: droy
		 * - created basic code to walk up all the way to the DocumentRoot
		 *
		 */

		$currentScript 	= $_SERVER['SCRIPT_FILENAME'];
		$strLen 		= strlen($currentScript);
		$found 			= false;
		$antiLooper		= 0;

		# default to /home/_projectCommon.php
		$rValue 		= $_SERVER['DOCUMENT_ROOT'] . "/home/_projectCommon.php";


		while($strLen > 1 && ! $found) {
			$currentScript 	= substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($currentScript, "/"));
			$testPath 		= $currentScript . "/_projectCommon.php";

			if(file_exists($testPath)) {
				$found 	= true;
				$rValue = $testPath;
			}
			$strLen = strlen($currentScript);

			# break free from endless loops
			$antiLooper++;
			if($antiLooper > 20) {
				$found = true;
			}
		}
		return $rValue;
	}


	function runStdWebAppCacheable() {
		 session_start();

		 header("Cache-control: private");
		 header("Expires: 0");
	}

	function getAlphaCode($_NumChars)
	{
		# Accept: int - number of chars
		# return: string - random alphanumeric code


		# Generate alpha code
		$addstring = "";
		for ($i = 1; $i <= $_NumChars; $i++) {
			if(rand(0,1) == 1) {
				# generate character
				$addstring = $addstring . chr(rand(0,5) + 97);
			}
			else {
				$addstring = $addstring . rand(0,9);
			}
		}
		return $addstring;
	}

	function getCURDATE() {
		return date("Y-m-d");
	}

	function addOrIfNotNull($_String) {
		# Accept: String - String to be AND'ed
		# return: string - AND'ed String

		if($_String != "") {
			$_String = $_String . " OR ";
		}

		return $_String;
	}

	function addAndIfNotNull($_String) {
		# Accept: String - String to be AND'ed
		# return: string - AND'ed String

		if($_String != "") {
			$_String = $_String . " AND ";
		}

		return $_String;
	}

	function getNumCode($_NumChars)
	{
		# Accept: int - number of chars
		# return: int - random numeric code


		# Generate code
		$addstring = "";
		for ($i = 1; $i <= $_NumChars; $i++) {
			if($i > 1) {
				# generate first digit
				$addstring = $addstring . rand(1,9);
			}
			else {
				$addstring = $addstring . rand(0,9);
			}
		}
		return $addstring;
	}

	function str_replace_count($find, $replace,$subject, $count) {
		# Replaces $find with $replace in $subnect $count times only

		$nC = 0;

		$subjectnew = $subject;
		$pos = strpos($subject, $find);
		if ($pos !== FALSE)   {
			while ($pos !== FALSE) {
				$nC++;
				$temp = substr($subjectnew, $pos+strlen($find));
				$subjectnew = substr($subjectnew, 0, $pos) . $replace . $temp;
				if ($nC >= $count)   {
					break;
				}
		        $pos = strpos($subjectnew, $find);
			}
		}
		return $subjectnew;
	}

	function returnQuotedString($_String)
	{
		# Accept: String - String to be quoted
		# return: string - Quoted String

		// replace " with '
		# $_String = str_replace('"', "'", $_String);
		# https://bugs.eclipse.org/bugs/show_bug.cgi?id=299682#c1
		$_String = addslashes($_String);

		return "\"" . $_String . "\"";
	}

	function returnHTMLSafeString($_String)
	{
		# Accept: String - String to be HTMLSafified
		# return: string

		// replace " with '
		$_String = str_replace('<', "&lt;", $_String);
		$_String = str_replace('<', "&gt;", $_String);
		$_String = str_replace("\n", "<br />", $_String);

		return $_String;
	}

	function returnJSSAfeString($_String)
	{
		# Accept: String - String to be quoted
		# return: string - Quoted String

		// replace " with '
		$_String = str_replace("'", "\\'", $_String);

		return $_String;
	}

	function replaceEnterWithBR($_String) {
		return str_replace("\n", "<br />", $_String);
	}

	function generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html, $Breadcrumb = NULL) {

		# Breadcrumbs for the new solstice theme.
		if ($Breadcrumb == NULL || !is_object($Breadcrumb)) {
			require_once('breadcrumbs.class.php');
			$Breadcrumb = new Breadcrumb();
		}

		# Only Nova and solstice is accepted.
		if($theme != "Nova") {
			$theme = "solstice";
		}

		if($pageTitle == "") {
			$pageTitle = "eclipse.org page";
		}

		# page-specific RSS feed
		if($this->PageRSS != "") {
			if ($this->PageRSSTitle != "") {
				$this->PageRSSTitle = "Eclipse RSS Feed";
			}
			$this->ExtraHtmlHeaders .= '<link rel="alternate" title="' . $this->PageRSSTitle . '" href="' . $this->PageRSS . '" type="application/rss+xml"/>';
		}

		$extraHtmlHeaders = $this->ExtraHtmlHeaders;

		include($this->getHeaderPath($theme));

		if ($Menu != NULL)
		include($this->getMenuPath($theme));

		if ($Nav != NULL)
		include($this->getNavPath($theme));

		echo $html;

		#first lets insert the sitewide Analytics
		$this->googleJavaScript  = <<<EOHTML
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-910670-2']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
EOHTML;

		#Now let Check to see if the project is also providing a GA code and include that if they are.
		if ($this->projectGoogleAnalyticsCode != "")
		{
			$gaCode = $this->projectGoogleAnalyticsCode;
			$this->googleJavaScript .= <<<EOHTML

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '$gaCode']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
EOHTML;
		}

		if ($theme != "solstice")  {
			echo $this->googleJavaScript;
		}
		$google_javascript = $this->googleJavaScript;
		include($this->getFooterPath($theme));

		# OPT1:$starttime = microtime();
		# OPT1:$html = ob_get_contents();
		# OPT1:ob_end_clean();

		# OPT1:$stripped_html = $html;
		# OPT1:$stripped_html = preg_replace("/^\s*/", "", $stripped_html);
		# OPT1:$stripped_html = preg_replace("/\s{2,}/", " ", $stripped_html);
		# OPT1:$stripped_html = preg_replace("/^\t*/", "", $stripped_html);
		# OPT1:$stripped_html = preg_replace("/\n/", "", $stripped_html);
		# OPT1:$stripped_html = preg_replace("/>\s</", "><", $stripped_html);
		# $stripped_html = preg_replace("/<!--.*-->/", "", $stripped_html);
		# OPT1:$endtime = microtime();

		# OPT1:echo "<!-- unstripped: " . strlen($html) . " bytes/ stripped: " . strlen($stripped_html) . "bytes - " . sprintf("%.2f", strlen($stripped_html) / strlen($html)) . " Bytes saved: " . (strlen($html) - strlen($stripped_html)) . " Time: " . ($endtime - $starttime) . " -->";
		# echo $stripped_html;
	}

	function AddExtraHtmlHeader( $string ) {
		$this->ExtraHtmlHeaders .= $string;
	}

	function AddExtraJSFooter( $string ) {
		$this->ExtraJSFooter.= $string;
	}

	function getThemeURL($_theme) {
		if($_theme == "") {
			$theme = "solstice";
		}

		return "/eclipse.org-common/themes/" . $_theme;

	}

	function getHTTPParameter($_param_name, $_method="") {
		/** @author droy
		 * @since version - Oct 19, 2006
		 * @param String _param_name name of the HTTP GET/POST parameter
		 * @param String _method GET or POST, or the empty string for POST,GET order
		 * @return String HTTP GET/POST parameter value, or the empty string
		 *
		 * Fetch the HTTP parameter
		 *
		 */

		$rValue = "";
		$_method = strtoupper($_method);

		# Always fetch the GET VALUE, override with POST unless a GET was specifically requested
		if(isset($_GET[$_param_name])) {
			$rValue = $_GET[$_param_name];
		}
		if(isset($_POST[$_param_name]) && $_method != "GET") {
			$rValue = $_POST[$_param_name];
		}
		return $rValue;
	}


	function getClientOS() {

        $UserAgent = $_SERVER['HTTP_USER_AGENT'];

        $regex_windows  = '/([^dar]win[dows]*)[\s]?([0-9a-z]*)[\w\s]?([a-z0-9.]*)/i';
        $regex_mac      = '/(68[k0]{1,3})|(mac os x)|(darwin)/i';
        $regex_os2      = '/os\/2|ibm-webexplorer/i';
        $regex_sunos    = '/(sun|i86)[os\s]*([0-9]*)/i';
        $regex_irix     = '/(irix)[\s]*([0-9]*)/i';
        $regex_hpux     = '/(hp-ux)[\s]*([0-9]*)/i';
        $regex_aix      = '/aix([0-9]*)/i';
        $regex_dec      = '/dec|osfl|alphaserver|ultrix|alphastation/i';
        $regex_vms      = '/vax|openvms/i';
        $regex_sco      = '/sco|unix_sv/i';
        $regex_linux    = '/x11|inux/i';
        $regex_bsd      = '/(free)?(bsd)/i';
        $regex_amiga    = '/amiga[os]?/i';
        $regex_ppc		= '/ppc/i';

        $regex_x86_64   = "/x86_64/i";

        // look for Windows Box
        if(preg_match_all($regex_windows,$UserAgent,$match))  {


			$v  = $match[2][count($match[0])-1];
            $v2 = $match[3][count($match[0])-1];

			// Establish NT 6.0 as Vista
			if(stristr($v,'NT') && $v2 == 6.0) $v = 'win32';

			// Establish NT 5.1 as Windows XP
			elseif(stristr($v,'NT') && $v2 == 5.1) $v = 'win32';

			// Establish NT 5.0 and Windows 2000 as win2k
            elseif($v == '2000') $v = '2k';
			elseif(stristr($v,'NT') && $v2 == 5.0) $v = 'win32';

			// Establish 9x 4.90 as Windows 98
            elseif(stristr($v,'9x') && $v2 == 4.9) $v = 'win32';
			// See if we're running windows 3.1
            elseif($v.$v2 == '16bit') $v = 'win16';
                // otherwise display as is (31,95,98,NT,ME,XP)
			else $v .= $v2;
			// update browser info container array
			if(empty($v)) $v = 'win32';
			return (strtolower($v));
		}

                //  look for amiga OS
                elseif(preg_match($regex_amiga,$UserAgent,$match))  {
                        if(stristr($UserAgent,'morphos')) {
                        // checking for MorphOS
                                return ('morphos');
                                }
                }
                        elseif(stristr($UserAgent,'mc680x0')) {
                        // checking for MC680x0
                        return ('mc680x0');
                        }
                        elseif(preg_match('/(AmigaOS [\.1-9]?)/i',$UserAgent,$match)) {
                              // checking for AmigaOS version string
                                return ($match[1]);
                        }
                // look for OS2
                elseif( preg_match($regex_os2,$UserAgent))  {
                        return ('os2');
                }
                // look for mac
                // sets: platform = mac ; os = 68k or ppc
                elseif( preg_match($regex_mac,$UserAgent,$match) )
                {
                    $os = !empty($match[1]) ? 'mac68k' : '';
                    $os = !empty($match[2]) ? 'macosx' : $os;
                    $os = !empty($match[3]) ? 'macppc' : $os;
                    $os = !empty($match[4]) ? 'macosx' : $os;
                    return ('macosx');
                }
                //  look for *nix boxes
                //  sunos sets: platform = *nix ; os = sun|sun4|sun5|suni86
                elseif(preg_match($regex_sunos,$UserAgent,$match))
                {
                    if(!stristr('sun',$match[1])) $match[1] = 'sun'.$match[1];
                    return ('solaris');
                }
                //  irix sets: platform = *nix ; os = irix|irix5|irix6|...
                elseif(preg_match($regex_irix,$UserAgent,$match))
                {
                    return ($match[1].$match[2]);
                }
                //  hp-ux sets: platform = *nix ; os = hpux9|hpux10|...
                elseif(preg_match($regex_hpux,$UserAgent,$match))
                {
                    $match[1] = str_replace('-','',$match[1]);
                    $match[2] = (int) $match[2];
                    return ('hpux');
                }
                //  aix sets: platform = *nix ; os = aix|aix1|aix2|aix3|...
                elseif(preg_match($regex_aix,$UserAgent,$match))
                {
                    return ('aix');
                }
                //  dec sets: platform = *nix ; os = dec
                elseif(preg_match($regex_dec,$UserAgent,$match))
                {
                    return ('dec');
                }
                //  vms sets: platform = *nix ; os = vms
                elseif(preg_match($regex_vms,$UserAgent,$match))
                {
                    return ('vms');
                }
                //  dec sets: platform = *nix ; os = dec
                elseif(preg_match($regex_dec,$UserAgent,$match))
                {
                    return ('dec');
                }
                //  vms sets: platform = *nix ; os = vms
                elseif(preg_match($regex_vms,$UserAgent,$match))
                {
                    return ('vms');
                }
                //  sco sets: platform = *nix ; os = sco
                elseif(preg_match($regex_sco,$UserAgent,$match))
                {
                    return ('sco');
                }
                //  unixware sets: platform = *nix ; os = unixware
                elseif(stristr($UserAgent,'unix_system_v'))
               {
                    return ('unixware');
                }
                //  mpras sets: platform = *nix ; os = mpras
                elseif(stristr($UserAgent,'ncr'))
                {
                    return ('mpras');
                }
                //  reliant sets: platform = *nix ; os = reliant
                elseif(stristr($UserAgent,'reliantunix'))
                {
                    return ('reliant');
                }
                //  sinix sets: platform = *nix ; os = sinix
                elseif(stristr($UserAgent,'sinix'))
                {
                    return ('sinix');
                }
                //  bsd sets: platform = *nix ; os = bsd|freebsd
                elseif(preg_match($regex_bsd,$UserAgent,$match))
                {
                    return ($match[1].$match[2]);
                }
                //  last one to look for
                //  linux sets: platform = *nix ; os = linux
                elseif(preg_match($regex_linux,$UserAgent,$match))
                {

                        if(preg_match($regex_x86_64,$UserAgent,$match)) {
                                return "linux-x64";
                        }
                        elseif(preg_match($regex_ppc,$UserAgent,$match)) {
                                return "linux-ppc";
                        }
                        else {
                                return ('linux');
                        }
                }
        }



        function isValidTheme($_theme) {
		/* @return: bool
		 *
		 * returns true if supplied theme is in the array of valid themes
		 *
		 * 2005-12-07: droy
		 *
		 */
        	return array_search($_theme, $this->THEME_LIST);
        }


        function getUserPreferedTheme() {
		/* @return: String
		 *
		 * returns theme name in a browser cookie, or the Empty String
		 *
		 * 2005-12-07: droy
		 *
		 */
        	if(isset($_COOKIE["theme"])) {
				$theme = $_COOKIE["theme"];

				if($this->isValidTheme($theme)) {
					return $theme;
				}
				else {
					return "";
				}
        	}
        }

        /**
         * @param layout string Button layout (standard, condensed)
         * @param showfaces bool
         * @author droy
         * @since 2011-05-18
         * @return: HTML string for facebook like
         * Generate HTML string for facebook like button
         */
        function getFacebookLikeButtonHTML($_layout="standard", $_showfaces=false) {

        	$width 	= 450;
        	$height	=  22;

        	if($_layout == "condensed") {
        		$width = 90;
        		$_layout = "button_count";
        	}
        	else {
        		$_layout = "standard";
        	}

        	if($_showfaces) {
        		$height = 82;
        	}
        	$str = "<iframe src='//www.facebook.com/plugins/like.php?href=" . $this->getCurrentURL() . "&layout=" . $_layout . "&" . ($_showfaces ? "show_faces=true" : "") . "&width=$width&action=like' style='border: medium none; overflow: hidden; width: " . $width . "px; height: " . $height . "px;' frameborder='0' scrolling='no'></iframe>";
        	return $str;
        }
        /**
         * @author droy
         * @since 2011-05-18
         * @return URL of the current PHP page
         * Construct and return URL of the current script
         */
        function getCurrentURL() {
        	return "http" . ((empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT']!=443) ? "" : "s") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        function usePolls() {
        	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/polls/poll.php");
        }
		function useJSON() {
        	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/json/JSON.php");
        }

        function useProjectInfo() {
        	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/projects/projectInfoList.class.php");
        }
        /*
         * This function applies standard formatting to a date.
         *
         * The first parameter is either a string or a number representing a date.
         * If it's a string, it must be in a format that is parseable by the
         * strtotime() function. If it is a number, it must be an integer representing
         * a UNIX timestamp (number of seconds since January 1 1970 00:00:00 GMT)
         * which, conveniently, is the output of the strtotime() function.
         *
         * The second (optional) parameter is the format for the result. This must
         * one of 'short', or 'long'.
         */
        function getFormattedDate($date, $format = 'long') {
        	if (is_string($date)) $date = strtotime($date);
        	switch ($format) {
        		case 'long' : return date("F j, Y", $date);
        		case 'short' : return date("d M y", $date);
        	}
        }

        /*
         * This function applies standard formatting to a date range.
         *
         * See the comments for getFormattedDate($date, $format) for information
         * concerning what's expected in the parameters of this method).
         */
        function getFormattedDateRange($start_date, $end_date, $format) {
        	if (is_string($start_date)) $start_date = strtotime($start_date);
        	if (is_string($end_date)) $end_date = strtotime($end_date);
        	switch ($format) {
        		case 'long' :
        			if ($this->same_year($start_date, $end_date)) {
						if ($this->same_month($start_date, $end_date)) {
							return date("F", $start_date)
								. date(" d", $start_date)
								. date("-d, Y", $end_date);
						} else {
							return date("F d", $start_date)
								. date("-F d, Y", $end_date);
						}
					} else {
						return date("F d, Y", $start_date)
							. date("-F d, Y", $end_date);
					}
        		case 'short' :
        			if ($this->same_year($start_date, $end_date)) {
						if ($this->same_month($start_date, $end_date)) {
							return date("d", $start_date)
								. date ("-d", $end_date)
								. date(" M", $start_date)
								. date(" y", $end_date);
						} else {
							return date("d M", $start_date)
								. date("-d M y", $end_date);
						}
					} else {
						return date("d M y", $start_date)
							. date("-d M y", $end_date);
					}
        	}
        }

        /*
         * This method answers true if the two provided values represent
         * dates that occur in the same year.
         */
		function same_year($a, $b) {
			return date("Y", $a) == date("Y", $b);
		}

        /*
         * This method answers true if the two provided values represent
         * dates that occur in the same month.
         */
		function same_month($a, $b) {
			return date("F", $a) == date("F", $b);
		}

		/**
		 * Returns a string representing the size of a file in the downloads area
		 * @author droy
		 * @since Jun 7, 2007
		 * @param string file File name relative to http://download.eclipse.org (the &file= parameter used)
		 * @return string Returns a string in the format of XX MB
		 */
		function getDownloadFileSizeString($_file) {
			$fileSize = "N/A";
			$filesizebytes  = filesize($this->getDownloadBasePath() . $_file);
			if($filesizebytes > 0) {
				$fileSize = floor($filesizebytes / 1048576) . " MB";
			}
			return $fileSize;
		}

		/**
		 * useSession(String) - use auth sessions
		 * @author droy
		 * @since Jun 7, 2007
		 * @param string 'optional' or 'required'
		 * @return Session object
		 */
		function useSession($required="") {
			require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/session.class.php");
        	$ssn = new Session();  # constructor calls validate
        	if ($ssn->getGID() == "" && $required == "required") {
        		$ssn->redirectToLogin();
			}
        	return $ssn;
		}

		function isValidCaller($_pathArray) {
			$a = debug_backtrace();
			$caller = $a[1]['file'];  # Caller 0 is the class that called App();
			$validCaller = false;
			for($i = 0; $i < count($_pathArray); $i++) {
				# TODO: use regexp's to match the leftmost portion for better security
				if(strstr($caller, $_pathArray[$i])) {
					$validCaller = true;
					break;
				}
			}
			return $validCaller;
		}

		function sqlSanitize($_value, $_dbh=NULL) {
		 /**
		 * Sanitize incoming value to prevent SQL injections
		 * @param string value to sanitize
		 * @param dbh database resource to use
		 * @return string santized string
		 */
			if ($_dbh==NULL) {
				$_dbh = $this->database( "eclipse", "" );
			}
			$_value = mysql_real_escape_string($_value, $_dbh);
        	return $_value;
		}
	function getOGTitle(){
		return '<meta property="og:title" content="' . $this->OGTitle . '" />' . PHP_EOL;
	}

	function setOGTitle($title){
		$this->OGTitle = $title;
	}

	function getOGDescription(){
		return '<meta property="og:description" content="' . $this->OGDescription . '" />' . PHP_EOL;
	}

	function setOGDescription($description){
		$this->OGDescription = $description;
	}

	function getOGImage(){
		return '<meta property="og:image" content="' . $this->OGImage . '" />' . PHP_EOL;
	}

	function setOGImage($image){
		$this->OGImage = $image;
	}

	function setDoctype($doctype) {
		$accepted = array('html5', 'xhtml');
		if (in_array($doctype, $accepted)) {
			$this->doctype = $doctype;
		}
		return;
	}

	function getDoctype() {
		$doc = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';
		switch ($this->doctype) {
			case 'html5':
				$doc = '<!DOCTYPE html>
<html>';
				break;
		}
		return $doc;
	}

	/**
	 * Get theme Variables
	 *
	 * Fetch solstice custom variables
	 *
	 * @return array
	 */
	public function getThemeVariables() {
		$v = $this->theme_variables;
		// Set default variables for all themes.
		if (empty($v)) {
			$v['hide_breadcrumbs'] = FALSE;
			$v['breadcrumbs_html'] = "";
			$v['body_classes'] = '';
			$v['main_container_classes'] = 'container';
			$this->theme_variables = $v;
		}

		return $this->theme_variables;
	}

	/**
	 * Set theme Variables
	 *
	 * This function allow pages to pass extra
	 * parameters to the solstice theme.
	 */
	public function setThemeVariables($variables) {
		$current_variables = $this->getThemeVariables();
		if (is_array($variables)) {
			$this->theme_variables = array_merge($current_variables, $variables);
		}
	}

  /**
	 * Function to validate a date
	 * @param string $date
	 * @return boolean
	 */
	private function validateDateFormat($date) {
		if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $d)) {
			//date validation
			if (checkdate($d[2],$d[3],$d[1])) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Function to set the OutDated flag
	 * @param string $when.
	 *   Accepted formats 'YYYY-MM-DD' or 'now'.
	 *
	 * @return boolean
	 */
	function setOutDated($when = 'now') {
		if (strtolower($when) == 'now' || ($this->validateDateFormat($when) && time() >= strtotime($when))) {
			$this->OutDated = TRUE;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Return value of the private property OutDated.
	 */
	function getOutDated() {
		return $this->OutDated;
	}

	/**
	 * Function to set the version of jQuery
	 * @param string $version
	 *
	 * @return boolean
	 */
	function setjQueryVersion($version = FALSE){
		//Only set jQueryVersion if we have a copy on eclipse.org
		$supported = array('1.4.4', '1.5.1', '1.5.2', '1.7.2', '1.9.1', '2.0.0');
		if(in_array($version, $supported)){
			$this->jQueryVersion = $version;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Return markup needed to load jQuery
	 * @return string|boolean
	 */
	function getjQuery(){
		if($this->jQueryVersion){
			$strn = <<<EOHTML
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/$this->jQueryVersion/jquery.min.js"></script>
	<script type="text/javascript">
	/* <![CDATA[ */
	window.jQuery || document.write('<script src="/eclipse.org-common/lib/jquery/jquery-$this->jQueryVersion.min.js"><\/script>')
	/* ]]> */
	</script>
EOHTML;
			return $strn;
		}
		return FALSE;
	}

	/**
	 * Return The Eclipse Foundation Twitter and Facebook badge
	 * @return string
	 */
	function getSocialBadge(){
		$protocol = $this->getHTTPPrefix();
		$strn = <<<EOHTML
		<script type="text/javascript">
		/* <![CDATA[ */
		document.write('<div id=\"badge_facebook\"><iframe src=\"$protocol:\/\/www.facebook.com\/plugins\/like.php?href=http:\/\/www.facebook.com\/pages\/Eclipse\/259655700571&amp;layout=button_count&amp;show_faces=false&amp;width=90&amp;action=like\" frameborder=\"0\" scrolling=\"no\"><\/iframe><\/div><div id=\"badge_twitter\"><a href=\"https:\/\/twitter.com\/EclipseFdn\" class=\"twitter-follow-button\" data-show-count=\"false\">Follow @EclipseFdn<\/a><script type=\"text\/javascript\">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"\/\/platform.twitter.com\/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");<\/script><\/div>');
		/* ]]> */
		</script>
EOHTML;
		return $strn;
	}

	function getTwitterFollowWidget($_twitterhandle)
	{
		$output = '';
		$output = '<a href="https://twitter.com/'. $_twitterhandle .'" class="twitter-follow-button" data-show-count="false">Follow @' .$_twitterhandle . '</a>';
		// Only include the script once per page
		if ($this->twitterScriptInserted == FALSE) {
			$this->twitterScriptInserted = TRUE;
			$output .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		}
		return $output;

	}

	function getGoogleSearchHTML() {
		$strn = <<<EOHTML
		<form action="//www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s">
	 	<input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
  		<input type="text" name="q" size="25" />
  		<input type="submit" name="sa" value="Search" />
		</form>
		<script type="text/javascript" src="//www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&lang=en"></script>
EOHTML;
		return $strn;
	}

	function setGoogleAnalyticsTrackingCode($gaUniqueID) {
		$this->projectGoogleAnalyticsCode = $gaUniqueID;
	}

	function setPromotionPath($_path) {
		$this->CustomPromotionPath = $_SERVER['DOCUMENT_ROOT'] . $_path;
	}

	# Record a database record
	public function setDatabase( $key, $host, $user, $pwd, $db ) {
		$rec = array() ;
		$rec['HOST'] = $host;
		$rec['USERNAME'] = $user;
		$rec['PASSWORD'] = $pwd;
		$rec['DATABASE'] = $db;
		$rec['CONNECTION'] = null;
		$this->databases[$key] = $rec;
  	}

  	# Setup the handling of database connections.  On production systems, reference the database connection
  	# classes, but on development systems, use the standardized local database distribution.
	private function configureDatabases() {
		#-----------------------------------------------------------------------------------------------------
		# Dev Mode Databases
		$this->setDatabase( "myfoundation", "localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "foundation",   "localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "eclipse",      "localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "bugzilla",     "localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "downloads",	"localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "polls", 		"localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "projectinfo",	"localhost", "dashboard", "draobhsad", "myfoundation_demo" );
		$this->setDatabase( "packaging", 	"localhost", "dashboard", "draobhsad", "packaging_demo" );
		$this->setDatabase( "ipzilla", 		"localhost", "dashboard", "draobhsad", "ipzilla_demo" );
		$this->setDatabase( "ipzillatest",	"localhost", "dashboard", "draobhsad", "ipzilla_demo" );
		$this->setDatabase( "live",			"localhost", "dashboard", "draobhsad", "live_demo" );
		$this->setDatabase( "epic", 		"localhost", "dashboard", "draobhsad", "epic_demo" );
		$this->setDatabase( "conferences",  "localhost", "dashboard", "draobhsad", "conferences_demo" );
		$this->setDatabase( "marketplace", 	"localhost", "dashboard", "draobhsad", "marketplace_demo" );
		#-----------------------------------------------------------------------------------------------------

		#-----------------------------------------------------------------------------------------------------
		# Production Databases
		$this->set("bugzilla_db_classfile_ro",	'dbconnection_bugs_ro.class.php');
		$this->set("bugzilla_db_class_ro",		'DBConnectionBugs');
		$this->set("bugzilla_db_classfile",	 	'dbconnection_bugs_rw.class.php');
		$this->set("bugzilla_db_class",		 	'DBConnectionBugsRW');
		$this->set("dashboard_db_classfile",	'dbconnection_dashboard_rw.class.php');
		$this->set("dashboard_db_class",	 	'DBConnectionDashboard');
		$this->set("downloads_db_classfile_ro",	'dbconnection_downloads_ro.class.php');
		$this->set("downloads_db_class_ro",	 	'DBConnectionDownloads');
		$this->set("epic_db_classfile_ro",	 	'dbconnection_epic_ro.class.php');
		$this->set("epic_db_class_ro",	 	 	'DBConnectionEPIC');
		$this->set("packaging_db_classfile_ro",	'dbconnection_packaging_ro.class.php');
		$this->set("packaging_db_class_ro",		'DBConnectionPackaging');
		$this->set("foundation_db_classfile",	'dbconnection_workaround.class.php');
		$this->set("foundation_db_class",	 	'FoundationDBConnectionRW');
		$this->set("foundation_db_classfile_ro",'dbconnection_foundation_ro.class.php');
		$this->set("foundation_db_class_ro", 	'DBConnectionFoundation');
		$this->set("gerrit_db_classfile_ro",	'dbconnection_gerrit_ro.class.php');
		$this->set("gerrit_db_class_ro",		'DBConnectionGerrit');
		$this->set("ipzilla_db_classfile_ro",	'dbconnection_ipzilla_ro.class.php');
		$this->set("ipzilla_db_class_ro",	 	'DBConnectionIPZillaRO');
		$this->set("ipzilla_db_classfile",	 	'dbconnection_ipzilla_rw.class.php');
		$this->set("ipzilla_db_class",	 	 	'DBConnectionIPZillaRW');
		$this->set("ipzillatest_db_classfile",	'dbconnection_ipzillatest_rw.class.php');
		$this->set("ipzillatest_db_class",	 	'DBConnectionIPZillaRW');
		$this->set("live_db_classfile",	 	 	'dbconnection_live_rw.class.php');
		$this->set("live_db_class",	 	 		'DBConnectionLIVE');
		$this->set("polls_db_classfile",	 	'dbconnection_polls_rw.class.php');
		$this->set("polls_db_class",		 	'DBConnectionPollsRW');
		$this->set("myfoundation_db_classfile",	'dbconnection_portal_rw.class.php');
		$this->set("myfoundation_db_class",	 	'DBConnectionPortalRW');
		$this->set("projectinfo_db_classfile_ro",'dbconnection_projectinfo_ro.class.php');
		$this->set("projectinfo_db_class_ro", 	'DBConnectionProjectInfo');
		$this->set("eclipse_db_classfile",	 	'dbconnection_rw.class.php');
		$this->set("eclipse_db_class",		 	'DBConnectionRW');
		$this->set("eclipse_db_classfile_ro",	'dbconnection.class.php');
		$this->set("eclipse_db_class_ro",	 	'DBConnection');
		$this->set("conferences_db_classfile",	'dbconnection.conferences_rw.class.php');
		$this->set("conferences_db_class", 	 	'DBConnectionConferencesRW');
		$this->set("marketplace_db_classfile_ro",	 	'dbconnection_marketplace_ro.class.php');
		$this->set("marketplace_db_class_ro",	  	'DBConnectionMarket');
		#-----------------------------------------------------------------------------------------------------
	}

	# Open a database and store the record
	public function database( $key, $query ) {
		$rec = $this->databases[$key];
		$dbh = null;
		if($this->devmode) { 	# For DEV machines
				$dbh = $rec['CONNECTION'];
				if( $dbh == null ) {
					$dbh = mysql_connect( $rec['HOST'], $rec['USERNAME'], $rec['PASSWORD']);
				}
				if(get_magic_quotes_gpc())
				{
					trigger_error("magic_quotes_gpc is currently set to ON in your php.ini. This is highly DISCOURAGED.  Please change your setting or comment out this line.");
				}
		} else { 				# For PRODUCTION machines
			$class = null;

			if(strtoupper(substr(trim($query), 0, 6)) == 'SELECT'
			&& strtoupper(substr(trim($query), 0, 23)) != "SELECT /* USE MASTER */") {  // Try to use read-only when possible
				$classfile = $this->get($key . '_db_classfile_ro');
				$class = $this->get($key . '_db_class_ro');
			}

			if($class == null) {
				$classfile = $this->get($key . '_db_classfile');
				$class = $this->get($key . '_db_class');
			}

			require_once($this->DB_CLASS_PATH . $classfile);
			$dbc = new $class();
			$dbh = $dbc->connect();
		}
		$this->databases[$key]['CONNECTION'] = $dbh;
		$this->set('DBHANDLEMAP ' . $dbh, $key);
		mysql_select_db( $rec['DATABASE'], $dbh );
		return $dbh;
	}

	# Return a record for a database by name
	public function databaseName( $key ) {
		$rec = $this->databases[$key];
		return $rec['DATABASE'];
	}

	# Return the name of a database by database handle
	public function databaseNameForHandle($dbh) {
		return $this->get('DBHANDLEMAP ' . $dbh);
	}

	# Storage functions for arbitraray hash
	public function get( $key ) {
		if(isset($this->hash[$key])) {
			return $this->hash[$key];
		}
	}

	# Storage functions for arbitraray hash
	public function set( $key, $value ) {
		$this->hash[$key] = $value;
	}

	# Storage functions for arbitraray hash
	public function ifEmptyThenSet( $key, $value ) {
		if( !isset($this->hash[$key])) {
			$this->hash[$key] = $value;
		}
	}

	# Display a backtrace of all the SQL queries run in this session.  Only available when devmode == true or logsql == true.
	function SQLBacktrace () {
		if(($this->devmode && (count($this->query_btrace) > 0)) || $this->logsql){
	    	$row = 1;
	    	echo "<p><table cellpadding=10 width=800 bgcolor=#ffcccc><tr><td>";
	    	echo "<p><font size=\"+2\">Query Trace: </font> In ascending order from oldest to newest";
	    	echo "<div style=\"font-family: courier;\">";
	    	foreach ($this->query_btrace as $query) {
	  			echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>$row.) " .
	  				$query{0} ." (" . $query{2} . ")rows:</b> " . $query{1} . "<br>\n";
	  			$row++;
	    	}
	    	echo "</div>";
	  		echo "</p>\n";
	  		echo "</table></p>\n";
	  	}
	}

	# Check if a MySQL error occurred and display it.  If $this->devmode then an
	# SQL backtrace is shown as well.
	function mysqlErrorCheck () {
		$error = mysql_error();
		if ($error) {
			echo "<p><table cellpadding=10 width=400 bgcolor=#ffcccc><tr><td><font size=+2>SQL Trouble: </font>";
			echo "<font color=red>";
			echo htmlspecialchars ($error);
			echo "</font>\n";
			if($this->devmode || $this->logsql) {
				$backtrace = debug_backtrace();
				$file = $backtrace[2]['file'];
				$line = $backtrace[2]['line'];
				$function = $backtrace[2]['function'];
				echo "<br/>file: $file<br/>line: $line<br/>function: $function<br/>";
			}
		echo "</table></p>\n";
		if($this->devmode || $this->logsql) {
			$this->SQLBacktrace();
		}
		exit();
		}
	}

	# All in one query function
	function sql ($statement, $dbname, $logstring = null) {
		$dbh = $this->database( $dbname, $statement );

		$result = mysql_query($statement, $dbh);
		$rowcount = 0;

		# Only keep information in devmode so we don't waste RAM
		if($this->devmode || $this->logsql) {
			# Report on the number of rows affected by the query
			if(($result !== TRUE) && ($result !== FALSE)) {
				$rowcount = mysql_num_rows($result);
			} else {
				$rowcount = mysql_affected_rows($dbh);
			}

			if($logstring) {
				# This is used when inserting binary blobs so that the blob does not appear in the log
				$this->query_btrace[] = array($this->databaseNameForHandle($dbh), $logstring, $rowcount);
			} else {
				$this->query_btrace[] = array($this->databaseNameForHandle($dbh), $statement, $rowcount);
			}
		}

		$this->mysqlErrorCheck();
		return $result;
	}

	# These don't match the naming convention in $App but are used in the portal and submissions systems like this
	# so we'll leave them alone for consistency.
	function bugzilla_sql ($statement) 	{ return $this->sql ($statement, "bugzilla"); } 		// Bugzilla
	function conference_sql ($statement){ return $this->sql ($statement, "conferences"); }		// Conferences
	function dashboard_sql ($statement) { return $this->sql ($statement, "dashboard"); }		// Dash
	function downloads_sql ($statement) { return $this->sql ($statement, "downloads"); }		// Downloads
	function eclipse_sql ($statement) 	{ return $this->sql ($statement, "eclipse"); }			// Whole Eclipse database
	function epic_sql ($statement) 		{ return $this->sql ($statement, "epic"); }				// EPIC (read-only!)
	function foundation_sql($statement) { return $this->sql ($statement, "foundation"); }		// Foundation internal database
	function gerrit_sql($statement) 	{ return $this->sql ($statement, "gerrit"); }			// Gerrit
	function ipzilla_sql ($statement) 	{ return $this->sql ($statement, "ipzilla"); }			// IPZilla
	function ipzillatest_sql ($statement) { return $this->sql ($statement, "ipzillatest"); }	// IPZilla test database
	function live_sql ($statement) 		{ return $this->sql ($statement, "live"); }				// Eclipse Live (read-only!)
	function polls_sql ($statement) 	{ return $this->sql ($statement, "polls"); }			// Polls
	function portal_sql	($statement) 	{ return $this->sql ($statement, "myfoundation"); }		// MyFoundation Portal
	function projectinfo_sql ($statement) { return $this->sql ($statement, "projectinfo"); }	// ProjectInfo tables only (read-only!)
	function packaging_sql ($statement)  { return $this->sql ($statement, "packaging"); } 		// Packaging Database
	function marketplace_sql ($statement) { return $this->sql ($statement, "marketplace"); } 	// Marketplace (read-only)
}

?>