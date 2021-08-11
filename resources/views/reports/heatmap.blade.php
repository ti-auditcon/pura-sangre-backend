@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')

<div class="ibox">
    <div class="ibox-body">
        <div id="map" style="width: 900px; height: 540px;"></div>
    </div>
</div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}
   <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdjSN29qOPy3mKi4MGOoRp9VWUP9pPaHc&libraries=visualization"
        type="text/javascript">
    </script>

    <script>
        var map;
        const heatmapData = [];
        var url_geolocation = "{{ url('users/geolocations') }}";

        $.get(url_geolocation, function(locations) {
            var locations_filtered = locations.filter(location => location.lat);

            locations_filtered.forEach((location, index) => {
                heatmapData[index] = { 
                    location: new google.maps.LatLng(location.lat, location.lng),
                    weight: 0.6
                };
            });

            // console.log(heatmapData);
        });

        // var heatmapData = [
        //     new google.maps.LatLng(37.782, -122.447),
        //     new google.maps.LatLng(36.778261, -119.417932),
        //     new google.maps.LatLng(-37.814107, 144.96328),
        //     new google.maps.LatLng(33.867487, 151.20699)
        // ];
        function initializeMap() {
            var myMapOptions = {
                zoom: 14,
                center: new google.maps.LatLng(-34.9893761, -71.2351242),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            
            map = new google.maps.Map(document.getElementById('map'),myMapOptions);

            var heatmap = new google.maps.visualization.HeatmapLayer({
                data: heatmapData,
                dissipating: true,
                radius: 40,
                map
            });
        }

        google.maps.event.addDomListener(window, 'load', initializeMap);
    </script>
@endsection