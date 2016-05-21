var params = {};
var url ;
//var http = new XMLHttpRequest();
var http;
var markers = [];
var markers_data = [];
var lines = [];
var myLines = [];
var last_marker_count = 0;
var last_data_marker_count = 0;
var last_lat = 0;
var last_lon = 0;
var url_position = 0;
var timestamp = Math.floor(Date.now() / 1000);
var toggle_icon = 0;
var last_sent_cords_timestamp = 0;
var map;

function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
      var R = 6371; // Radius of the earth in km
      var dLat = deg2rad(lat2-lat1);  // deg2rad below
      var dLon = deg2rad(lon2-lon1); 
      var a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2)
      ; 
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
      var d = R * c; // Distance in km
      return d;
    }

    function deg2rad(deg) {
      return deg * (Math.PI/180)
    }


  function get_site_hostname() {
    var a = document.createElement('a');
    a.href = window.location.href;
   //['href','protocol','host','hostname','port','pathname','search','hash'].forEach(function(k) {
   //  console.log(k+':', a[k]);
   //});
   console.log("site hostname");
   console.log(a['hostname']);
   return a['hostname'];
  } 

    function send_cords(lat, lon, type) {
      var timestamp = Math.floor(Date.now() / 1000);
      //GET /?id=206648&timestamp=1459757028&lat=12.9304184&lon=100.8798106&speed=0.0&bearing=0.0&altitude=0.0&batt=100.0

      if (params.disable_cords_radius !=0) {
        console.log("disable_cords_radius enabled");     
        var dist_meters = getDistanceFromLatLonInKm(lat,lon,params.disable_cords_ref_lat,params.disable_cords_ref_lon) * 1000;
        console.log("dist_meters: " + dist_meters);
        console.log("radius: " + params.disable_cords_radius);
        if (params.disable_cords_radius > 0){
          if (dist_meters > params.disable_cords_radius) {
            console.log("now outside disable_cords_radius distance, will disable coords");
            return;
          }
        } else {
          if (dist_meters < Math.abs(params.disable_cords_radius)){
            console.log("now within disable_cords_radius (-) distance, will disable coords");
            return;
          }
        }
      }
      
      if (params.enable_send_cord == 1){
        var url = params.tracker_server + "?id=" + params.id + "&timestamp=" + timestamp + "&lat=" + lat + "&lon=" + lon + "&type=" + type;
        console.log("send_cords");
        console.log("url: " + url);	
        http.open("GET", url, true);
        http.send();
      } else {
        console.log("send_cords disabled");
      }
    }

  function getLocation() {
    if (params.enable_send_cord != 1) {
      console.log("getLocation disabled");
      return;
    }
    if (navigator.geolocation) {
        //navigator.geolocation.watchPosition(showPosition);
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
       alert("Geolocation is not supported by this browser.");
    }
  }

  function getLocation_config() {    
    if (navigator.geolocation) {
        //navigator.geolocation.watchPosition(showPosition);
        navigator.geolocation.getCurrentPosition(showPosition_config);
    } else { 
       alert("Geolocation is not supported by this browser.");
    }
  }

  function showPosition(position){
    showPosition_map(position);
  }


  function showPosition_config(position){
    console.log("showPosition");
    console.log( position.coords.latitude ); 
    console.log( position.coords.longitude );
    var timestamp = Math.floor(Date.now() / 1000);
    lat.value = position.coords.latitude;
    lon.value = position.coords.longitude;
    save_configs();
    make_map_link();
    alert(" Map position now set to present location: lat: " + position.coords.latitude + " lon: " + position.coords.longitude  );
  }

  function showPosition_map(position) {
    console.log("showPosition");
    console.log( position.coords.latitude ); 
    console.log( position.coords.longitude );
    last_lat = position.coords.latitude;
    last_lon = position.coords.longitude
    send_cords(position.coords.latitude, position.coords.longitude, 0);
    //alert(" position sent lat: " + position.coords.latitude + " lon: " + position.coords.longitude + " type: " + type + " id: " + id );
  }



  /**
 * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
 * @param obj1
 * @param obj2
 * @returns obj3 a new object based on obj1 and obj2
 */
  function merge_options(obj1,obj2){
    var obj3 = {};
    for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
    for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
    return obj3;
  }

   function clone(obj) {
     if (null == obj || "object" != typeof obj) return obj;
     var copy = obj.constructor();
     for (var attr in obj) {
       if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
     }
     return copy;
   }

   function update_tracks(points) {
     console.log("udate_tracks");
     if (params.track_id.length == 0) {
       console.log("track_id.length 0 so no tracks ploted");
       return;
     }
     remove_markers();    
     map.removeLayer(lines);
     mylines2 = [];
     console.log("points");
     console.log(points);
     //var points = data_obj.track;
     var tracks = {};
     var info = "";
     for ( var i = 0; i < points.length; i++) {
       if (typeof tracks[points[i]['id']] == "undefined") {
         tracks[points[i]['id']] = [];
         points[i]['lng'] = points[i]['lon'];
         tracks[points[i]['id']].push(points[i]);
       } else {
         points[i]['lng'] = points[i]['lon'];
         tracks[points[i]['id']].push(points[i]);
       }         
     }
     console.log("tracks")
     console.log(tracks);
     var line = {
       "type": "LineString",
       "coordinates": []
     };
     var track = [];
     var a;
     var chosen_tracks = {};
     console.log("params.track_id");
     console.log(params.track_id);

     if (params.track_id.length > 0) {
       chosen_tracks[params.track_id] = tracks[params.track_id];
     }else {
        return;
       //chosen_tracks = tracks;
     }
     console.log("chosen_tracks");
     console.log(chosen_tracks); 
     for (var key in chosen_tracks) {          
       track = clone(chosen_tracks[key]);
       line['coordinates'] = [];
       var last_i = 0;
       info = " id: " + chosen_tracks[key][last_i]['id'] + " lat: " +chosen_tracks[key][last_i]['lat']  + " lon: " +chosen_tracks[key][last_i]['lon'] +" last updated: " + timestampToString(chosen_tracks[key][last_i]['timestamp']) + " type: " + chosen_tracks[key][last_i]['type'];
  
       add_marker(chosen_tracks[key][last_i]['lat'],chosen_tracks[key][last_i]['lon'],42,info);     
       for (var i = 0; i < chosen_tracks[key].length; ++i) {
         a = [];
         a.push(chosen_tracks[key][i]['lon']);
         a.push(chosen_tracks[key][i]['lat']);
         line['coordinates'].push(a);
         last_i = i;
       }
       mylines2.push(clone(line));
     }
     console.log("myline2");
     console.log(mylines2)
     lines = L.geoJson(mylines2).addTo(map);
   }

    function checkAlarm(lat,lon){
      console.log("checkAlarm");
      if (params.alarm_radius == 0){
        console.log("alarm_radius == 0, disabled");
        return;
      }
      console.log(params.alarm_ref_lat);
      console.log(params.alarm_ref_lon);
      var ref_lat = 0;
      var ref_lon = 0;
      // if params.laarm_ref_lat > 0 then judge distance from alarm_ref_lat,lon else judge distance from last known positon of held device
      if (params.alarm_ref_lat != 0){
         ref_lat = params.alarm_ref_lat;
         ref_lon = params.alarm_ref_lon;
      } else {
         ref_lat = last_lat;
         ref_lon = last_lon;
      }
      var dist_meters = getDistanceFromLatLonInKm(lat,lon,ref_lat,ref_lon) * 1000;
      console.log("dist: " + dist_meters);
      // mode 1  trigger dist > alarm_radius ref lat,lon
      console.log("alarm_radius");
      console.log(params.alarm_radius);
      if (params.alarm_radius > 0){
        console.log("check mode 1");
        if (Math.abs(params.alarm_radius) < dist_meters){
           console.log("sound alarm mode 1");
           alarm.play();
           alert("Proximity Alarm Mode 1 trigered, track_id distance from Map Center > " + params.alarm_radius + " Meters");
           
        }
      } else {
        // mode 2 trigger dist < alarm_radius from you (device you hold)
        if (Math.abs(params.alarm_radius) > dist_meters) {
           console.log("sound alarm mode 2");
           alarm.play();
           alert("Proximity Alarm Mode 2 trigered, track_id distance from Ref lat,lon < " + params.alarm_radius +" Meters");          
        }
      }
    }

    function href_from_string(string,detail){
      // example detail can be  ['href','protocol','host','hostname','port','pathname','search','hash']
      var a = document.createElement('a');
      a.href = string;
      return a[detail];
    }

    function iconExists(id) {
      // shouldn't need this function anymore with new custome_icon return from get_track
      // find out if custom icon image file for ID is present on server
      var http2 = new XMLHttpRequest();
      //var url = href_from_string(params.tracker_get_server,'protocol')+"//" + href_from_string(params.tracker_get_server,'hostname') + "/icon_exist.php?id=" + id;
      var url = "./icon_exist.php?id=" + id;
      http2.open("GET", url, false);
      http2.send();
      if(http2.readyState == 4 && http2.status == 200) {
         console.log("response2: ");
         console.log(http2.responseText);
         return http2.responseText;
      }
      return http2.status;
    } 

    

    function make_icon(type,color,id) {
     console.log("make_icon");
     if (type == 41) {
       console.log("type == 41");
       //console.log("params.icon_scale");
       var iconURL = 'uploads/' + id + '_icon.png';
       var custom_icon = L.icon({
         iconUrl: iconURL,    
         iconSize:     [Math.round(57 * params.icon_scale), Math.round(68 * params.icon_scale)], 
         iconAnchor:   [Math.round(28 * params.icon_scale), Math.round(68 * params.icon_scale)], 
         popupAnchor:  [0, Math.round(-69 * params.icon_scale)], 
       });
       return custom_icon;
     }
     var icon_dir = 'images_red';
     if (color == 'green'){
       icon_dir = 'images_green';
     }
     //params.icon_scale
     // Math.round( floatvalue )
     var activeIcon = L.icon({
       iconUrl: icon_dir + '/icon_' + type + '.png',    
       iconSize:     [Math.round(57 * params.icon_scale), Math.round(68 * params.icon_scale)], // size of the icon
       iconAnchor:   [Math.round(28 * params.icon_scale), Math.round(68 * params.icon_scale)], // point of the icon which will correspond to marker's location
       popupAnchor:  [0, Math.round(-69 * params.icon_scale)], // point from which the popup should open relative to the iconAnchor
     });
     return activeIcon;
   } 

    function fix_overlaped_icons(points){
      // this is just a hack that only works on zoom 15 and is different bettween devices and screen sizes
      console.log("fix_overlaped_icons");
      var result = 0;
      for (var o = 0; o < points.length; o++) {
        for (var i = 0; i < points.length; i++) {
          if (o != i) {
            //console.log("test o: " + o + " i: " + i);
            //console.log(points[i]['lat']);
            //console.log(points[o]['lat']);
            //console.log(points[i]['lat'] - points[o]['lat']);
            // might want to make .00013 a bit bigger to add more space bettween icons but not much more, .00014? was .00013 then moved to .00015
            var min_proximity =  Math.pow(2,(19 - map._zoom)) * .0001
            console.log("min_proximity: " + min_proximity);
            //if (Math.abs(points[i]['lat'] - points[o]['lat']) < .0004 && Math.abs(points[i]['lon'] - points[o]['lon']) < .0004){
            if (Math.abs(points[i]['lat'] - points[o]['lat']) < min_proximity && Math.abs(points[i]['lon'] - points[o]['lon']) < min_proximity){
              if (points[o]['moved'] != true){
                points[i]['moved'] = true;
              }
              if (points[o]['overlap_count'] > 0){
                points[o]['overlap_count'] = points[o]['overlap_count'] + 1;
              } else {
                points[o]['overlap_count'] = 1;
              }
              console.log("id: " + points[o]['id'] + " count: " + points[o]['overlap_count']);
              if (points[i]['moved'] != true && map._zoom == 19){              
                if (points[i]['lat'] > points[o]['lat'] ){
                  result = 0.00021 + (points[i]['lat'] - points[o]['lat']);
                } else {
                  result = 0.00021 - (points[i]['lat'] - points[o]['lat']);
                }
                //console.log("result: " + result);
                //console.log(parseFloat(points[i]['lat']) + parseFloat(result));
                points[i]['lat'] =  parseFloat(points[i]['lat']) + parseFloat(result);
              }
            }
          }
        }
      }
    }


    function write_icons(points,color) {
      // points is an array
      console.log("write_icons2");
      console.log("points");
      console.log(points);
      console.log("map zoom");
      console.log(map._zoom);
      var info = "";
      var filter_list;
      var i2;
      var flag_filter = true;
      fix_overlaped_icons(points);
      for (var i = 0; i < points.length; i++) {
        if (params.alarm_radius != 0 && points[i]['id'] == params.track_id && points[i]['id'] != params.id) {
           checkAlarm(points[i]['lat'],points[i]['lon']);
        }
        info = "";
        if (params.filter.length > 0){
          filter_list = params.filter.split(",");
          console.log("filter list");
          console.log(filter_list);
          console.log("type");
          console.log(points[i]['type']);
          flag_filter = true;
          for ( i2 = 0; i2 < filter_list.length; i2++) {
            if (points[i]['type'] == filter_list[i2]){
              console.log("match filter with: " + points[i]['type']);
              console.log(filter_list[i2]);
              flag_filter = false;
            } 
          }
          console.log("flag_filter");
          console.log(flag_filter);
          if (flag_filter) { continue; }
        }
        flag_filter = false;
        if (points[i]['moved'] == true){
          console.log("moved id: " + points[i]['id']);
          if (map._zoom < 19) {
            points[i]['type'] = 44;
            console.log("hit continue");
            //continue;
            flag_filter = true;
          }
        }
        if (flag_filter) { continue; }
        console.log("get here?");
        if (points[i]['overlap_count'] > 0){
          console.log("overlap_count > 0");
          if (map._zoom < 19) {
            var change_type = 43 + points[i]['overlap_count'];
            if (change_type > 52) {
              change_type = 52;
            }
            console.log("change to type: " + change_type);
            points[i]['type'] = change_type;
          }
        }
        console.log("points[i]");
        console.log(points[i]);
        if (typeof points[i]['info'] == "undefined") {
          points[i]['info'] = "";
        }
        console.log("points[i][username] " + points[i]['username']);
        if (typeof points[i]['username'] != 'undefined'){
           info = "user: " + points[i]['username'] ;
        }
        info = info + " id: " + points[i]['id'] + " " + points[i]['info'];
        if (typeof points[i]['pic_file'] != 'undefined') {                
          info = info + " pic_file: " + points[i]['pic_file'] ;
          pic_url = "../shoot/gallery.php?search=" + points[i]['pic_file'];
          info = info + '<a href="' + pic_url + '">Click to View Pic</a>';
        }
        info = info + " lat: " + points[i]['lat'] + " long: " + points[i]['lon'] + " indx: " + points[i]['indx'] +" last updated: " + timestampToString(points[i]['timestamp']) + " type: " + points[i]['type'] ;
        info = "<h2>" + info + "</h2>";
        console.log(" info ");
        console.log(info );
        if (color != "green" && points[i]['type'] < 43) {
          if (toggle_icon == 0){
            toggle_icon = 1;
          } else {
            toggle_icon = 0;
            points[i]['type'] = 41;
          }
        }
        if (points[i]['type'] == 41) {
          if (points[i]['custom_icon'] == "false"){
            console.log("no custome_icon change to type 12 id: " + points[i]['id']);
            points[i]['type'] = 12;
          }
        }
        add_marker_point(points[i],make_icon(points[i]['type'],'red',points[i]['id']),info);      
      }
      console.log("markers");
      console.log(markers);
    }   

     
   function add_marker_point(point,icon,info){
     var LamMarker;
     if (point['type'] > 52) { 
       LamMarker  = L.marker([point['lat'],point['lon']])
       .bindPopup(info);
        markers.push(LamMarker);
        map.addLayer(LamMarker);
     } else {
        LamMarker = L.marker([point['lat'],point['lon']],{icon:icon})	  
        .bindPopup(info);  
        markers.push(LamMarker);
        map.addLayer(LamMarker);
     }
     if (point['type'] == 41) {
       LamMarker.setOpacity(0.85);         
     } else {
        LamMarker.setOpacity(0.75);
     }               
   }

   function add_marker2(lat,lon,icon,info,opacity) {
     info = "<h2>" + info + "</h2>";
     var LamMarker;
     
     if (type > 52 || type < 1) { 
       LamMarker  = L.marker([lat,lon],{icon:icon})
       .bindPopup(info);
       markers.push(LamMarker);
       map.addLayer(LamMarker);
     } else { 
        LamMarker = L.marker([lat,lon],{icon:icon})
	.bindPopup(info);
        markers.push(LamMarker);
        map.addLayer(LamMarker);
     }
     LamMarker.setOpacity(opacity); 
   }
  

   function add_marker(lat,lon,type,info) {
     info = "<h2>" + info + "</h2>";
     var LamMarker;
     
     if (type > 52 || type < 1) { 
       LamMarker  = L.marker([lat,lon],{icon:make_icon(12,'red',0)})
       .bindPopup(info);
       markers.push(LamMarker);
       map.addLayer(LamMarker);
     } else { 
        LamMarker = L.marker([lat,lon],{icon:make_icon(type,'red',0)})
	.bindPopup(info);
        markers.push(LamMarker);
        map.addLayer(LamMarker);
     }
     LamMarker.setOpacity(0.80); 
   }

   function remove_markers() {
     for (var i = 0; i < markers.length ; i++ ) {
          map.removeLayer(markers[i]);
       }
     markers = [];
   }


   function update_icons(obj) {
     console.log("update_icons");
        //{"all":[{"users":[{"id":"206648"lat":"12.9304777","lon":"100.8797813","info":"Scotty is a cool dude.","timestamp":"1460871380","type":"1","username":"Scotty"}],"count":"1"}{"pics":[{"id":"986583"lat":"12.9304578","lon":"100.8797595","info":"","timestamp":"1461042969","type":"16","pic_file":"webcam1461042969.jpg"}],"count":"1"}{"data":[{"id":"986583"lat":"12.9304625","lon":"100.879818","info":"","timestamp":"1461031394","type":"12"}],"count":"1"}]}

     console.log("update_icons obj");
     console.log(obj);
     if (typeof obj['all'] != 'undefined'){
       remove_markers();
       console.log("obj[all] != undefined");
       if (typeof obj['all'][0]['users'] != 'undefined'){         
         console.log('obj[all][0][users] != undefined');
         if (obj['all'][0]['users'].length > 0){
            console.log('length > 0');
            write_icons(obj['all'][0]['users'],"red");
         }
       }
       if (typeof obj['all'][1]['pics'] != 'undefined'){
         if (obj['all'][1]['pics'].length > 0){
           write_icons(obj['all'][1]['pics'],"green");
         }
       }
       if (typeof obj['all'][2]['data'] != 'undefined'){
         if (obj['all'][2]['data'].length > 0){
            write_icons(obj['all'][2]['data'],"green"); 
         }
       }
     } else {
       if (typeof obj['users'] != 'undefined') {
         if (obj['users'].length > 0){
            console.log("obj.users");
            remove_markers();
            write_icons(obj['users'],"red");
         } 
       } 
       if (typeof obj['pics'] != 'undefined') {     
         if (obj['pics'].length > 0){
            console.log("obj.pics");
            remove_markers();
            write_icons(obj['pics'],"green");
         }
       }
       if (typeof obj['data'] != 'undefined') { 
         if (obj['data'].length > 1){
           console.log("obj.data");
           if (obj['data'][0]['type'] == 0) {
             update_tracks(obj['data']);
           } else {
             remove_markers();
             //write_icons(obj['data'],"green"); 
           }
         }
       }
     }
   }
  

  function myTimer() { 
    console.log("myTimer 2");   
    //var url_list = [params.tracker_get_server + "/?type=99&extra=1&id=1", params.tracker_get_server + "/?last=1", params.tracker_get_server + "/?count=100&extra=1"];
    var url_site = href_from_string(params.tracker_get_server,'protocol') +"//" + href_from_string(params.tracker_get_server,'hostname');
    if (params.track_id.length > 0) {
      var url_list = [url_site + "/get_track.php/?mode=all", url_site +"/get_track.php/?mode=data"];
    } else {
      var url_list = [url_site +"/get_track.php/?mode=all"];
    }
    var ts = Date.now() /1000 |0;
    // start time of 25 hours ago
    var start = ts - (60* params.track_time_min);
    // start time of 6 hours ago
    //var start = ts - 21600;
    console.log("timestamp: " + ts); 
    url = url_list[url_position];
    url = url + "&start=" + start;
    url = url + "&radius=" + params.radius_filter;
    url = url + "&lat=" + params.lat + "&lon=" + params.lon;
    console.log("url now");
    console.log(url);
    http.open("GET", url, true);
    http.send();
    var d = new Date();
    console.log("timer");
    console.log( d.toLocaleTimeString());
    //if (url_position == 0) {
    //  myLines = [];
    //}
    url_position = url_position + 1;
    if (url_position > url_list.length-1) {
      url_position = 0;
    }

    if ((((ts - last_sent_cords_timestamp ) > params.send_cord_interval) || (last_sent_cords_timestamp == 0)) && params.enable_send_cord == 1) {
      console.log("getting location");
      //send_cords();
      getLocation();
      last_sent_cords_timestamp = ts;
    }  
  }

   

  function remove_marker(m) {
       map.removeLayer(m);
  }

  function move_marker(m,lat,lon,info,timestamp) {
    console.log("lon");
    console.log(lon);
    console.log("m");
    console.log(m);
    m._latlng.lat = lat;
    m._latlng.lng = lon;
    m._popup._content = info + " " + timestampToString(timestamp);
    m.update();
  }

  

  function timestampToString(t) {
    if (typeof t == "undefined"){
      return " Unknown";
    }
    var t = new Date( t * 1000 );
    var dt = t.toString();
    return dt;
  }

    

  function randomIntFromInterval(min,max) {
    return Math.floor(Math.random()*(max-min+1)+min);
  }

  function reset_default_(){
    // reset to all default configs
    localStorage.removeItem('id'); 
    get_config();
    put_params();
  }

  function save_configs() {
    localStorage.setItem("id", id.value);
    localStorage.setItem("lat", lat.value);
    localStorage.setItem("lon", lon.value );
    localStorage.setItem("zoom", zoom.value);
    localStorage.setItem("track_id", track_id.value);
    localStorage.setItem("track_time_min", track_time_min.value);
    localStorage.setItem("icon_type", icon_type.value);
    localStorage.setItem("info", info.value);
    localStorage.setItem("tracker_server", tracker_server.value);
    localStorage.setItem("tracker_get_server", tracker_get_server.value);
    localStorage.setItem("irc_server", irc_server.value);
    localStorage.setItem("cam_resolution", cam_resolution.value);
    localStorage.setItem("filter", filter.value);
    localStorage.setItem("radius_filter", radius_filter.value);
    localStorage.setItem("update_interval", update_interval.value);
    if (update_interval.value < 3){
      if (id.value > 300000){
        update_interval.value = 2;
      }
    }        
    localStorage.setItem("send_cord_interval", send_cord_interval.value);
    localStorage.setItem("enable_send_cord", enable_send_cord.value);
    localStorage.setItem("max_speed_record", max_speed_record.value);
    localStorage.setItem("min_speed_record", min_speed_record.value);
    localStorage.setItem("icon_scale",icon_scale.value);
    localStorage.setItem("alarm_radius", alarm_radius.value);
    localStorage.setItem("alarm_ref_lat", alarm_ref_lat.value);
    localStorage.setItem("alarm_ref_lon", alarm_ref_lon.value);
    localStorage.setItem("disable_cords_ref_lat", disable_cords_ref_lat.value);
    localStorage.setItem("disable_cords_ref_lon", disable_cords_ref_lon.value);
    localStorage.setItem("disable_cords_radius", disable_cords_radius.value);
  }

  function put_params(){ 
    id.value = params.id;
    lat.value = params.lat;
    lon.value = params.lon;
    zoom.value = params.zoom;
    track_id.value = params.track_id;
    track_time_min.value = params.track_time_min;
    icon_type.value = params.icon_type;
    info.value = params.info;
    tracker_server.value = params.tracker_server;
    tracker_get_server.value = params.tracker_get_server;
    update_interval.value = params.update_interval;
    irc_server.value = params.irc_server;
    cam_resolution.value = params.cam_resolution;
    filter.value = params.filter;
    radius_filter.value = params.radius_filter;       
    send_cord_interval.value = params.send_cord_interval;
    enable_send_cord.value = params.enable_send_cord;
    min_speed_record.value = params.min_speed_record;
    max_speed_record.value = params.max_speed_record;
    icon_scale.value = params.icon_scale;
    alarm_radius.value = params.alarm_radius;
    alarm_ref_lat.value = params.alarm_ref_lat;
    alarm_ref_lon.value = params.alarm_ref_lon;
    disable_cords_ref_lat.value = params.disable_cords_ref_lat;
    disable_cords_ref_lon.value = params.disable_cords_ref_lon;
    disable_cords_radius.value = params.disable_cords_radius;
  }

  function get_config() {
    if (typeof(Storage) !== "undefined") {
       params.id = localStorage.getItem("id");
       params.lat = localStorage.getItem("lat");
       params.lon = localStorage.getItem("lon");
       //document.getElementById("gallery_path").innerHTML='<a href="./shoot/gallery.php?lat=' + params.lat + "&lon=" + params.lon + '"> Gallery </a>';
       params.zoom = localStorage.getItem("zoom");
       params.track_id = localStorage.getItem("track_id");
       params.track_time_min = localStorage.getItem("track_time_min");
       params.icon_type = localStorage.getItem("icon_type");
       params.info = localStorage.getItem("info");
       params.tracker_server = localStorage.getItem("tracker_server");
       params.tracker_get_server = localStorage.getItem("tracker_get_server");
       params.update_interval = localStorage.getItem("update_interval");
       params.irc_server = localStorage.getItem("irc_server");
       params.cam_resolution = localStorage.getItem("cam_resolution");
       params.filter = localStorage.getItem("filter");
       params.radius_filter = localStorage.getItem("radius_filter");
       params.send_cord_interval = localStorage.getItem("send_cord_interval");
       params.enable_send_cord = localStorage.getItem("enable_send_cord");
       params.min_speed_record = localStorage.getItem("min_speed_record");
       params.max_speed_record = localStorage.getItem("max_speed_record");
       params.icon_scale = localStorage.getItem("icon_scale");
       params.alarm_radius = localStorage.getItem("alarm_radius");
       params.alarm_ref_lat = localStorage.getItem("alarm_ref_lat");
       params.alarm_ref_lon = localStorage.getItem("alarm_ref_lon");
       params.disable_cords_ref_lat = localStorage.getItem("disable_cords_ref_lat");
       params.disable_cords_ref_lon = localStorage.getItem("disable_cords_ref_lon");
       params.disable_cords_radius = localStorage.getItem("disable_cords_radius");
       if ((params.disable_cords_radius == null) || ( params.disable_cords_radius.length == 0)) {
         console.log("set default dis_cords_ values");
         localStorage.setItem("disable_cords_ref_lat", "0");
         localStorage.setItem("disable_cords_ref_lon", "0");
         localStorage.setItem("disable_cords_radius", "0");
         params.disable_cords_ref_lat = "0";
         params.disable_cords_ref_lon = "0";
         params.disable_cords_radius = "0";
       }
       if ((params.alarm_ref_lat == null) || ( params.alarm_ref_lat.length == 0)) {
         console.log("set default alarm_ref_lat");
         // 0 sets ref lat,lon to device location
         localStorage.setItem("alarm_ref_lat", "0");
         params.alarm_ref_lat = "0";
       }
       if ((params.alarm_ref_lon == null) || ( params.alarm_ref_lon.length == 0)) {
         console.log("set default alarm_ref_lon");
         localStorage.setItem("alarm_ref_lon", "0");
         params.alarm_ref_lon = "0";
       }
       if ((params.alarm_radius == null) || ( params.alarm_radius.length == 0)) {
         console.log("set default alarm_radius");
         // 0 disables alarm
         localStorage.setItem("alarm_radius", "0");
         params.alarm_radius = "0";
       }
       if ((params.icon_scale == null) || ( params.icon_scale.length == 0)) {
         console.log("set default icon_scale");
         localStorage.setItem("icon_scale", "1");
         params.icon_scale = "1";
       }
       console.log("params");
       console.log(params);
       console.log("typeof params.id");
       console.log(typeof params.id);
       console.log(params.id);
       //console.log(params.id);
       if ( params.id == null || params.id.length == 0) {
         console.log("id was undefined will create one");
         params.id = randomIntFromInterval(300000,999000);
         localStorage.setItem("id", params.id);
         localStorage.setItem("lat", 12.93419);
         localStorage.setItem("lon", 100.892515 );
         localStorage.setItem("zoom", 15);
         localStorage.setItem("track_id", 206648);
         localStorage.setItem("track_time_min", 120);
         localStorage.setItem("icon_type", 12);
         localStorage.setItem("info", "");
         localStorage.setItem("tracker_server", "https://www.funtracker.site/record_track.php");
         localStorage.setItem("tracker_get_server", "https://www.funtracker.site/get_track.php");
         localStorage.setItem("update_interval", "5");
         localStorage.setItem("irc_server", "wilhelm.freenode.net");
         localStorage.setItem("cam_resolution", "low");
         localStorage.setItem("filter", "");
         localStorage.setItem("send_cord_interval", "60");
         localStorage.setItem("enable_send_cord", "1");
         localStorage.setItem("min_speed_record", "0.2");
         localStorage.setItem("max_speed_record", "9");
         // radius_filter is in Meters
         localStorage.setItem("radius_filter", "15000");
         get_config();
       } 
    } else {
       params.id = randomIntFromInterval(300000,999000);
       alert("Sorry, your browser does not support Web Storage, time to upgrade.. your ID now: " + id);       
    }
  }

 
