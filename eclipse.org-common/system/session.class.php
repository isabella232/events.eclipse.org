<?php
/*******************************************************************************
 * Copyright (c) 2007-2011 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

define('ECLIPSE_SESSION', 'ECLIPSESESSION');
define('HTACCESS', '/home/data/httpd/friends.eclipse.org/html/.htaccess');
define('LOGINPAGE', 'https://dev.eclipse.org/site_login/');

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
if (!class_exists("EvtLog")) {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/evt_log.class.php");
}


class Session {

	private $gid		= "";
	private $bugzilla_id= 0;
	private $subnet		= "";
	private $updated_at	= "";
	private $is_persistent	= 0;
	private $Friend		= null;
	private $data		= "";
	
	/**
	 * Default constructor
	 *
	 * @return null
	 */
	function Session($persistent=0) {
		$this->validate();
		$this->setIsPersistent($persistent);
	}

	
	function getGID() {
		return $this->gid;
	}
	function getBugzillaID() {
		return $this->bugzilla_id;
	}
	function getSubnet() {
		return $this->subnet;
	}
	function getUpdatedAt() {
		return $this->updated_at;
	}
	function getFriend() {
		if($this->Friend == null) {
			$this->Friend = new Friend();
		}
		return $this->Friend;
	}
	function getData() {
		return unserialize($this->data);
	}
	function getIsPersistent() {
		return $this->is_persistent == null ? 0 : $this->is_persistent;
	}
	function getLoginPageURL() {
		return LOGINPAGE;
	}
	function getIsLoggedIn() {
		return $this->getGID() !== "";
	}
	
	function setGID($_gid) {
		$this->gid = $_gid;
	}
	function setBugzillaID($_bugzilla_id) {
		$this->bugzilla_id = $_bugzilla_id;
	}
	function setSubnet($_subnet) {
		$this->subnet = $_subnet;
	}
	function setUpdatedAt($_updated_at) {
		$this->updated_at = $_updated_at;
	}
	function setFriend($_friend) {
		$this->Friend = $_friend;
	}
	function setData($_data) {
		$this->data = serialize($_data);
	}
	function setIsPersistent($_is_persistent) {
		$this->is_persistent = $_is_persistent;
	}

	
	/**
	 * Validate session based on browser cookie
	 *
	 * @return boolean
	 */
	function validate() {
		$cookie = (isset($_COOKIE[ECLIPSE_SESSION]) ? $_COOKIE[ECLIPSE_SESSION] : "");
		$rValue = false;
		if ( (!$this->load($cookie))) {
        	# Failed - no such session, or session no match.  Need to relogin
        	# Bug 257675
        	# setcookie(ECLIPSE_SESSION, "", time() - 3600, "/", ".eclipse.org");
        	$rValue = false;
        }
        else {
			# TODO: update session?
			$rValue = true;
        	$this->maintenance();
        	$this->setFriend($this->getData());
        }
        return $rValue;
	}

	function destroy() {
		$App = new App();
		$sql = "DELETE FROM sessions WHERE gid = '" . $App->sqlSanitize($this->getGID(), null) . "' LIMIT 1";
        $App->eclipse_sql($sql);
		setcookie(ECLIPSE_SESSION, "", time() - 3600, "/", ".eclipse.org", 1, TRUE);
			
		if(!$App->devmode) {
			# Log this event
			$EvtLog = new EvtLog();
			$EvtLog->setLogTable("sessions");
			$EvtLog->setPK1($this->getBugzillaID());
			$EvtLog->setPK2($_SERVER['REMOTE_ADDR']);
			$EvtLog->setLogAction("DELETE");
			$EvtLog->insertModLog("apache");
		}
	}

	function create() {
		# create session on the database
		$Friend = $this->getFriend();
		$this->setData($Friend);
		
		# need to have a bugzilla ID to log in
		if($Friend->getBugzillaID() > 0) {
			$App = new App();
			$this->setGID(md5(uniqid(rand(),true)));
			$this->setSubnet($this->getClientSubnet());
			$this->setUpdatedAt($App->getCURDATE());
			$this->setBugzillaID($Friend->getBugzillaID());
			
			$sql = "INSERT INTO sessions (
						gid,
						bugzilla_id,
						subnet,
						updated_at,
						data,
						is_persistent)
						VALUES (
							" . $App->returnQuotedString($this->getGID()) . ",
							" . $App->sqlSanitize($Friend->getBugzillaID(), null) . ",
							" . $App->returnQuotedString($this->getSubnet()) . ",
							NOW(),
							'" . $App->sqlSanitize($this->data) . "',
							'" . $App->sqlSanitize($this->getIsPersistent(), null) . "')";

			$App->eclipse_sql($sql);
			
			if(!$App->devmode) {
				# Log this event
				$EvtLog = new EvtLog();
				$EvtLog->setLogTable("sessions");
				$EvtLog->setPK1($this->getBugzillaID());
				$EvtLog->setPK2($_SERVER['REMOTE_ADDR']);
				$EvtLog->setLogAction("INSERT");
				$EvtLog->insertModLog("apache");

				# add session to the .htaccess file
				# TODO: implement a smart locking
				if($Friend->getIsBenefit()) {
					$fh = fopen(HTACCESS, 'a') or die("can't open file");
					$new_line = "SetEnvIf Cookie \"" . $this->getGID() . "\" eclipsefriend=1\n";
					fwrite($fh, $new_line);
					fclose($fh);
				}
			}
			
			$cookie_time = 0;
			if($this->getIsPersistent()) {
				$cookie_time = time()+3600*24*365;
			}

			setcookie(ECLIPSE_SESSION, $this->getGID(), $cookie_time, "/", ".eclipse.org", 1, TRUE);
			
			# uncomment for local dev
			# setcookie(ECLIPSE_SESSION, $this->getGID(), $cookie_time, "/");
		}
	}

	function load($_gid) {
		# need to have a bugzilla ID to log in
		
		$rValue = false;
		if($_gid != "") {
			$App = new App();
			$sql = "SELECT /* USE MASTER */ gid, bugzilla_id, subnet, updated_at, data,	is_persistent
					FROM sessions
					WHERE gid = " . $App->returnQuotedString($App->sqlSanitize($_gid, null));
						# " AND subnet = " . $App->returnQuotedString($this->getClientSubnet());
			
			$result = $App->eclipse_sql($sql);
			if($result && mysql_num_rows($result) > 0) {
				$rValue = true;
				$myrow = mysql_fetch_assoc($result);
				$this->setGID($_gid);
				$this->setBugzillaID($myrow['bugzilla_id']);
				$this->setSubnet($myrow['subnet']);
				$this->setUpdatedAt($myrow['updated_at']);
				$this->data = $myrow['data'];
				$this->setIsPersistent($myrow['is_persistent']);
			}
		}		
		return $rValue;
	}

	function maintenance() {
		$App = new App();
			
		$sql = "DELETE FROM sessions
				WHERE (updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY) AND is_persistent = 0) 
				OR (subnet = '" . $this->getClientSubnet() . "' AND gid <> '" . $App->sqlSanitize($this->getGID(), null) . "')
				OR updated_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)"; 

		$App->eclipse_sql($sql);
		
		# 1/500 of each maintenance calls will perform htaccess cleanup	
		if(rand(0, 500) < 1) {
			$this->regenrate_htaccess();
		}
	}
	
	private function regenrate_htaccess() {
		$App = new App();
		
		if(!$App->devmode) {
			
			$sql = "SELECT gid 
					FROM sessions AS S 
						INNER JOIN friends AS F ON F.bugzilla_id = S.bugzilla_id 
					WHERE F.is_benefit = 1"; 
	
			$result = $App->eclipse_sql($sql);
			$new_file = "";
			while($myrow = mysql_fetch_assoc($result)) {
				$new_file .= "SetEnvIf Cookie \"" . $myrow['gid'] . "\" eclipsefriend=1\n";	
			}
			
			if($new_file != "") {
				$fh = fopen(HTACCESS, 'w') or die("can't open file");
				fwrite($fh, $new_file);
				fclose($fh);
			}
		}
	}
		
	function getClientSubnet() {
		# return class-c subnet
		return substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], ".")) . ".0";
	}
	
	function redirectToLogin() {
		header("Location: " . LOGINPAGE);
		exit;
	}
}
?>