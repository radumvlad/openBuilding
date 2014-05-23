<script src="<?php echo asset_url();?>javascripts/objects.js"></script>
<script src="<?php echo asset_url();?>javascripts/obview.js"></script>
<script src="<?php echo asset_url();?>javascripts/obedit.js"></script>


<div>
	<a href="javascript:void(0)" onclick="change('select')">select</a>
	<a href="javascript:void(0)" onclick="change('move')">move</a>
	<a href="javascript:void(0)" onclick="change('erase')">erase</a>
</div>
<div>
	<a href="javascript:void(0)" onclick="change('add_wall')">add wall</a>
	<a href="javascript:void(0)" onclick="change('add_sup')">add stairs up</a>
	<a href="javascript:void(0)" onclick="change('add_sdown')">add stairs down</a>
	<a href="javascript:void(0)" onclick="change('add_sboth')">add stairs both</a>
	<a href="javascript:void(0)" onclick="change('add_door')">add door</a>
	<a href="javascript:void(0)" onclick="change('add_edoor')">add exit door</a>
	<a href="javascript:void(0)" onclick="change('add_label')">add label</a>
	<input type="text" id="label_text" onkeypress="setLabelText(event)" />
</div>




<canvas id="editCanvas" width="1000" height="500" style="border: 1px solid #000000;"></canvas>

<script type="text/javascript">


	function init() {

		var data, b;

		data = JSON.parse('[{ "kind": "wall", "info": { "x1": 1, "y1": 1, "x2": 5, "y2": 1 } }, { "kind": "wall", "info": { "x1": 5, "y1": 1, "x2": 5, "y2": 5 } }, { "kind": "wall", "info": { "x1": 5, "y1": 5, "x2": 1, "y2": 5 } }, { "kind": "wall", "info": { "x1": 1, "y1": 5, "x2": 1, "y2": 1 } }, {"kind": "stair", "info": {"x": 10, "y": 10}, "type": "both"}, {"kind": "door", "info": {"x": 8, "y": 2}, "angle": 0, "type": "normal"}, {"kind": "label", "info": {"x": 2, "y": 2}, "text": "zzz"}]');

		b = new EditableBuilding();
		b.setInitialObjects(data);

	}

	$(document).ready(function(){
		init();
	});

</script>