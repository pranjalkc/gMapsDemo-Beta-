<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
for (var j = 0; j < listParking.length; j++) {
                                
                                
                            }
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="JavaScript/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASuOY4JMHLEHl1_QFk_wCjT87yAuMXSas "></script>
        <script src="JavaScript/gmaps.js"></script>
        <script src="convVenueData.js"></script>
        <script src="convParkingData.js"></script>
        <script>
            var map;
            var currentLat = 43.2565;
            var currentLng = -79.9648;
            var success = false;
            //var venuePositions = [[], []];
            Math.radians = function (degrees) {
                return degrees * Math.PI / 180;
            };
            Math.degrees = function (radians) {
                return radians * 180 / Math.PI;
            };
            $(document).ready(function () {
                navigator.geolocation.getCurrentPosition(
                        successCallback,
                        failureCallback
                        );
                function createMap() {
                    map = new GMaps({
                        div: "#map-container-2",
                        center: new google.maps.LatLng(currentLat, currentLng),
                        panControl: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        zoom: 14
                    });
                    for (var i = 0; i < listVenues.length; i++) {
                        var latitude = listVenues[i].latitude;
                        var longitude = listVenues[i].longtitude;
                        map.addMarker({
                            lat: latitude,
                            lng: longitude,
                            icon: "images/marker1.png",
                            infoWindow: {
                                content: '<div id = "container">' +
                                        '<p>' + 
                                        listVenues[i].name + '<br>' +
                                        '<div id = "address">' +
                                        listVenues[i].address + '<br>' +
                                        listVenues[i].city + ' ' + listVenues[i].phone + '<br>' +
                                        '<a href = ' + listVenues[i].website + '> Website </a>' + '<br>' +
                                        '<button id="parking" name="' + i + '">Find Nearest Parking</button>' +
                                        '</div>' +
                                        '</p>' +
                                        '</div>',
                                maxWidth: 400
                            }
                        });
                        map.fitZoom();


                    }
                }

                function currentLocation()
                {
                    if (!success)
                        return;
                    map.addMarker({
                        lat: currentLat,
                        lng: currentLng,
                        infoWindow: {
                            content: '<p>you are here</p>'
                        }
                    });
                }
                
                function findParking(id)
                {
                    //console.log(i);
                    $("info").empty();
                    $("error").empty();
                    var i = id;
                    var lat1 = listVenues[i].latitude;
                    var lon1 = listVenues[i].longtitude;
                    map.removeMarkers();
                    currentLocation();
                    map.addMarker({
                        lat: lat1,
                        lng: lon1,
                        icon: "images/marker1.png",
                        infoWindow: {
                            content: '<div id = "container">' +
                                    '<p>' +
                                    listVenues[i].name + '<br>' +
                                    '<div id = "address">' +
                                    listVenues[i].address + '<br>' +
                                    listVenues[i].city + ' ' + listVenues[i].phone + '<br>' +
                                    listVenues[i].website + '<br>' +
                                    '</div>' +
                                    '</p>' +
                                    '</div>'
                        }
                    });
                    var spots = 0;
                    for (var i = 0; i < listParking.length; i++) {

                        var lat2 = listParking[i].latitude;
                        var lon2 = listParking[i].longtitude;
                        //parkingPostions[i] = [latitude, longitude];
                        var R = 6371e3;
                        var lr1 = Math.radians(lat1);
                        var lr2 = Math.radians(lat2);
                        var ltr = Math.radians(lat2 - lat1);
                        var lnr = Math.radians(lon2 - lon1);
                        var a = Math.sin(ltr / 2) * Math.sin(ltr / 2) + Math.cos(lr1) * Math.cos(lr2) * Math.sin(lnr / 2) * Math.sin(lnr / 2);
                        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        var distance = R * c;
                        if (distance <= 700) {
                            spots++;
                            //console.log(listParking[j].address);
                            map.addMarker({
                                lat: lat2,
                                lng: lon2,
                                icon: "images/marker2.png",
                                infoWindow: {
                                    content: '<div id = "container">' +
                                            '<div id = "address">' +
                                            listParking[i].address + '<br>' +
                                            listParking[i].city + '<br>' +
                                            "Distance from the venue: " + Math.round(distance) + ' meters <br>' +
                                            '</div>' +
                                            '</div>'
                                }
                            });
                            map.fitZoom();
                        }
                    }
                    if (spots === 0) {
                        document.getElementById("info").innerHTML ="";
                        document.getElementById("error").innerHTML = "Sorry, there are no parking spots near the venue!";
                    } else {
                        document.getElementById("error").innerHTML ="";
                        document.getElementById("info").innerHTML = "There are " + spots + " parking spots available.";
                    }
                }

                function refreshData() {
                    document.getElementById("error").innerHTML ="";
                    document.getElementById("info").innerHTML ="";
                    $("error").empty();
                    map.removeMarkers();
                    currentLocation();
                    for (var i = 0; i < listVenues.length; i++) {
                        var latitude = listVenues[i].latitude;
                        var longitude = listVenues[i].longtitude;
                        map.addMarker({
                            lat: latitude,
                            lng: longitude,
                            icon: "images/marker1.png",
                            infoWindow: {
                                content: '<div id = "container">' +
                                        '<p>' +
                                        listVenues[i].name + '<br>' +
                                        '<div id = "address">' +
                                        listVenues[i].address + '<br>' +
                                        listVenues[i].city + ' ' + listVenues[i].phone + '<br>' +
                                        '<a href = ' + listVenues[i].website + '> Website </a>' + '<br>' +
                                        '<span class="input-group-btn"> <button type ="button" class = "btn btn-light" id="parking" onClick="findParking(' + i + ')>Find Nearest Parking</button></span>' +
                                        '</div>' +
                                        '</p>' +
                                        '</div>',
                                maxWidth: 400
                            }
                        });
                        map.fitZoom();


                    }
                }


                $("button").click(function () {
                    var i = $(this).attr('name');
                    //var i = document.getElementById("parking").name;
                    findParking(i);
                    console.log(i);

                });

                $("#refresh").click(function () {
                    refreshData();
                });

                function successCallback(position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    success = true;
                    createMap();

                }
                function failureCallback(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errMsg = "User denied the request for Geolocation."
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errMsg = "Location information is unavailable."
                            break;
                        case error.TIMEOUT:
                            errMsg = "The request to get user location timed out."
                            break;
                        case error.UNKNOWN_ERROR:
                            errMsg = "An unknown error occurred."
                            break;
                    }
                    document.getElementById("error").innerHTML = "<p>" + errMsg + "</p>";
                }


            });
        </script>
    </head>
    <body>
        <div class="row">
            <div class ="col-sm-2">
                <img src = "images/logo.png">
            </div>
            <div class="col-sm-9">
                <div class="jumbotron">
                    <p>Hello! This web application shows the Live Music Venues in 
                        Hamilton. Click on the markers to find out more about that 
                        Venue. Note that every venue has an ID, mentioned in the 
                        information window that pops up when you click on a marker. 
                        That ID can be used to find out nearest parking spots 
                        around that venue. Find out about upcoming live music 
                        events in Hamilton 
                        <a href = "https://www.thespec.com/hamilton-events/music/">
                            here</a></p>
                    <form>
                        <div class="input-group col-md-2">
                            <!--label for="parking">ID to find parking</label>
                            <input type="text" class = "form-control" id="ID" placeholder="Parking ID here">
                            <span class="input-group-btn">
                                <button type ="button" class = "btn btn-light" id="parking">Go</button>
                            </span-->

                        </div>
                        <button type="button" class="btn btn-primary" id = "refresh">Refresh</button>
                    </form>
                </div>
            </div>
        </div>

        <div class ="container">

            <div id="map-container-2" class="z-depth-1" style="height: 400px"></div>
            <div id = "error" class = "alert alert-danger"></div>
            <div id ="info" class ="alert alert-info"></div>
        </div>



    </body>
</html>
