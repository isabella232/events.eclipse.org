# Eclipse Events Page

The code implements the [Eclipse Foundation events](http://events.eclipse.org)

Author: Angelika Wittek, Konteno <br>
inspired by work from Christoper Guindon, Eclipse Foundation

Published under the EPL

Requirements: Ralph Mueller, Eclipse Foundation

Prereqs:

* eclipse.org-commons
* Eclipse page boilerplate

Uses external libraries:

* leafletjs
* jQuery

## How-to

### Download content to your local machine

    $ git clone https://(yourname)@git.eclipse.org/r/websites/events.eclipse.org

### Using git on your local machine

make your changes and commit <br>

    $ vi data/EclipseEvents.json // or use any other editor
    $ git add .
    $ git commit -m "commit comments"

### Push changes to Eclipse Foundation git/Gerrit
You will be asked for a password, you can retrieve it with https://git.eclipse.org/r/#/settings/http-password. Please use your Bugzilla login credentials and copy the passowrd. Replace (yourname) with your git / Gerrit user name.

    $ git push https://(yourname)@git.eclipse.org/r/websites/events.eclipse.org HEAD:refs/for/master

Once you have pushed your changes to Gerrit, they will get reviewed and eventually pushed to the site. 

### Review changes that others made

Open https://git.eclipse.org/r/#/q/project:websites/events.eclipse.org,n,z in your web-browser. 

Review changes (this is a more complex task that needs to be discussed)

## Copy and edit template for new event entries in the Jason File

file location: data/EclipseEvents.json

Copy text below and insert into JSON file 
If you insert the last entry in the file, ommit the trailing comma

Replace the second part of each pair with the appropriate information, 

Look up the geo data (longitude and latitude) based on the address. You can use for example this [geocoder](http://www.gpsvisualizer.com/geocode)

The order of appearance in the listing is currently based on the position in the file. In the near future, we will add functionality to automatically sort events and ommit past events. <br>
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

Additional events can be added, please let us know if you miss a type

