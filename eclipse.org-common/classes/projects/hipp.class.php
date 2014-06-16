<?php
/*******************************************************************************
 * Copyright (c) 2013 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");

class Hipp {

	#*****************************************************************************
	#
	# hipp.class.php
	#
	# Author: 	Denis Roy
	# Date:		2013-11-06
	#
	# Description: Functions and modules related to Hudson HIPP
	#
	# HISTORY:
	#
	# mysql> select * from ProjectServices;
	# +----+------------------+-------------+------------+------------+----------+-----------+---------+
	# | ID | ProjectID        | ServiceType | ServerHost | ServerPort | XvncBase | OtherData | State   |
	# +----+------------------+-------------+------------+------------+----------+-----------+---------+
	# |  1 | technology.babel | hipp        | hipp3      |       8215 |      360 | NULL      | running |
	# +----+------------------+-------------+------------+------------+----------+-----------+---------+
	# 
	#*****************************************************************************

	var $ID					= 0;
	var $ProjectID 			= "";
	var $ServiceType		= "";
	var $ServerHost			= "";
	var $ServerPort		 	= 0;
	var $XvncBase			= 0;
	var $OtherData			= "";
	var $State				= "";
	
	function getID() {
		return $this->ID;
	}
	function getProjectID() {
		return $this->ProjectID;
	}
	function getServiceType() {
		return $this->ServiceType;
	}
	function getServerHost() {
		return $this->ServerHost;
	}
	function getServerPort() {
		return $this->ServerPort;
	}
	function getXvncBase() {
		return $this->XvncBase;
	}
	function getOtherData() {
		return $this->OtherData;
	}
	function getState() {
		return $this->State;
	}
	
	function setID($_ID) {
		$this->ID = $_ID;
	}
	function setProjectID($_ProjectID) {
		$this->ProjectID = $_ProjectID;
	}
	function setServiceType($_ServiceType) {
		$this->ServiceType = $_ServiceType;
	}
	function setServerHost($_ServerHost) {
		$this->ServerHost = $_ServerHost;
	}
	function setServerPort($_ServerPort) {
		$this->ServerPort = $_ServerPort;
	}
	function setXvncBase($_XvncBase) {
		$this->XvncBase = $_XvncBase;
	}
	function setOtherData($_OtherData) {
		$this->OtherData = $_OtherData;
	}
	function setState($_State) {
		$this->State = $_State;
	}

	function selectHIPP($_ProjectID) {
		
		$App = new App();
	    $WHERE = "";
	
	    if($_ProjectID != "") {

            $WHERE .= " WHERE SRV.ProjectID = " . $App->returnQuotedString($App->sqlSanitize($_ProjectID)) . "
            			AND SRV.ServiceType = 'hipp'";
	
		    $sql = "SELECT /*  hipp.class.php */
						SRV.ID,
		    			SRV.ProjectID,
						SRV.ServiceType,
		    			SRV.ServerHost,
		    			SRV.ServerPort,
		    			SRV.XvncBase,
		    			SRV.OtherData,
		    			SRV.State
		        	FROM
						ProjectServices AS SRV "
					. $WHERE;

		    $result = $App->foundation_sql($sql);
	
			if($myrow = mysql_fetch_array($result)) {
				$this->setID			($myrow["ID"]);
				$this->setProjectID		($myrow["ProjectID"]);
				$this->setServiceType	($myrow["ServiceType"]);
				$this->setServerHost	($myrow["ServerHost"]);
				$this->setServerPort	($myrow["ServerPort"]);
				$this->setXvncBase		($myrow["XvncBase"]);
				$this->setOtherData		($myrow["OtherData"]);
				$this->setState			($myrow["State"]);
		    }		    
		    $result = null;
		    $myrow	= null;
	    }
	}
}
?>