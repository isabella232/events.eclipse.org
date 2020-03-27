/*
 * Copyright (c) 2013-2016 Angelika Wittek (Munich, Germany).
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Angelika Wittek
 *    Ralph Müller (rm)
 *    Christopher Guindon
 * 
 * 	24.01.2014 rm: changed sort routine, sort by rank of event first, then by date https://foundation.eclipse.org/infrazilla/show_bug.cgi?id=1968
 *  05.04.2016 rm: changed title, event information opens in new browser tab / window https://foundation.eclipse.org/infrazilla/show_bug.cgi?id=1968
 *  13.05.2016 aw: added markercluster and filter by year feature. 
 */
var map;
var eventIcons = [];
var allEclipseEvents;
var markerClusterGroups = [];
var layerControl;
var eventtypeURLParaValue;
var yearURLParaValue;

function initialize() {

    // read the URL parameters:
    eventtypeURLParaValue = getURLParameter(eventtypeURLParaName);
    yearURLParaValue = getURLParameter(yearURLParaName);

    $.getJSON(jsonDatafile, processData);
}

function processData(data) {

    // set the titles from the constant file:
    document.title = browserTitle;
    $("#pageTitle").append(pageTitle);

    // process event data from jsonfile:
    allEclipseEvents = data.events;

    // --- first, convert event's date field to real date, then sort events by date
    for (var i in allEclipseEvents) {
        allEclipseEvents[i].dateTime = new Date(allEclipseEvents[i].date);
        if (allEclipseEvents[i]['end-date'] != undefined) {
            allEclipseEvents[i].endDateTime = new Date(allEclipseEvents[i]['end-date']);
        }
    }
    allEclipseEvents.sort(compareEventsByRankAndDate);


    // ----------- create regionButtons in the div #regionButtons, for each dataset in regionInfos one button is created:
    for (var regionId in regionInfos) {
        $("#regionButtons").append("<button id=" + regionId + " onClick=\"regionButtonClicked(this.id)\" class=\"btn btn-default regionButton\">" + regionInfos[regionId].name + "</button>");
    }
 
    // ----------- Mail missing Events-Text:
    $("#eventsmail1").append(
        "<p class=\"text-right\">Missing your event on this site? Please open a bug on <a href=\"https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Community&component=events.eclipse.org\">Eclipse Bugzilla</a>.<br> You may also send email to <a href=\"mailto:events@eclipse.org\">events@eclipse.org</a>, but Bugzilla is the better option.</p>");
    $("#eventsmail2").append(
        "<p class=\"text-right\">Missing your event on this site? Please open a bug on <a href=\"https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Community&component=events.eclipse.org\">Eclipse Bugzilla</a>.<br> You may also send email to <a href=\"mailto:events@eclipse.org\">events@eclipse.org</a>, but Bugzilla is the better option.</p>");

    // ----------- Create Buttons for filtering events by year:
    var currentYear = new Date().getFullYear();
    createYearButtons(currentYear);

    // -- Init Map with baseLayer and control:
    initMap();

    // show events for the selected year from the URL paramter or the current events:
    if (yearURLParaValue != null) {
        fillMap(yearURLParaValue, false);
    } else {
        fillMap(currentYear, true);
    }

}

function initMap() {

    // initialize the map on the "map" div with a given center and zoom
    map = L.map('map', {
        center: [regionInfos["world"].lat, regionInfos["world"].lon],
        zoom: 2,
        minZoom: 2
    });

    var baseLayer = initBaseLayer();
    map.addLayer(baseLayer, true);
    layerControl = new L.control.layers(null, null, {
        position: 'bottomright',
        collapsed: false,
        autoZIndex: true
    });
    layerControl.addTo(map);
}

function initBaseLayer() {
    // Layer für die Copyright-Infos:
    var baseLayer = L.tileLayer('//api.tiles.mapbox.com/v3/ralphmueller.i1lhfh9m/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>'
    });
    return baseLayer;
}

// clears the description area and the map (layers and control) from existing data:
function clearMap() {
    clearDescriptionsArea();

    for (var i in markerClusterGroups) {
        map.removeLayer(markerClusterGroups[i]);
        layerControl.removeLayer(markerClusterGroups[i]);
    }

}

