/*
 * Copyright (c) 2013 Angelika Wittek (Munich, Germany).
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Angelika Wittek
 */

var browserTitle = "Eclipse Events";
var pageTitle = "Upcoming Eclipse Events";
var jsonDatafile = 'data/EclipseEvents.json';
var eventTypeInfo = {
	dc : {
		name : "Demo Camps Fall 2013",
		image : "images/marker-icon-green.png",
		image_small : "images/small-marker-icon-green.png",
		icon_size:{
            x:"25",
            y:"41"
        }
	},
	ed : {
		name : "Eclipse Days",
		image : "images/marker-icon-blue.png",
		image_small : "images/small-marker-icon-blue.png",
		icon_size:{
            x:"25",
            y:"41"
        }
	},
	ec : {
		name : "EclipseCons",
		image : "images/marker-icon-red.png",
		image_small : "images/small-marker-icon-red.png",
		icon_size:{
            x:"40",
            y:"60"
        }
	},
	ht : {
		name : "Hackathons",
		image : "images/marker-icon-yellow.png",
		image_small : "images/small-marker-icon-yellow.png",
		icon_size:{
            x:"25",
            y:"41"
        }
	},
	ts : {
		name : "Training Series",
		image : "images/marker-icon-cyan.png",
		image_small : "images/small-marker-icon-cyan.png",
		icon_size:{
            x:"25",
            y:"41"
        }
	},
	unknown : {
		name : "unknown",
		image : "images/marker-icon-gray.png",
		image_small : "images/small-marker-icon.png",
		icon_size:{
            x:"25",
            y:"41"
        }
	}
};
// Infos for the zoomRegion Buttons, howto display the map:	
var regionInfos = {
	world : {
		name : "World",
		lat : "37.71859",
		lon : "-5.44922",
		zoom : "2"
	},
	ap : {
		name : "Asia",
		lat : "40.17887",
		lon : "101.68945",
		zoom : "3"
	},
	emea : {
		name : "Europe",
		lat : "49.83798",
		lon : "15.16113",
		zoom : "4"
	},
	na : {
		name : "North America",
		lat : "40.71396",
		lon : "-94.08691",
		zoom : "4"
	},
	de : {
		name : "Germany",
		lat : "51.1599007",
		lon : "10.4429998",
		zoom : "5"
	},
	fr : {
		name : "France",
		lat : "46.768196",
		lon : "2.432664",
		zoom : "5"
	}
};