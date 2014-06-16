<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation) - Updated first level and added removeCrumb()
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menuitem.class.php");

class Breadcrumb extends Menu {

	private $CrumbList = array();

	# static list of first-level URIs with corresponding display-friendly names
	# everything outside of this is considered to be in project space
	private $FirstLevel = array(
		"10years"	=> "10 years of Eclipse",
		"articles" => "Articles",
		"artwork" => "Artwork",
		"community" => "Community",
		"committers" => "Committers",
		"contribute" => "Contribute",
		"corporate_sponsors" => "Corporate Sponsors",
		"donate" => "Donate",
		"downloads"	=> "Downloads",
		"eclipse4" => "Eclipse SDK 4.x",
		"errors" => "Errors",
		"europa" => "Europa",
		"friends" => "Friends of Eclipse",
		"galileo" => "Galileo",
		"ganymede" => "Ganymede",
		"go" => "Go",
		"home" => "Home",
		"images" => "Images",
		"indigo" => "Indigo",
		"juno" => "Juno",
		"kepler" => "Kepler",
		"legal" => "Legal",
		"licenses" => "Licenses",
		"luna" => "Luna",
		"mail" => "Mailing Lists",
		"membership" => "Members",
		"mobile" => "Mobile",
		"newsgroups" => "Forums",
		"org" => "About Us",
		"phoenix-test" => "Test",
		"projects" => "Projects",
		"resources" => "Resources",
		"screenshots" => "Screenshots",
		"site_login" => "My Account",
		"users"	=> "Getting started",
	);

	function getCrumbList() {
		return $this->CrumbList;
	}

	function setCrumbList($_List) {
		$this->CrumbList = $_List;
	}

	# Main constructor
	function Breadcrumb() {

		$www_prefix = "";

		global $App;

		if (!isset($App)) {
			$App = new App();
		}
		$www_prefix = $App->getWWWPrefix();

		# Default: Home
		$this->addCrumb("Home", $www_prefix . "/", "_self");

		if(isset($_SERVER['REQUEST_URI'])) {
			# http://www.eclipse.org/newsgroups/test.php
			# Array ( [0] => [1] => newsgroups [2] => test.php )
			$items = explode("/", $_SERVER['REQUEST_URI']);


			# Examine Item 1 (first level URL)
			if(isset($this->FirstLevel[$items[1]])) {
				$this->addCrumb($this->FirstLevel[$items[1]], $www_prefix . "/" . $items[1], "_self");
			}
			else {
				# Not pre-defined Foundation page, must be a project page
				# /xtext/file.php => Home > Projects > xtext > $pageTitle
				$this->addCrumb("Project", $www_prefix . "/projects/", "_self");
				$this->addCrumb($items[1], $www_prefix . "/" . $items[1], "_self");
			}

			# Add current page
			# AT this point, $pageTitle should be set as we are running in header()
			global $pageTitle;
			if (isset($pageTitle)) {
				$title = $pageTitle;

				# consider truncating $pageTitle if it's too long
				if (strlen($title) > 35) {
					$title = substr($title, 0, 35) . "...";
				}

				$this->addCrumb($pageTitle, NULL, NULL);
			}
			else {
				# Add final generic crumb
				$this->addCrumb("Document", NULL, NULL);
			}
		}
	}

	function addCrumb($_Text, $_URL, $_Target) {
		# Menu Items must be added at position 1
		$Crumb = new Link($_Text, $_URL, $_Target, 0);

		# Add incoming menuitem
		$this->CrumbList[count($this->CrumbList)] = $Crumb;
	}

	function getCrumbCount() {
		return count($this->CrumbList);
	}

	function getCrumbAt($_Pos) {
		if($_Pos < $this->getCrumbCount()) {
			return $this->CrumbList[$_Pos];
		}
	}

	/**
	 * Insert breadcrumb at a specific position
	 * @param unknown $_Pos Position to insert at
	 * @param unknown $_Text Link text
	 * @param unknown $_URL Link URL
	 * @param unknown $_Target Link target
	 *
	 */
	function insertCrumbAt($_Pos, $_Text, $_URL, $_Target) {
		if ($_Pos < $this->getCrumbCount() && $_Pos > 0) {  # Don't allow inserting before Home
			$Crumb = new Link($_Text, $_URL, $_Target, 0);
			$tempList = array($Crumb);
			$result = array_merge(array_slice($this->CrumbList, 0, $_Pos, true), $tempList, array_slice($this->CrumbList, $_Pos, $this->getCrumbCount(), true));
			$this->CrumbList = $result;
		}
		else {
			$this->addCrumb($_Text, $_URL, $_Target);
		}
	}

	/**
	 * Unset a link from CrumbList
	 *
	 * @param int|array $_Key
	 */
	private function _removeCrumb($_Key){
		if (isset($this->CrumbList[$_Key])) {
			unset($this->CrumbList[$_Key]);
		}
	}

	/**
	 * Remove links from CrumbList
	 *
	 * Usage example:
	 *
	 *   Remove more than one link with an array.
	 *     $Breadcrumb->removeCrumb(array(0,3));
	 *
	 *   Remove only one link.
	 *     $Breadcrumb->removeCrumb(1);
	 *
	 * @param int|array $_Key
	 */
	function removeCrumb($_Key = NULL) {

		if ($_Key === NULL) {
			return;
		}

		if (is_array($_Key)) {
			foreach ($_Key as $k) {
				$this->_removeCrumb($k);
			}
		}
		else {
			$this->_removeCrumb($_Key);
		}

		// 'reindex' CrumbList.
		$this->CrumbList = array_values($this->CrumbList);
	}

	function showBreadcrumbs() {
		# for debugging purposes only
		echo "Breadcrumbs: ";
		foreach ($this->CrumbList as $Crumb) {
			# $Crumb is a Link object
			echo "<a href='" . $Crumb->getURL() . "'>" . $Crumb->getText() . "</a>";
			echo " | " ;
		}
	}
}
