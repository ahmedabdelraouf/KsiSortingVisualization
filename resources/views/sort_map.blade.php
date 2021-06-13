<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps Multiple Markers</title>
    <script src="https://maps.google.com/maps/api/js?sensor=false"
            type="text/javascript"></script>
    <script type="text/javascript">
        var groupedData = {!! json_encode($groupedData) !!};
    </script>
</head>
<body>

@foreach($groupedData as $index=>$sentiment)
    <button onclick="updateMap('{{$index}}')">{{$index}}</button>
@endforeach
<div id="map" style="width: 500px; height: 400px;"></div>

<script type="text/javascript">


    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: new google.maps.LatLng(-33.92, 151.25),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    var markers = [];
    var output = Object.entries(groupedData).map(([key, value]) => ({key, value}));


    function updateMap(sentiment) {
        let index = output.findIndex(function (info) {
            return info.key === sentiment;
        });
        var sentimentData = output[index].value;
        var newLocations = getLocations(sentimentData);
        setLocationsOnMap(newLocations);
    }

    function getLocations(sentimentData) {
        var newLocations = [];
        for (i = 0; i < sentimentData.length; i++) {
            newLocations.push(sentimentData[i].city);
        }
        return newLocations;
    }

    function setLocationsOnMap(locations) {
        deleteMarkers();
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(locations[i].name);
                    infowindow.open(map, marker);
                }
            })(marker, i));
            markers.push(marker);
        }
    }
    function deleteMarkers() {
        //Loop through all the markers and remove
        for ( i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    };

</script>
</body>
</html>
