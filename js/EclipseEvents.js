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
		
	var map;
	var eventIcons = [];

	function initialize() {

		// initialize the map on the "map" div with a given center and zoom
		map = L.map('map', {
			center : [ 37.71859, -5.44922 ],
			zoom : 2,
			minZoom: 2
		});
		
		$.getJSON(jsonDatafile, processData);
	}

	function processData(data) {
		
		// set the titles from the constant file:
		document.title = browserTitle;
		$("#pageTitle").append("<h1 class=\"pageTitle\">"+pageTitle +"</h1>"); 
		
		var baseLayer = initBaseLayer();
		map.addLayer(baseLayer, true);
		
		var markers4Layer = [];
		var title, latLon, description, marker, texttitle, eventtype;
		
		// process event data from jsonfile:
		var eclipseEvents = data.events;
		for ( var i in eclipseEvents) {
			eventtype = eclipseEvents[i].type;
			
			if (eventtype in eventTypeInfo == false) {
				alert("EventType [" + eventtype + "] from Jsonfile: [" +jsonDatafile+ "] is not declared in the eventTypeInfo-Array!");
				continue;
			}
			
			titleMarker = eclipseEvents[i].title;
			texttitle =  eclipseEvents[i].title + ", " + eclipseEvents[i].date + " "+ eclipseEvents[i].time;
			description = createHtmlDescription(eclipseEvents[i]);

			// create markers for map:
			latLon = new L.LatLng(eclipseEvents[i].address.geoLoc.lat,eclipseEvents[i].address.geoLoc.lon);
			marker = new L.marker(latLon, {
				title : titleMarker
			}).bindPopup("<b>" +texttitle + "</b><br>" + description);
			marker.setIcon(getEventIcon(eclipseEvents[i].type));
			
			if (eclipseEvents[i].type in markers4Layer == false ) {
				markers4Layer[eclipseEvents[i].type] = [];
			}
		 	
		 	markers4Layer[eclipseEvents[i].type].push(marker);
			
		 	
		 	//------------------- fill div #descriptons:
			// create div for eventtype:
			if( document.getElementById(eventtype) == null ) {
				$("#descriptons").append(
						"<div id=" +eventtype+ " class=\"eventType\">" 
						+ "<h3 class=\"eventTypeTitle\">" 
						+ " <img src='" +eventTypeInfo[eventtype].image_small + "' /> &nbsp;"
						+ eventTypeInfo[eventtype].name + "</h3></div>");
			}
			
			// for every event create 2 divs: one for the title and the second for the collapsible text:
			// create div for eventtitle in the eventtype div; divid = (eventtype + i), e.g. dc1, ec2
			var divId = eventtype + i;
			$("#"+eventtype).append("<div id=" +divId+" onClick=\"revealCollapse(this.id)\" class=\"revealCollapse\" >" + texttitle + "</div>");
			// create div for the eventdescription, divid = (eventtype + i + "_text"), e.g. dc1_descr, ec2_descr
			var divIdDescr =  getDescrId(divId);
			$("#"+eventtype).append("<div id=" +divIdDescr+" class=\"collapseText\">" + description + "</div>");
		}
		
		// ------------- build the map with the layers:
		var overlayMaps = {};
		var ml;
		for (var t in markers4Layer) {
			ml =  L.layerGroup(markers4Layer[t]);
			map.addLayer(ml);
			overlayMaps[eventTypeInfo[t].name + " <img src='" +eventTypeInfo[t].image_small + "' />" ]  = ml;
		}
		
		var baseMaps = {
			"Map" : baseLayer
		};

        L.control.layers(baseMaps, overlayMaps, {collapsed:false}).addTo(map);
        
        // ----------- create regionButtons in the div #regionButtons, for each dataset in regionInfos one button is created:
        for (var regionId in regionInfos) {
        	$("#regionButtons").append("<button id=" +regionId + " onClick=\"regionButtonClicked(this.id)\" class=\"regionButton\">" + regionInfos[regionId].name + "</button>");
        }
	}

	// Icon für Art des Events auswählen:
	function getEventIcon(type) {
		var xSize = eventTypeInfo[type].icon_size.x;
		var ySize = eventTypeInfo[type].icon_size.y;
		// anchor is calculated from the top left corner of the icon:
		var xAnchor = (xSize/2);
		var yAnchor = (ySize -1);
		var EclipseIcon = L.Icon.extend({
			options : {
				iconSize : [ xSize, ySize ],
				iconAnchor : [ xAnchor, yAnchor ],
				popupAnchor : [ -3, -76 ]
			}
		});
		
		if (type in eventIcons == false) {
			eventIcons[type] = new EclipseIcon({
				iconUrl : eventTypeInfo[type].image});
		}
		return eventIcons[type];
	}

	function initBaseLayer() {
		// Layer für die Copyright-Infos:
		var baseLayer = L
				.tileLayer(
						'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png',
						{
							maxZoom : 18,
							attribution : 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
						});
		return baseLayer;
	}
	// generate Description for popup from the jsondata:
	function createHtmlDescription(eclipseEvent) {
		var d;
		d = "<span>";
		d += "<p style=\"margin-bottom:8px; \">" + eclipseEvent.description + "</p>";
		d += "<p style=\"margin-bottom:8px; \">";
		d += "Address:"+ "<br>";
		d += eclipseEvent.locationName+ "<br>";
		d += eclipseEvent.address.street + ", ";
		d += eclipseEvent.address.zip + " " + eclipseEvent.address.city + ", " + eclipseEvent.address.country;
		d += "</p>";
		d += "<a href=" + eclipseEvent.registration + ">" + "register here"
				+ "</a>";
		d += "&nbsp;&nbsp;&nbsp;&nbsp;"
		d += "<a href=" + eclipseEvent.infoLink + ">" + "more information"
				+ "</a>";
		
		d += "</span>";
		return d;
	}
	
//	for the event description texts, id is the div-id of the event
	function revealCollapse(id) {
		var textId = getDescrId(id);
		if (document.getElementById(textId).style.display == "block") { 
			document.getElementById(textId).style.display = "none" 
		} else { 
			document.getElementById(textId).style.display = "block" 
		}
	}
//	gets the corresponding description div-id for the event div-id 
	function getDescrId(id) {
		return id+"_descr";
	}
	
//	sets the latlon coordinates and the zoom for the map, dependend on the region button id.
	// Important: the button-ids MUST match the keys in the regionInfos Array!!!
	function regionButtonClicked(buttonId) {
	    var latLon = new L.LatLng(regionInfos[buttonId].lat, regionInfos[buttonId].lon);
	    var zoom = regionInfos[buttonId].zoom;
	    
	    map.setView(latLon, zoom);
	}
	
	
	