function fillMap(year, futureEventsOnly) {

    clearMap();

    markerClusterGroups = [];

    var eclipseEvents = filterEventsByYear(year, futureEventsOnly);

    var markers4Layer = [];

    for (var i in eclipseEvents) {
        var eventtype = eclipseEvents[i].type;

        if (eventtype in eventTypeInfo == false) {
            alert("EventType [" + eventtype + "] from Jsonfile: [" + jsonDatafile + "] is not declared in the eventTypeInfo-Array!");
            continue;
        }

        var texttitle = eclipseEvents[i].title + ", " + eclipseEvents[i].dateTime.toDateString();
        if (eclipseEvents[i].endDateTime != undefined) {
            texttitle += " - " + eclipseEvents[i].endDateTime.toDateString();
        }
        var description = createHtmlDescription(eclipseEvents[i]);

        // If event is not a virtual event, add to map
        if (eclipseEvents[i].type !== "ve") {
	        // create marker for map:
	        var marker = createMarker(eclipseEvents[i], texttitle, description);
	
	        // collect marker depending on eventtype
	        if (eventtype in markers4Layer == false) {
	            markers4Layer[eventtype] = [];
	        }
	
	        markers4Layer[eventtype].push(marker);
        }
        fillDescriptionsArea(eventtype, texttitle, description, i);
    }

    // ------------- build the map with the layers:

    // we want to add the Layers in reverse order to the map -> most important last (foreground)
    var eventTypeKeysReverse = new Array();
    for (var k in eventTypeInfo) {
        eventTypeKeysReverse.unshift(k);
    }

    for (var c = eventTypeKeysReverse.length, n = 0; n < c; n++) {
        eventtype = eventTypeKeysReverse[n];

        if (markers4Layer[eventtype] != undefined) {

            var layer = L.layerGroup(markers4Layer[eventtype]);
            var markerClusterGroup = createMarkerClusterGroup();

            markerClusterGroup.addLayer(layer);

            // if a URL-Parameter is set for an eventtype, then show only the evemts of this type. If not set, then show all:
            if (eventtypeURLParaValue == null || eventtype === eventtypeURLParaValue) {
                map.addLayer(markerClusterGroup);
            }

            markerClusterGroups[eventtype] = markerClusterGroup;

            // put the markerGroup into the control with a name -> checkbox
            layerControl.addOverlay(markerClusterGroup, eventTypeInfo[eventtype].name + " <img src='" + eventTypeInfo[eventtype].image_small + "' />");
        }
    }
}

function createMarkerClusterGroup() {

    var markerClusterGroup = new L.MarkerClusterGroup({

        iconCreateFunction: function(cluster) {

            var markers = cluster.getAllChildMarkers();
            var thisEventtype = markers[0].options.alt;

            return new L.DivIcon({
                iconSize: [0, 0],
                html: '<div class="clusterIcon" style="background:' + eventTypeInfo[thisEventtype].color + '">' +
                    cluster.getChildCount() + '</div>'
            });
        }
    });
    return markerClusterGroup;
}

// Create the filter buttons: for each year in the data and one for upcoming events
function createYearButtons(currentYear) {
    var years = [];
    for (var i in allEclipseEvents) {
        var year = allEclipseEvents[i].dateTime.getFullYear();
        // get the distinct years:
        if (years.indexOf(year) == -1) {
            // ignore the "NaN" value (NaN != NaN -> true always!)
            if (year != year) {
                console.log("wrong dateformat for: " + allEclipseEvents[i].title);
                continue;
            }
            years.push(year);

            // add a entry for each year in the  DropDown list:
            $("#yearDropDown").append("<li role=\"presentation\"><a id=" + year + " role=\"menuitem\" tabindex=\"-1\" href=\"#eecontent\"  onClick=\"fillMap(this.id, false);return true;\"> " + year + " </a></li>");
        }
    }
    // and a entry for the upcoming (future) events:
    $("#yearDropDown").append("<li role=\"presentation\" class=\"divider\"></li>");
    $("#yearDropDown").append("<li role=\"presentation\"><a id=" + currentYear + " role=\"menuitem\" tabindex=\"-1\" href=\"#eecontent\"  onClick=\"fillMap(this.id, true);return true;\"> " + "upcoming" + " </a></li>");


}

function clearDescriptionsArea() {
    if (document.getElementById("_descriptons") != null) {
        $("#_descriptons").remove();
    }
    $("#descriptons").append("<div id=\"_descriptons\" ><\div>");
}

function fillDescriptionsArea(eventtype, texttitle, description, index) {

    //------------------- fill div #descriptons (with html logic):
    // create div for eventtype:
    if (document.getElementById(eventtype) == null) {
        $("#_descriptons").append(
            "<div id=" + eventtype + " class=\"eventType\">" +
            "<h3 class=\"eventTypeTitle\">" +
            " <img src='" + eventTypeInfo[eventtype].image_small + "' /> &nbsp;" +
            eventTypeInfo[eventtype].name + "</h3></div>");
    }

    var divId = eventtype + index;
    var divIdDescr = getDescrId(divId);

    // for every event create 2 divs: one for the title and the second for the collapsible text:
    $("#" + eventtype).append("<div id=" + divId + " onClick=\"revealCollapse(this.id)\" class=\"revealCollapse\" >" + texttitle + "</div>");
    // create div for the eventdescription, divid = (eventtype + i + "_text"), e.g. dc1_descr, ec2_descr
    $("#" + eventtype).append("<div id=" + divIdDescr + " class=\"collapseText\">" + description + "</div>");
}

//	gets the corresponding description div-id for the event div-id
function getDescrId(id) {
    return id + "_descr";
}

// create a marker for the map:
function createMarker(event, texttitle, description) {
    var latLon = new L.LatLng(event.address.geoLoc.lat, event.address.geoLoc.lon);
    var marker = new L.marker(latLon, {
        title: texttitle,
        alt: event.type
    }).bindPopup("<b>" + texttitle + "</b><br>" + description);
    marker.setIcon(getEventIcon(event.type));

    return marker;
}

