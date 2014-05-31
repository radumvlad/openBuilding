<script src="<?php echo asset_url();?>javascripts/objects.js"></script>
<script src="<?php echo asset_url();?>javascripts/obview.js"></script>
<script src="<?php echo asset_url();?>javascripts/obedit.js"></script>

<div id="actions" class="navbar-map">
	<div class="container" style="text-align:center;">
		<h4 class="pull-left"><?php echo $info->name; ?></h4>

		<div class="pull-right" style="margin-top:10px;">
			<span href="javascript:void(0)" onclick="zoom('in')" class="tool">
				<span class="glyphicon glyphicon-zoom-in"></span>
				<span>Zoom in</span>
			</span>

			<span href="javascript:void(0)" onclick="zoom('out')" class="tool">
				<span class="glyphicon glyphicon-zoom-out"></span>
				<span>Zoom out</span>
			</span>
		</div>
		<div class="pull-right divider-vertical"></div>
		<div class="pull-right" id="edit_only" style="margin-top:10px;display:none">
			<span id="move_tool" href="javascript:void(0)" onclick="change('move', this)" class="tool selected-tool">
				<span class="glyphicon glyphicon-move"></span>
				<span>Move</span>
			</span>
			<span href="javascript:void(0)" onclick="change('select', this)" class="tool">
				<span class="glyphicon glyphicon-hand-up"></span>
				<span>Select</span>
			</span>
			<span href="javascript:void(0)" onclick="change('erase', this)" class="tool">
				<span class="glyphicon glyphicon-trash"></span>
				<span>Erase</span>
			</span>

			<span class="dropdown">
				<span id="add_tool" data-toggle="dropdown" class="tool" href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span><span>Add</span></span>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_wall')" tabindex="-1" role="menuitem">Add wall</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_sup')" tabindex="-1" role="menuitem">Add stairs up</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_sdown')" tabindex="-1" role="menuitem">Add stairs down</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_sboth')" tabindex="-1" role="menuitem">Add stairs both</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_door')" tabindex="-1" role="menuitem">Add door</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_edoor')" tabindex="-1" role="menuitem">Add exit door</a></li>
					<li role="presentation"><a href="javascript:void(0)" onclick="change('add_label')" tabindex="-1" role="menuitem">Add label</a></li>

				</ul>
			</span>
		</div>		
	</div>
</div>

<div id="label_div" style="position:absolute;right:5px;background-color:#E3E4E3;padding:2px;display:none">
	<input type="text" id="label_text" onkeypress="setLabelText(event)" />
</div>

<canvas id="viewCanvas" style="display:block"></canvas>
<canvas id="editCanvas" style="display:none"></canvas>

<span onclick="getFloor('1')" class="floor-css glyphicon glyphicon-chevron-up" style="right:20px;top:250px;cursor:pointer"></span>

<span id="floor_span" class="floor-css" style="right:30px;top:293px;"><?php echo (is_null($info->floor_number))?0:$info->floor_number;?></span>

<span onclick="getFloor('-1')" class="floor-css glyphicon glyphicon-chevron-down" style="right:23px;top:350px;cursor:pointer"></span>

<script type="text/javascript">

	var bid = <?php echo $info->id?>;
	var floor_number = <?php echo (is_null($info->floor_number))?0:$info->floor_number;?>;
	var mode = "view";
	var b;

	function init() {
		var ctx = document.getElementById("viewCanvas").getContext("2d");
		ctx.canvas.width  = $(document).width();
		ctx.canvas.height = $(document).height() - $("#myNav").height() - $("#actions").height() - 3;

		ctx = document.getElementById("editCanvas").getContext("2d");
		ctx.canvas.width  = $(document).width();
		ctx.canvas.height = $(document).height() - $("#myNav").height() - $("#actions").height() - 3;



		var data, json;
		json = '<?php echo (is_null($info->floor_json))?'[]':$info->floor_json; ?>';
		data = JSON.parse(json);

		b = new Building('viewCanvas');
		b.setInitialObjects(data);
	}

	function goToEdit(){
		$.ajax({
			type: "post",
			url: "<?php echo base_url();?>index.php/location/start_edit",
			processData: true,
			context: "application/json",
			data: {building_id: bid},
			success: function(data) {
				data = JSON.parse(data);
				if(data.status == 1){
					mode = "edit";

					$('#view_b').show();
					$('#edit_b').hide();
					$('#edit_only').show();

					$("#viewCanvas").css("display", "none");
					$("#editCanvas").css("display", "block");

					if(data.new == 1){
						var json = b.toJson();
						var data = JSON.parse(json);

						b.destroy();
						b = new EditableBuilding();
						b.setInitialObjects(data);
					}
					else
					{
						$.ajax({
							type: "post",
							url: "<?php echo base_url();?>index.php/location/get_floor_edit",
							processData: true,
							context: "application/json",
							data: {building_id: bid, number: floor_number},
							success: function(data) {
								var data = JSON.parse(data);

								b.destroy();
								b = new EditableBuilding();
								b.setInitialObjects(data);
							},
							error: function() {
								console.log("failure");
							}
						});
					}

				}
			},
			error: function() {
				console.log("failure");
			}
		});
}

