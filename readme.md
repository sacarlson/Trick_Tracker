#Trick Tracker
// Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->

for Detailed info and to see an active website running  https://www.funtracker.site/

This is now a real running website. we continue to publish all of what we have created here on this github account.
Please be sure to feedback any problems you may have to github issues or on the freenode IRC #tricktraker

Trick Tracker is a combo GPS tracking and display map system ment to help us share our locations with groups of people.
It's secondary roll is to allow us to send a freind your present location any place on the planet without ever having to 
install any software on your android or other device.
without any login or user info (at this time) anyone can mark a GPS location with a seletion of Icons that are placed
on the map to show your items of interest locations with a time stamp and your random user ID number.  The website is designed to be used from an android phones by 
running the website from a Firfox browser or most any browser on an android or other portable devices that support GPS
html5 geolocation.  The software also is designed to work with Traccar that is another opensource software, that can optionaly be
installed on any android phone to work. Traccar allows continues tracking of any device over time that can be displayed on
the Trick Tracker map with tracks of past paths taken of tracked targets over a desired period of time.

Bellow here is more technical info about the tracking API format used in Trick Tracker that is derived from the Traccar api format for input.
The output of track.php output format is also discribed here that is used in the javascript section of maps to
provide realtime updates that are polled over time.

these files are setup to support traccar that is used to track your andriod phone location over time
index.php is setup as the point that traccar uses to send updates of location that are stored in a mysql database

mysql db = track_data
table = data
table = pics
table = users

track.php is used to recover data from the mysql database that will search for a given user id over some period of time 
or just return the last known position of the id.  it is setup as an api that will be used by a javascript program (see map app example).
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

{"users":[{"username":"Scotty","lat":"12.9303985","lon":"100.8798076","timestamp":"1460446473","id":"206648","info":"Scotty is a cool dude.","type":"1"},{"username":"acer","lat":"12.9303765","lon":"100.8797842","timestamp":"1460446531","id":"951342","info":"the white acer android phone used in test","type":"0"}],"count":"2"}

https://api.tricktraker.com/track.php/?type=99&extra=1&id=0
  special case when type=99 we pass all tracks accept type=0 (tracked devices)
  only manualy entered positions with manual types are displayed.
{"id":"0","track":[{"lat":"12.9304148","lon":"100.8798189","timestamp":"1460114835","speed":"0","bearing":"0","alt":"0","id":"717355","comment":"","type":"2"},{"lat":"12.9304351","lon":"100.8798171","timestamp":"1460114226","speed":"0","bearing":"0","alt":"0","id":"717355","comment":"","type":"11"}],"count":"2"}

in this case also id can be from 0 - 10 (without added id filters) to allow it's use in signaling in post processing of data on map side


On the Traccar recording side we have the index.php setup to record to mysql for input that looks something like this:
?id=206648&timestamp=1459757028&lat=12.9304184&lon=100.8798106&speed=0.0&bearing=0.0&altitude=0.0&batt=100.0
or 
http://track.surething.biz/?id=206648&timestamp=1459757028&lat=12.9304184&lon=100.8798106&speed=0.0&bearing=0.0&altitude=0.0&batt=100.0


Updated format track2.php Apr 19, 2016:
https://api.tricktraker.com/track.php/?mode=all

