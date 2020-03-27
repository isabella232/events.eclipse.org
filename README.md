# events.eclipse.org

The [events.eclipse.org](https://events.eclipse.org) website is generated with [Hugo](https://gohugo.io/documentation/).

The site provides date, time and place for the various events (conferences, demo camps, special days, hackathons and trainings) for the Eclipse ecosystem, displayed on a convenient map of the world.

[![Build Status](https://travis-ci.org/EclipseFdn/events.eclipse.org.svg?branch=master)](https://travis-ci.org/eclipsefdn/events.eclipse.org) [![Netlify Status](https://api.netlify.com/api/v1/badges/8d42015f-09c7-46b1-9f9c-419404d01f6d/deploy-status)](https://app.netlify.com/sites/eclipsefdn/deploys)

## Getting started

Install dependencies, build assets and start a webserver:

```bash
npm install 
hugo server
```

## Copy and edit template for new event entries in the JSON File

file location: static/data/EclipseEvents.json

Copy text below and insert into JSON file 
If you insert the last entry in the file, ommit the trailing comma

Replace the second part of each pair with the appropriate information, 

Look up the geo data (longitude and latitude) based on the address. You can use for example this [geocoder](http://www.gpsvisualizer.com/geocode)

To help us, please use the "MMM DD, YYYY" format for the "date" fields. Example: "Nov 3, 2013" will work just fine! <br>
We hope to find a more general solution in the future.We are not using the time for anything (yet), so feel free to add the time in any format that is appropriate.


    {
        "type":"xx",
        "title":"title",
        "date":"date",
        "time":"time",
        "locationName":"location",
        "registration":"reglink",
        "infoLink":"infolink",
        "description":"description",
        "address":{
            "street":"street",
            "zip":"zip",
            "city":"citiy",
            "country": "country",
            "geoLoc":{
                "lat":"00",
                "lon":"00"
            }
        }
    },

xx can be any of the following:

* dc - Demo Camp & Stammtisch
* wg - Working Group Events
* ec - EclipseCon
* ed - Eclipse Day & Hackathons
* et - Training Series
* ee - Other interesting Events
* ve - Virtual Events

Additional events can be added, please let us know if you miss a type

## URL Parameter
Therer are two:
- eventtype
- year
For example: events.eclipse.org/?eventtype=ed&year=2015
shows only the events of type ed (Training) in the year 2015

## How you can help

* please report problems that you encounter, as well as improvement proposals via Eclipse Bugzilla
* add your event information by sending an email to events@eclipse.org

## Contributing

1. [Fork](https://help.github.com/articles/fork-a-repo/) the [eclipsefdn/events.eclipse.org](https://github.com/eclipsefdn/events.eclipse.org) repository
2. Clone repository: `git clone https://github.com/[your_github_username]/events.eclipse.org.git`
3. Create your feature branch: `git checkout -b my-new-feature`
4. Commit your changes: `git commit -m 'Add some feature' -s`
5. Push feature branch: `git push origin my-new-feature`
6. Submit a pull request

### Declared Project Licenses

This program and the accompanying materials are made available under the terms
of the Eclipse Public License v. 2.0 which is available at
http://www.eclipse.org/legal/epl-2.0.

SPDX-License-Identifier: EPL-2.0

## Related projects

### [EclipseFdn/solstice-assets](https://github.com/EclipseFdn/solstice-assets)

Images, less and JavaScript files for the Eclipse Foundation look and feel.

### [EclipseFdn/hugo-solstice-theme](https://github.com/EclipseFdn/hugo-solstice-theme)

Hugo theme of the Eclipse Foundation look and feel. 

## Bugs and feature requests

Have a bug or a feature request? Please search for existing and closed issues. If your problem or idea is not addressed yet, [please open a new issue](https://github.com/eclipsefdn/events.eclipse.org/issues/new).

## Author

**Christopher Guindon (Eclipse Foundation)**

- <https://twitter.com/chrisguindon>
- <https://github.com/chrisguindon>

**Angelika Wittek**

- <https://github.com/AngelikaWittek>

## Trademarks

* Eclipse® is a Trademark of the Eclipse Foundation, Inc.

## Copyright and license

Copyright 2020 the [Eclipse Foundation, Inc.](https://www.eclipse.org) and the [events.eclipse.org authors](https://github.com/eclipsefdn/events.eclipse.org/graphs/contributors). Code released under the [Eclipse Public License Version 2.0 (EPL-2.0)](https://github.com/eclipsefdn/events.eclipse.org/blob/src/LICENSE).