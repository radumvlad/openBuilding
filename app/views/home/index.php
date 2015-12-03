
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCarSX2WaGGxvPGmpPGQ335tN6WhKR9nM8"></script>
<script type="text/javascript">
    var map, newMarker = null, editMarker = null, markers, search = "", zoomLevel = 2;
    var infowindow = new google.maps.InfoWindow({});

    $("#searchInput").keypress(function(e) {
        if(e.which == 13) {
            get_markers($("#searchInput").val());
        }
    });

    function getPosFromArr(id){
        for(var i = 0; i < markers.length; i++){
            if(markers[i].id == id)
                return i;
        }
    }

    function initialize() {

        $("#map-canvas").height($(document).height() - $("#myNav").height() - 1);

        markers = new Array();

        var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(30.441282, 26.081848),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            maxZoom: 15,
            minZoom: 1
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        get_markers(search);
        google.maps.event.addListener(map, 'zoom_changed', zoom_change);

        google.maps.event.addListener(infowindow, 'closeclick', function(){
            if(editMarker != null)
                markers.push(editMarker);
            this.close();
        });
    }

    function zoom_change(){
        var i, prevZoomLevel;
        prevZoomLevel = zoomLevel;
        zoomLevel = parseInt((map.getZoom() - 1) / 3.5);

        if (prevZoomLevel !== zoomLevel) {
            for (i = 0; i < markers.length; i++) {
                markers[i].setIcon('<?php echo asset_url();?>img/cladire'+zoomLevel+'.png');
            }

            if(newMarker != null){
                newMarker.setIcon('<?php echo asset_url();?>img/cladire'+zoomLevel+'.png');
            }

            if(editMarker != null){
                editMarker.setIcon('<?php echo asset_url();?>img/cladire'+zoomLevel+'.png');
            }
        }
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

                    var str = '<div class="container" style="width:250px"><div class="form-horizontal" role="form"><div id="formM" class="form-group" style="margin-bottom:0px;height:30px"><label>Name:</label><span id="textSpan">'+data[i].name+'</span><input id="textMarker" class="form-control input-sm pull-right" style="width:140px;display:none" type="text" value="'+data[i].name+'"></div><div class="form-group" style="margin-bottom:0px"><label>Author:</label><a href="<?php echo base_url();?>index.php/home/profile/' + data[i].user_id + '">'+data[i].owner+'</a></div><div class="form-group" style="margin-bottom:0px"><label>Updated at:</label><span>'+data[i].updated_date+'</span></div><div class="row">';

                    if(user == data[i].user_id || role == 1){
                        str = str + '<a id="e_b" onclick="edit_marker('+data[i].id+');" href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Edit</a>';
                    }
                    else
                    {
                        str = str + '<a id="e_b" style="display:none" onclick="edit_marker('+data[i].id+');" href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Edit</a>';
                    }

                    str = str + '<a id="d_b" style="display:none" onclick="delete_marker('+data[i].id+');" href="javascript:void(0)" class="btn btn-sm btn-danger pull-left">Delete</a><a id="o_b" href="<?php echo base_url();?>index.php/home/map/'+data[i].id+'" class="btn btn-sm btn-success pull-right">Open</a><a id="u_b" style="display:none" onclick="update_marker();" href="javascript:void(0)" class="btn btn-sm btn-info pull-right">Save</a></div></div></div>';

                    var marker = new google.maps.Marker({
                        clickable: true,
                        position: new google.maps.LatLng(data[i].latitude, data[i].longitude),
                        map: map,
                        icon: '<?php echo asset_url();?>img/cladire'+zoomLevel+'.png',
                        title: data[i].name,
                        id: data[i].id,
                        owner_id: data[i].user_id,
                        owner: data[i].owner,
                        iwContent:str,
                    });


                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(this.iwContent);
                        infowindow.open(map,this);
                    });

                    markers.push(marker);
                }
                
            },
            error: function() {
                console.log("failure");
            }
        });
    }

    function add_marker(){
        newMarker = new google.maps.Marker({
            clickable: true,
            position: map.getCenter(),
            map: map,
            icon: '<?php echo asset_url();?>img/cladire'+zoomLevel+'.png',
            draggable: true,
            iwContent: '<div class="container" style="width:250px"><div class="form-horizontal" role="form"><div id="formM" class="form-group" style="margin-bottom:0px"><label>Name:</label><input id="textMarker" class="form-control input-sm pull-right" style="width:190px;display:inline" type="text" value=""></div><div class="row"><a id="u_b" onclick="create_marker();" href="javascript:void(0)" class="btn btn-sm btn-info pull-right">Save</a></div></div></div>'

        });


        google.maps.event.addListener(newMarker, 'click', function() {
            infowindow.setContent(this.iwContent);
            infowindow.open(map,this);
        });

        google.maps.event.trigger(map, 'resize');
    }

    function create_marker(){
        var text = $("#textMarker").val();

        if(text == ""){
            $("#formM").addClass("has-error");
            return false;
        }

        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/add",
            processData: true,
            context: "application/json",
            data: {name: text, latitude: newMarker.getPosition().lat(), longitude:newMarker.getPosition().lng()},
            success: function(data) {
                data = JSON.parse(data);

                newMarker.setTitle(text);
                newMarker.id = data.building_id;
                newMarker.setDraggable(false);
                newMarker.owner_id = user;
                newMarker.owner = data.owner;

                newMarker.iwContent = '<div class="container" style="width:250px"><div class="form-horizontal" role="form"><div id="formM" class="form-group" style="margin-bottom:0px;height:30px"><label>Name:</label><span id="textSpan">'+text+'</span><input id="textMarker" class="form-control input-sm pull-right" style="width:140px;display:none" type="text" value="'+text+'"></div><div class="form-group" style="margin-bottom:0px;height:30px"><label>Author:</label><a href="<?php echo base_url();?>index.php/home/profile/' + user + '">'+data.owner+'</a></div><div class="form-group" style="margin-bottom:0px"><label>Updated at:</label><span>'+'a few seconds ago'+'</span></div><div class="row"><a id="e_b" onclick="edit_marker('+newMarker.id+');" href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Edit</a><a id="d_b" style="display:none" onclick="delete_marker('+newMarker.id+');" href="javascript:void(0)" class="btn btn-sm btn-danger pull-left">Delete</a><a id="o_b" href="<?php echo base_url();?>index.php/home/map/'+newMarker.id+'" class="btn btn-sm btn-success pull-right">Open</a><a id="u_b" style="display:none" onclick="update_marker();" href="javascript:void(0)" class="btn btn-sm btn-info pull-right">Save</a></div></div></div>'

                google.maps.event.trigger(map, 'resize');

                infowindow.close();

                markers.push(newMarker);
                newMarker = null;

            },
            error: function() {
                console.log("failure");
            }
        });
    }

    function edit_marker(id){
        var loc = getPosFromArr(id);

        editMarker = markers[loc];
        editMarker.setDraggable(true);
        markers.splice(loc, 1);

        $("#textMarker").show();
        $("#textSpan").hide();


        $("#e_b").hide();
        $("#u_b").show();
        $("#d_b").show();
        $("#o_b").hide();

    }

    function delete_marker(id){

        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/delete",
            processData: true,
            context: "application/json",
            data: {building_id: id},
            success: function(data) {

                editMarker.setVisible(false);
                editMarker = null;
                infowindow.close();
            },
            error: function() {
                console.log("failure");
            }
        });
    }

    function update_marker(){
        var text = $("#textMarker").val();

        if(text == ""){
            $("#formM").addClass("has-error");
            return false;
        }

        $.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/edit",
            processData: true,
            context: "application/json",
            data: {name: text, latitude: editMarker.getPosition().lat(), longitude: editMarker.getPosition().lng(), building_id: editMarker.id},
            success: function(data) {
                editMarker.setTitle(text);
                editMarker.setDraggable(false);
                editMarker.iwContent = '<div class="container" style="width:250px"><div class="form-horizontal" role="form"><div id="formM" class="form-group" style="margin-bottom:0px;height:30px"><label>Name:</label><span>'+text+'</span><input id="textMarker" class="form-control input-sm pull-right" style="width:140px;display:inline" type="hidden" value=""></div><div class="form-group" style="margin-bottom:0px"><label>Author:</label><a href="<?php echo base_url();?>index.php/home/profile/' + editMarker.owner_id + '">'+editMarker.owner+'</a></div><div class="form-group" style="margin-bottom:0px"><label>Updated at:</label><span>a few seconds ago</span></div><div class="row"><a id="e_b" onclick="edit_marker('+editMarker.id+');" href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Edit</a><a id="d_b" style="display:none" onclick="delete_marker('+editMarker.id+');" href="javascript:void(0)" class="btn btn-sm btn-danger pull-left">Delete</a><a id="o_b" href="<?php echo base_url();?>index.php/home/map/'+editMarker.id+'" class="btn btn-sm btn-success pull-right">Open</a><a id="u_b" style="display:none" onclick="update_marker();" href="javascript:void(0)" class="btn btn-sm btn-info pull-right">Save</a></div></div></div>';

                google.maps.event.trigger(map, 'resize');

                markers.push(editMarker);
                editMarker = null;

                $("#textMarker").hide();
                $("#textSpan").show();
                $("#textSpan").text(text);

                $("#e_b").show();
                $("#u_b").hide();
                $("#d_b").hide();
                $("#o_b").show();

            },
            error: function() {
                console.log("failure");
            }
        });
    }


    google.maps.event.addDomListener(window, 'load', initialize);


</script>




<div id="map-canvas" style="width: 100%;"></div>

<div id="actionDiv"></div>