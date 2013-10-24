/**
 * Angelika Wittek, 21.10.2013
 */

var browserTitle = "Eclipse Events";
var pageTitle = "See you at the <br>&nbsp;&nbsp;Eclipse Events 2013...";
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
		name : "EclipseDays",
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
	}
};