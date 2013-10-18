<?php
/*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

define('MAX_LOG_DAYS', 365);

require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php");

class EvtLog {

	#*****************************************************************************
	#
	# evt_log.class.php
	#
	# Author: 		Denis Roy
	# Date:			2004-08-05
	#
	# Description: Functions and modules related to a modification log entry
	#
	# HISTORY:
	#
	#*****************************************************************************


	
	var $LogID 		= 0;
	var $LogTable		= "";
	var $PK1		= "";
	var $PK2		= "";
	var $LogAction		= "";
	var $uid		= "";
	var $EvtDateTime	= "";
	
	function getLogID() {
		return $this->LogID;
	}
	function getLogTable() {
		return $this->LogTable;
	}
	function getPK1() {
		return $this->PK1;
	}
	function getPK2() {
		return $this->PK2;
	}
	function getLogAction() {
		return $this->LogAction;
	}
	function getuid() {
		return $this->PersonID;
	}
	function getEvtDateTime() {
		return $this->EvtDateTime;
	}


	function setLogID($_LogID) {
		$this->LogID = $_LogID;
	}
	function setLogTable($_LogTable) {
		$this->LogTable = $_LogTable;
	}
	function setPK1($_PK1) {
		$this->PK1 = $_PK1;
	}
	function setPK2($_PK2) {
		$this->PK2 = $_PK2;
	}
	function setLogAction($_LogAction) {
		$this->LogAction = $_LogAction;
	}
	function setuid($_uid) {
		$this->uid = $_uid;
	}
	function setEvtDateTime($_EvtDateTime) {
		$this->EvtDateTime = $_EvtDateTime;
	}

	function insertModLog ($_uid) {
		$uid = $_uid;
		if($this->getLogTable() != "" && $this->getPK1() != "" && $this->getLogAction() != "" && $uid != "") {
			$App = new App();
			$dbc = new DBConnectionRW();
			$dbh = $dbc->connect();
			
			$sql = "INSERT INTO SYS_EvtLog (
						LogID,
						LogTable,
						PK1,
						PK2,
						LogAction,
						uid,
						EvtDateTime)
					VALUES (
						NULL,
						" . $App->returnQuotedString($App->sqlSanitize($this->getLogTable(),$dbh)) . ",
						" . $App->returnQuotedString($App->sqlSanitize($this->getPK1(),$dbh)) . ",
						" . $App->returnQuotedString($App->sqlSanitize($this->getPK2(),$dbh)) . ",
						" . $App->returnQuotedString($App->sqlSanitize($this->getLogAction(),$dbh)) . ",
						" . $App->returnQuotedString($App->sqlSanitize($uid),$dbh) . ",
						NOW()
					)";
					
			mysql_query($sql, $dbh);
			if(mysql_error() != "") {
				echo "An unknown database error has occurred while logging information.  Please contact the System Administrator.";
				echo mysql_error();
				exit;
			}
			
			$dbc->disconnect();
			
			# 1% of each hits will perform clean up	
			if(rand(0, 100) < 1) {
				$this->cleanup();
			}
		}
		else {
			echo "An unknown system error has occurred while logging information.  Please contact the System Administrator.";
			exit;
		}
	}
	
	function cleanup() {
		$sql = "DELETE FROM SYS_EvtLog WHERE EvtDateTime < " . MAX_LOG_DAYS;
					
		$dbc = new DBConnectionRW();
		$dbh = $dbc->connect();
		mysql_query($sql, $dbh);
		$dbc->disconnect();
	}
}
?>