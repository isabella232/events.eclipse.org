<?php
 
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton + Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

	if (file_exists("/home/data/httpd/eclipse-php-classes/system/dbconnection.class.php")) {
		require_once "/home/data/httpd/eclipse-php-classes/system/dbconnection.class.php";
		require_once "/home/data/httpd/eclipse-php-classes/system/dbconnection_epic_ro.class.php";	
		require_once "/home/data/httpd/eclipse-php-classes/system/dbconnection_live_rw.class.php";	
	} else {
		require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/dbconnection.class.php");
		//require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/dbconnection_epic_ro.class.php");
		//require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/dbconnection_live_rw.class.php");		
	}

?>