function goToView(){

	var saved = save();

	if(saved == true){
		$.ajax({
			type: "post",
			url: "<?php echo base_url();?>index.php/location/end_session",
			processData: true,
			context: "application/json",
			data: {building_id: bid},
			success: function(data) {
				data = JSON.parse(data);
				if(data.status == 1){
					
				}
			},
			error: function() {
				console.log("failure");
			}
		});


		$.ajax({
			type: "post",
			url: "<?php echo base_url();?>index.php/location/get_floor",
			processData: true,
			context: "application/json",
			data: {building_id: bid, number: floor_number},
			success: function(data) {
				data = JSON.parse(data);
				mode = "view";

				$("#viewCanvas").css("display", "block");
				$("#editCanvas").css("display", "none");

				$('#view_b').hide();
				$('#edit_b').show();

				$('#edit_only').hide();

				b.destroy();
				b = new Building('viewCanvas');
				b.setInitialObjects(data);

			},
			error: function() {
				console.log("failure");
			}
		});

	}
}

function zoom(x){
	if(x == 'in')
		b.zoomFrame(1);
	else if(x == 'out')
		b.zoomFrame(-1);
}

function setLabelText(e) {
	if (e.keyCode == 13 && myEditableBuilding.newObject && myEditableBuilding.newObject.kind == "label") {
		b.newObject.text = $("#label_text").val();
		$("#label_div").hide();
	}
}

$(document).ready(function(){
	init();
});

function save(){

	var json = myEditableBuilding.toJson();
	var result;

	$.ajax({
		type: "post",
		url: "<?php echo base_url();?>index.php/location/save_floor",
		processData: true,
		async: false,
		context: "application/json",
		data: {building_id: bid, floor_json: json, floor_number: floor_number},
		success: function(data) {
			data = JSON.parse(data);

			if(data.status == 1)
				result = true;
			else 
				result = false;
		},
		error: function() {
			console.log("failure");
			return false;
		}
	});

	return result;
}

function hasEditedFloor(floor){
	$.ajax({
		type: "post",
		url: "<?php echo base_url();?>index.php/location/has_edited_floor",
		processData: true,
		context: "application/json",
		async: false,
		data: {building_id: bid, floor_number: floor},
		success: function(data) {
			data = JSON.parse(data);
			if(data.has == 1)
				return true;

			return false;
		},
		error: function() {
			console.log("failure");
		}
	});
}

function getFloor(delta){
	var new_floor = floor_number + parseInt(delta);

	if(mode == "edit"){
		save();


		$(".tool").each(function(){
			$(this).removeClass('selected-tool');
		});

		$("#move_tool").addClass('selected-tool');

		if(hasEditedFloor(new_floor)) {
			$.ajax({
				type: "post",
				url: "<?php echo base_url();?>index.php/location/get_floor_edit",
				processData: true,
				context: "application/json",
				data: {building_id: bid, number: new_floor},
				success: function(data) {
					floor_number = new_floor;
					$("#floor_span").text(new_floor);

					data = JSON.parse(data);

					b.destroy();
					b = new EditableBuilding();
					b.setInitialObjects(data);

				},
				error: function() {
					console.log("failure");
				}
			});

		}
		else {
			$.ajax({
				type: "post",
				url: "<?php echo base_url();?>index.php/location/get_floor",
				processData: true,
				context: "application/json",
				data: {building_id: bid, number: new_floor},
				success: function(data) {
					floor_number = new_floor;
					$("#floor_span").text(new_floor);

					data = JSON.parse(data);

					b.destroy();
					b = new EditableBuilding();
					b.setInitialObjects(data);
				},
				error: function() {
					console.log("failure");
				}
			});
		}

	}
	else if(mode == "view"){
		$.ajax({
			type: "post",
			url: "<?php echo base_url();?>index.php/location/get_floor",
			processData: true,
			context: "application/json",
			data: {building_id: bid, number: new_floor},
			success: function(data) {
				floor_number = new_floor;
				$("#floor_span").text(new_floor);

				data = JSON.parse(data);

				b.destroy();
				b = new Building('viewCanvas');
				b.setInitialObjects(data);
			},
			error: function() {
				console.log("failure");
			}
		});
	}
}

function change(action, ths) {

	ths = typeof ths !== 'undefined' ? ths : "";

	$(".tool").each(function(){
		$(this).removeClass('selected-tool');
	});

	if(ths != "")
		$(ths).addClass('selected-tool');
	else
		$("#add_tool").addClass('selected-tool');


	if (action == "select") {
		b.mode = "select";		
	}
	else if (action == "move") {
		b.mode = "move";
	}
	else if (action == "erase") {
		b.mode = "erase";
	}
	else if (action == "add_wall") {
		b.mode = "add";
		b.newObject = new Wall();
	}
	else if (action == "add_sup") {
		b.mode = "add";
		b.newObject = new Stair();
		b.newObject.type = "up";
	}
	else if (action == "add_sdown") {
		b.mode = "add";
		b.newObject = new Stair();
		b.newObject.type = "down";
	}
	else if (action == "add_sboth") {
		b.mode = "add";
		b.newObject = new Stair();
		b.newObject.type = "both";
	}
	else if (action == "add_door") {
		b.mode = "add";
		b.newObject = new Door();
		b.newObject.type = "normal";
	}
	else if (action == "add_edoor") {
		b.mode = "add";
		b.newObject = new Door();
		b.newObject.type = "exit";
	}
	else if (action == "add_label") {
		b.mode = "add";
		b.newObject = new Label();
		$('#label_div').show();
		$('#label_text').focus();

	}
}




</script>