{"all":[{"users":[{"id":"206648"lat":"12.9304777","lon":"100.8797813","info":"Scotty is a cool dude.","timestamp":"1460871380","type":"1","username":"Scotty"}],"count":"1"}{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"}],"count":"1"}{"data":[{"id":"986583"lat":"12.9304625","lon":"100.879818","info":"","timestamp":"1461031394","type":"12"}],"count":"1"}]}

https://api.tricktraker.com/track.php/?mode=user

{"users":[{"id":"951342"lat":"12.9304292","lon":"100.8797761","info":"the white acer android phone used in test","timestamp":"1461004164","type":"0","username":"acer"},{"id":"206648"lat":"12.9304777","lon":"100.8797813","info":"Scotty is a cool dude.","timestamp":"1460871380","type":"1","username":"Scotty"}],"count":"2"}

https://api.tricktraker.com/track.php/?mode=pics

{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"},{"id":"796555"lat":"12.9304687","lon":"100.8798153","info":"","timestamp":"1460880321","type":"0","pic_file":"webcam1460880321.jpg"},{"id":"480167"lat":"12.9306165","lon":"100.8805894","info":"","timestamp":"1460870225","type":"0","pic_file":"webcam1460870225.jpg"},{"id":"480167"lat":"12.9305798","lon":"100.8805725","info":"","timestamp":"1460870206","type":"0","pic_file":"webcam1460870206.jpg"},{"id":"480167"lat":"12.9305002","lon":"100.8806056","info":"","timestamp":"1460870111","type":"0","pic_file":"webcam1460870111.jpg"},{"id":"480167"lat":"12.9305862","lon":"100.880625","info":"","timestamp":"1460870103","type":"0","pic_file":"webcam1460870103.jpg"},{"id":"407304"lat":"12.9322973","lon":"100.8798923","info":"","timestamp":"1460821056","type":"0","pic_file":"webcam1460821056.jpg"},{"id":"407304"lat":"12.9340822","lon":"100.881213","info":"","timestamp":"1460813384","type":"0","pic_file":"webcam1460813384.jpg"},{"id":"407304"lat":"12.9376337","lon":"100.8834041","info":"","timestamp":"1460812718","type":"0","pic_file":"webcam1460812718.jpg"},{"id":"394352"lat":"12.932839999999999","lon":"100.88005","info":"","timestamp":"1460728194","type":"0","pic_file":"webcam1460728194.jpg"},{"id":"394352"lat":"12.9356903","lon":"100.8824043","info":"","timestamp":"1460725156","type":"0","pic_file":"webcam1460725156.jpg"},{"id":"394352"lat":"12.9376262","lon":"100.8833695","info":"","timestamp":"1460724329","type":"0","pic_file":"webcam1460724329.jpg"},{"id":"394352"lat":"12.9304584","lon":"100.8798077","info":"","timestamp":"1460716567","type":"0","pic_file":"webcam1460716567.jpg"},{"id":"394352"lat":"12.9304599","lon":"100.8798212","info":"","timestamp":"1460716551","type":"0","pic_file":"webcam1460716551.jpg"}],"count":"14"}

https://api.tricktraker.com/track.php/?mode=data&limit=5

{"data":[{"id":"951342"lat":"12.9304666","lon":"100.8798028","info":"","timestamp":"1461045804","type":"0"},{"id":"951342"lat":"12.9304298","lon":"100.8797761","info":"","timestamp":"1461045684","type":"0"},{"id":"951342"lat":"12.9304289","lon":"100.8797761","info":"","timestamp":"1461045623","type":"0"},{"id":"951342"lat":"12.9304308","lon":"100.8797767","info":"","timestamp":"1461045562","type":"0"},{"id":"951342"lat":"12.9304625","lon":"100.8798081","info":"","timestamp":"1461045502","type":"0"}],"count":"5"}


https://api.tricktraker.com/track.php/?mode=pics&type=16

{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"}],"count":"1"}


https://api.tricktraker.com/track.php/?mode=pics&type=99
  // dump all but type=0, in this case type 16 was the only other file
{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"}],"count":"1"}

https://api.tricktraker.com/track.php/?mode=pics&start=1460870103

{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"},{"id":"796555"lat":"12.9304687","lon":"100.8798153","info":"","timestamp":"1460880321","type":"0","pic_file":"webcam1460880321.jpg"},{"id":"480167"lat":"12.9306165","lon":"100.8805894","info":"","timestamp":"1460870225","type":"0","pic_file":"webcam1460870225.jpg"},{"id":"480167"lat":"12.9305798","lon":"100.8805725","info":"","timestamp":"1460870206","type":"0","pic_file":"webcam1460870206.jpg"},{"id":"480167"lat":"12.9305002","lon":"100.8806056","info":"","timestamp":"1460870111","type":"0","pic_file":"webcam1460870111.jpg"}],"count":"5"}

https://api.tricktraker.com/track.php/?mode=pics&start=1460870103&stop=1460870225

{"pics":[{"id":"480167"lat":"12.9305798","lon":"100.8805725","info":"","timestamp":"1460870206","type":"0","pic_file":"webcam1460870206.jpg"},{"id":"480167"lat":"12.9305002","lon":"100.8806056","info":"","timestamp":"1460870111","type":"0","pic_file":"webcam1460870111.jpg"}],"count":"2"}


Added distance measurement mode and filters Apr 20, 2016:

https://api.tricktraker.com/track.php/?mode=user&lat=12.9304697&lon=100.8798447
  Distance is measured in Meters I think.

{"users":[{"id":"206648","lat":"12.9304646","lon":"100.8798169","info":"Scotty is a cool dude.","timestamp":"1461126764","type":"1","distance":"3.0650795037254","username":"Scotty"},{"id":"333333","lat":"12.949247521683759","lon":"100.88449001312256","info":"This is just a fake user used for test to check new distance measurement software","timestamp":"1461117693","type":"12","distance":"2147.3662969949","username":"fake_guy"},{"id":"951342","lat":"12.9304292","lon":"100.8797761","info":"the white acer android phone used in test","timestamp":"1461004164","type":"0","distance":"8.6902532798667","username":"acer"}],"count":"3"}

http://api.tricktraker.com/track.php/?mode=user&lat=12.9304697&lon=100.8798447&radius=500

{"users":[{"id":"206648","lat":"12.9304597","lon":"100.8798149","info":"Scotty is a cool dude.","timestamp":"1461128866","type":"1","distance":"3.4149112922656","username":"Scotty"},skip{"id":"951342","lat":"12.9304292","lon":"100.8797761","info":"the white acer android phone used in test","timestamp":"1461004164","type":"0","distance":"8.6902532798667","username":"acer"}],"count":"3"}



new changes in tricktraker modified traccar format input for type value example:

api2.tricktraker.com?id=206648&timestamp=1459757028&lat=12.9304184&lon=100.8798106&speed=0.0&bearing=0.0&altitude=0.0&batt=100.0&type=1012

for type over 1000 example 1012 we will subract 1000 and put 12 into user table for type. data table we record type as 0 in this case
for type less than 1000 user table type is unchanged and data table becomes the value of type input.

for use type indicates a stationary icon or a mobile icon.  we consider over 1000 icons to be mobile icons and less than 1000 being stationary icon marker points.

if type is not present in data then it is assumed to be or defaults to type value of 0.



New changes in tricktraker modified traccar API format input with added passkey and value for added security July 10, 2016:
example:
https://www.funtracker.site/record_track.php/id=951342&timestamp=1470023212&lat=12.9304879&lon=100.8798295&speed=0.0&bearing=0.0&altitude=0.0&batt=93.0&passkey=abcdabcd

When the added passkey security is active, then the database will not be updated unless the Id of the user matches with the passkey for that user.  This was implemented due to people exploiting the lack of security in the GPS stream that was causeing peoples markers and tracks to be corrupted by other users.  At present only the built in tracking client in trick tracker and now running on funtracker.site in the browser mode supports the new security measure.  The API for the android Traccar app has not yet been changed or branched yet to provide this security feature.  If we start finding people exploit the traccar api path (not the same as browser path), then we will be forced to disable all android and other traccar clients  from at least the funtracker.site until we have the clients fixed with the added security measure. Our present temp work around at this time is that if passkey is not present in the stream then it is accepted as good or matched.  This presently allows traccar clients to continue to be used but again with no security.
