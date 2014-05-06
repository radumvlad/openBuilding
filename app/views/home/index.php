
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ0LnpERZeLc_ih7LCcVRQp0fvyMtbY1Q&sensor=true"></script>
<script type="text/javascript">
    var map, newMarker, markers, search;



    $("#searchInput").keypress(function(e) {
        if(e.which == 13) {
            get_markers($("#searchInput").val());
        }
    });

    function initialize() {
        markers = new Array();

        var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(30.441282, 26.081848),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        get_markers("");
    }

    function get_markers(s){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>index.php/location/get",
            processData: true,
            context: "application/json",
            data: {search: s, latitude: map.getCenter().lat(), longitude:map.getCenter().lng(), zoom: map.getZoom()},
            success: function(data) {

                data = JSON.parse(data);
                
                for(var i = 0; i < markers.length; i++){
                    markers[i].setMap(null);
                }

                markers = new Array();

                for (var i = 0; i < data.length; i++) {

                    var marker = new google.maps.Marker({
                        clickable: true,
                        position: new google.maps.LatLng(data[i].latitude, data[i].longitude),
                        map: map,
                        icon: '<?php echo asset_url();?>img/cladire_small.png',
                        title: data[i].name,
                        id: data[i].id
                    });

                    google.maps.event.addListener(marker, 'click', function() {
                        document.location = "<?php echo base_url();?>index.php/home/map/" + this.id;
                    });

                    markers.push(marker);
                }
                
            },
            error: function() {
                console.log("failure");
            }
        });
    }

    function add_marker()
    {

        newMarker = new google.maps.Marker({
            clickable: true,
            position: map.getCenter(),
            map: map,
            icon: '<?php echo asset_url();?>img/cladire_small.png',
            draggable: true
        });

        google.maps.event.trigger(map, 'resize');
    }

    function done_marker()
    {

        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/add",
            processData: true,
            context: "application/json",
            data: {name: "name", latitude: newMarker.getPosition().lat(), longitude:newMarker.getPosition().lng()},
            success: function(data) {
                data = JSON.parse(data);

                newMarker.title = "name";
                newMarker.id = data.building_id;
                newMarker.draggable = false;
                google.maps.event.addListener(newMarker, 'click', function() {
                    document.location = "<?php echo base_url();?>index.php/home/map/" + newMarker.id;
                });
                google.maps.event.trigger(map, 'resize');

            },
            error: function() {
                console.log("failure");
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

</script>


<div id="map-canvas" style="width: 100%; height: 100%"></div>