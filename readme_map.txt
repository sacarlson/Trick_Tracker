<!-- Copyright (c) 2016  Sacarlson  sacarlson_2000@yahoo.com -->

New feature added to Trick Tracker map that allows sending a link with map lat,lon, icon type, zoom level, and added text info for popup.
I plan to later generate a tool that will allow createing these links. until then you will have to create them manually with this format.
the input is json and must use %22 in place of " to suround values.  also must use %20 in place of spaces in text info.

example:

http://track.surething.biz/map/?json={%22lat%22:%2212.889783494440438%22,%22lon%22:%22100.92255592346191%22,%22zoom%22:%2215%22,%22type%22:%221%22,%22info%22:%22it%20be%20Scotty%20%22,%22timestamp%22:%221460190435%22} 

http://track.surething.biz/map/?json={%22lat%22:%2212.889783494440438%22,%22lon%22:%22100.92255592346191%22}

that will center the map at
lat: 12.889783494440438
lon: 100.92255592346191
zoom: 15
info: "it be Scotty"
timestamp: 1460190435

the popup will also contain the last update timestamp of when the info was updated.