// Filter the events for the given year and  future events:
function filterEventsByYear(year, futureEventsOnly) {

    var events = [];

    for (var i in allEclipseEvents) {

        if (futureEventsOnly) {

            if (isFutureEvent(allEclipseEvents[i].dateTime)) {
                // get the future events
                events.push(allEclipseEvents[i]);
            }
        } else {

            if (allEclipseEvents[i].dateTime.getFullYear() == year) {
                events.push(allEclipseEvents[i]);
            }
        }
    }
    return events;
}


// get the icon for the given eventtype:
function getEventIcon(type) {
    var xSize = eventTypeInfo[type].icon_size.x;
    var ySize = eventTypeInfo[type].icon_size.y;
    // anchor is calculated from the top left corner of the icon:
    var xAnchor = (xSize / 2);
    var yAnchor = (ySize - 1);

    var EclipseIcon = L.Icon.extend({
        options: {
            iconSize: [xSize, ySize],
            iconAnchor: [xAnchor, yAnchor],
            popupAnchor: [-3, -76]
        }
    });

    if (type in eventIcons == false) {
        eventIcons[type] = new EclipseIcon({
            iconUrl: eventTypeInfo[type].image
        });
    }
    return eventIcons[type];
}


// generate Description for popup from the jsondata:
function createHtmlDescription(eclipseEvent) {
    var d;
    d = "<span>";
    d += "<p>" + eclipseEvent.description + "</p>";
    d += "<p>";
    if (eclipseEvent.company != undefined) {
        d += "<p>Company Name: ";
        d += eclipseEvent.company + "</p>";
    }
    d += "Address:" + "<br>";
    d += eclipseEvent.locationName + "<br>";
    // only add address information if event is not virtual
    if (eclipseEvent.type !== "ve") {
	    d += eclipseEvent.address.street + ", ";
	    d += eclipseEvent.address.zip + " " + eclipseEvent.address.city + ", " + eclipseEvent.address.country;
    }
    d += "</p>";
    d += "<p>";

    if (eclipseEvent.infoLink != null && eclipseEvent.infoLink.trim() != "") {
        d += "<a href=" + eclipseEvent.infoLink + " target=_blank> more information" + "</a>";
    } else {
        d += "<span style='font-weight: bold; color: gray;'>" + "more information" + "</span>";
    }
    d += "&nbsp;&nbsp;&nbsp;&nbsp;"

    if (eclipseEvent.registration != null && eclipseEvent.registration.trim() != "") {
        d += "<a href=" + eclipseEvent.registration + " target=_blank> register here" + "</a>";
        d += "&nbsp;&nbsp;&nbsp;&nbsp;"
    }
    // only add marker onclick if event is not virtual 
    if (eclipseEvent.type !== "ve") {
    	d += "<a href=\"#\" onclick=\"showEventOnMap(" + "'" + eclipseEvent.type + "'" + "," + eclipseEvent.address.geoLoc.lat + "," + eclipseEvent.address.geoLoc.lon + ");\"> show on map</a>";
    }

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


//	sets the latlon coordinates and the zoom for the map, dependend on the region button id.
// Important: the button-ids MUST match the keys in the regionInfos Array!
function regionButtonClicked(buttonId) {
    var latLon = new L.LatLng(regionInfos[buttonId].lat, regionInfos[buttonId].lon);
    var zoom = regionInfos[buttonId].zoom;

    map.setView(latLon, zoom);
}

// zooms in the location of the event with the given lat, lon on the map:
function showEventOnMap(eventtype, lat, lon) {

    // for the case that the layer for this eventtype is not checked in the legend (events of this type are not shown on map): activate the layer
    var markerClusterGroup = markerClusterGroups[eventtype];
    map.addLayer(markerClusterGroup);

    var latLon = new L.LatLng(lat, lon);
    var zoom = 15;

    map.setView(latLon, zoom);
}

// returns true if the event is in the future (after today, 0:00AM)
function isFutureEvent(d) {
    var localMorning = new Date();
    localMorning.setHours(0);
    localMorning.setMinutes(0);
    localMorning.setSeconds(0);
    localMorning.setMilliseconds(0);
    if (localMorning <= d) {
        return (true);
    } else {
        return (false);
    }
}

// compares the rank of event types first, for same ranks compare "date" fields of 2 events, returns
function compareEventsByRankAndDate(e1, e2) {
    // -1 if e1 rank < e2 rank
    // result of compareDates if e1 and e2 ranks are equal
    // +1 e1 rank > e2 rank
    if (eventTypeInfo[e1.type].rank < eventTypeInfo[e2.type].rank) {
        return (-1);
    }
    if (eventTypeInfo[e1.type].rank > eventTypeInfo[e2.type].rank) {
        return (1);
    }
    return compareDates(e1.dateTime, e2.dateTime);
}
// compare two dates, returns
function compareDates(d1, d2) {
    // -1 if d1 before d2
    // 0  if d1 and d2 are equal
    // +1 if d1 is after d2
    return (d1 - d2);
}

// gets the url parameter value for the given name:
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
}