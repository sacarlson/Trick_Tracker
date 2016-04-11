#Trick Tracker
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->

Trick Tracker is a combo GPS tracking and display map system ment to help us share the locations of girls we happen to see on the streets.
It's secondary roll is to allow us to send a freind your present location any place on the planet without ever having to 
install any software on your android or other device.
without any login or user info (at this time) anyone can mark a GPS location with a seletion of Icons that are placed
on the map to show your items location with a time stamp and your random user ID number.  The website is designed to used from an android phones by 
running the website from a Chrome browser or most any browser on an android or other portable devices that support GPS
html5 geolocation.  The software also is designed to work with Traccar that is another opensource software can optionaly be
installed on any android phone to work. Traccar allows continues tracking of any device over time that can be displayed on
the Trick Tracker map with tracks of past paths taken of tracked targets over a desired period of time.

For more user info  check out http://scottygeekpage.blogspot.com/2016/04/digital-pimp-continue-development.html
that contains discriptions and screen shots and user info on how to use Trick Tracker and points to the presently active
demo of the website. The blog also contains information of the plans of future develpment.
You might also check out http://track.surething.biz/map that is at present the default location of the demo website.

Bellow here is more technical info about the tracking API format used in Trick Tracker that is derived from the Traccar api format for input.
The output of track.php output format is also discribed here that is used in the javascript section of maps to
provide realtime updates that are polled over time.

these files are setup to support traccar that is used to track your andriod phone location over time
index.php is setup as the point that traccar uses to send updates of location that are stored in a mysql database

mysql db = track_data
table = data

track.php is used to recover data from the mysql database that will search for a given user id over some period of time 
or just return the last known position of the id.  it is setup as an api that will be used by a javascript program.
the return data is in json format. it can be accessed with curl or restclient in javascript (see later examples)

examples:
track.surething.biz/track.php/?id=206648
 that will return the last know location of user id 206648 as
{"id":"206648", "lat":"12.9304074", "lon":"100.8797852","timestamp":"1459827219"}

track.surething.biz/track.php/?id=206648&count=4
{"id":"206648", "track":[{"lat":"12.9304074", "lon":"100.8797852","timestamp":"1459827219"},{"lat":"12.9304074", "lon":"100.8797852","timestamp":"1459827239"},...]}

will return the last 10 know positions of user id 20664 with the first element in the array being the last known location

The user id is the device identifier used in the traccar on the android (or other platform) device.

to setup traccar on an android device you must setup traccar server address and port  in this example we used address track.surething.biz  and port 80 that 
is now how we have apache2 setup and listening on. 

 we now also have time windows of timestamps
examples: 

http://track.surething.biz/track.php/?id=206648&count=10&start=1459830074&stop=1459843874

{"id":"206648","track":[{"lat":"12.9304269","lon":"100.879786","timestamp":"1459843274","type":"0"},{"lat":"12.9304415","lon":"100.8798223","timestamp":"1459842674","type":"0"},{"lat":"12.9304294","lon":"100.8798014","timestamp":"1459842074","type":"0"},{"lat":"12.9304252","lon":"100.8798128","timestamp":"1459841474","type":"0"},{"lat":"12.9304452","lon":"100.8798194","timestamp":"1459840874","type":"0"},{"lat":"12.9304077","lon":"100.8797737","timestamp":"1459840274","type":"0"},{"lat":"12.9304291","lon":"100.8798102","timestamp":"1459839674","type":"0"},{"lat":"12.9304447","lon":"100.8798161","timestamp":"1459839074","type":"0"},{"lat":"12.9304172","lon":"100.8797847","timestamp":"1459838473","type":"0"},{"lat":"12.9304278","lon":"100.8798158","timestamp":"1459837873","type":"0"}]:"count":"10"}

http://track.surething.biz/track.php/?id=206648&count=10&start=1459830074&stop=1459843874&extra=1

{"id":"206648","track":[{"lat":"12.9304269","lon":"100.879786","timestamp":"1459843274","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304415","lon":"100.8798223","timestamp":"1459842674","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304294","lon":"100.8798014","timestamp":"1459842074","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304252","lon":"100.8798128","timestamp":"1459841474","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304452","lon":"100.8798194","timestamp":"1459840874","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304077","lon":"100.8797737","timestamp":"1459840274","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304291","lon":"100.8798102","timestamp":"1459839674","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304447","lon":"100.8798161","timestamp":"1459839074","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304172","lon":"100.8797847","timestamp":"1459838473","speed":"0","bearing":"0","alt":"0","type":"0"},{"lat":"12.9304278","lon":"100.8798158","timestamp":"1459837873","speed":"0","bearing":"0","alt":"0","type":"0"}]:"count":"10"}

http://track.surething.biz/track.php/?type=2&extra=1

{"id":"","track":[{"lat":"12.9303781","lon":"100.8797828","timestamp":"1459826918","speed":"0","bearing":"0","alt":"0","id":"951342","type":"2"}]:"count":"1"}

http://track.surething.biz/track.php/?last=1

{"users":[{"username":"Scotty","lat":"12.9304142","lon":"100.8798171","timestamp":"1460010165","id":"206648","info":"Scotty is a cool dude.","type":""},{"username":"acer","lat":"12.9304351","lon":"100.8798416","timestamp":"1460010138","id":"951342","info":"the white acer android phone used in test","type":""}],"count":"2"}

http://track.surething.biz/track.php/?type=99&extra=1&id=0
  special case when type=99 we pass all tracks accept type=0 (tracked devices)
  only manualy entered positions with manual types are displayed.
{"id":"0","track":[{"lat":"12.9304148","lon":"100.8798189","timestamp":"1460114835","speed":"0","bearing":"0","alt":"0","id":"717355","comment":"","type":"2"},{"lat":"12.9304351","lon":"100.8798171","timestamp":"1460114226","speed":"0","bearing":"0","alt":"0","id":"717355","comment":"","type":"11"}],"count":"2"}

