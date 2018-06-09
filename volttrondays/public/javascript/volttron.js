/**
 * Copyright (c) 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

$(function() {
  $('.schedule-volttron-menu a').click(function() {
    var day = "day-1";
    if ($(this).hasClass('day-2')) {
      day = "day-2";
    }

    $('.schedule-volttron-day').removeClass("active").hide();
    $('.schedule-volttron-' + day).fadeIn(300).addClass("active");
  });
  
  $('.navbar-toggle').click(function() {
    var menu = document.getElementById("top-menu");
    if (window.pageYOffset == menu.offsetTop) {
      $('#top-menu').toggleClass("menu-bg-scrolling");
    }
  });
  
  var menu = document.getElementById("top-menu");
  
  window.onload = function() {
    addBackgroundToMenu();
  };
  window.onscroll = function() {
    addBackgroundToMenu();
  };

  function addBackgroundToMenu() {
    var menu = document.getElementById("top-menu");
    if (window.pageYOffset > menu.offsetTop) {
      menu.classList.add("menu-bg-scrolling");
    }
    else {
      menu.classList.remove("menu-bg-scrolling");
    }
  }
});
