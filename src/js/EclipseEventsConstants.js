/*
 * Copyright (c) 2013 Angelika Wittek (Munich, Germany).
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Angelika Wittek
 * 	  Ralph Mueller
 *    Christopher Guindon
 */
var browserTitle = "Eclipse Events";
var pageTitle = "Upcoming Eclipse Events";
var jsonDatafile = 'data/EclipseEvents.json';
var eventtypeURLParaName = "eventtype";
var yearURLParaName = "year";
var eventTypeInfo = {
    ec: {
        rank: 1,
        name: "EclipseCons",
        image: "images/marker-icon-red.png",
        image_small: "images/small-marker-icon-red.png",
        color: "#D93333",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    dc: {
        rank: 2,
        name: "Demo Camps & Stammtisch",
        image: "images/marker-icon-green.png",
        image_small: "images/small-marker-icon-green.png",
        color: "#6AAA16",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    ed: {
        rank: 3,
        name: "Eclipse Days & Hackathons",
        image: "images/marker-icon-yellow.png",
        image_small: "images/small-marker-icon-yellow.png",
        color: "#CEA70A",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    wg: {
        rank: 4,
        name: "Working Group Events",
        image: "images/marker-icon-blue.png",
        image_small: "images/small-marker-icon-blue.png",
        color: "#2B82CB",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    et: {
        rank: 5,
        name: "Training Series",
        image: "images/marker-icon-cyan.png",
        image_small: "images/small-marker-icon-cyan.png",
        color: "#14C6AB",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    ee: {
        rank: 6,
        name: "Other interesting Events",
        image: "images/marker-icon-purple.png",
        image_small: "images/small-marker-icon-purple.png",
        color: "#9B3CB5",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    ve: {
        rank: 7,
        name: "Virtual Events",
        image: "images/marker-icon-purple.png",
        image_small: "images/small-marker-icon-purple.png",
        color: "#9B3CB5",
        icon_size: {
            x: "25",
            y: "41"
        }
    },
    unknown: {
        name: "unknown",
        image: "images/marker-icon-gray.png",
        image_small: "images/small-marker-icon.png",
        color: "grey",
        icon_size: {
            x: "25",
            y: "41"
        }
    }
};
// Infos for the zoomRegion Buttons, howto display the map:	
var regionInfos = {
    world: {
        name: "World",
        lat: "20.71859",
        lon: "40.44922",
        zoom: "2"
    },
    ap: {
        name: "Asia",
        lat: "40.17887",
        lon: "101.68945",
        zoom: "3"
    },
    au: {
        name: "Australia",
        lat: "-23.838315",
        lon: "151.208503",
        zoom: "3"
    },
    emea: {
        name: "Europe",
        lat: "53.3758916",
        lon: "9.7320104",
        zoom: "4"
    },
    na: {
        name: "North America",
        lat: "40.71396",
        lon: "-94.08691",
        zoom: "4"
    },
    de: {
        name: "Germany",
        lat: "51.1599007",
        lon: "10.4429998",
        zoom: "6"
    },
    fr: {
        name: "France",
        lat: "46.768196",
        lon: "2.432664",
        zoom: "6"
    }

};