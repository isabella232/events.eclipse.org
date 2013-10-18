<?php

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

class Drop {

	#*****************************************************************************
	#
	# drop.class.php
	#
	# Author: 	Denis Roy
	# Date:		2004-11-16
	#
	# Description: Functions and modules related to drop objects
	#
	# HISTORY:
	#
	#*****************************************************************************

	var $drop_id 			= "";
	var $project_id 		= "";
	var $rsync_stanza 		= "";
	var $description		= "";
	var $is_active			= 0;
	var $our_path 			= "";
	var $size				= 0;
	var $size_unit			= "";
	var $index_url			= "";
	
	
	function getDropID() {
		return $this->drop_id;
	}
	function getProjectID() {
		return $this->project_id;
	}
	function getRsyncStanza() {
		return $this->rsync_stanza;
	}
	function getDescription() {
		return $this->description;
	}
	function getIsActive() {
		return $this->is_active;
	}
	function getOurPath() {
		return $this->our_path;
	}
	function getSize() {
		return $this->size;
	}
	function getSizeUnit() {
		return $this->size_unit;
	}
	function setDropID($_drop_id) {
		$this->drop_id = $_drop_id;
	}
	function setProjectID($_project_id) {
		$this->project_id = $_project_id;
	}
	function setRsyncStanza($_rsync_stanza) {
		$this->rsync_stanza = $_rsync_stanza;
	}
	function setDescription($_description) {
		$this->description = $_description;
	}
	function setIsActive($_is_active) {
		$this->is_active = $_is_active;
	}
	function setOurPath($_our_path) {
		$this->our_path = $_our_path;
	}
	function setSize($_size) {
		$this->size = $_size;
	}
	function setSizeUnit($_size_unit) {
		$this->size_unit = $_size_unit;
	}
}
?>
