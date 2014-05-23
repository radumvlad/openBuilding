<div class="container" style="margin-top:10px;margin-bottom:20px">
	<h4>Modificari:</h4>
	<div id="buildings">
		<?php foreach($arr as $key => $var){ ?>
		<div class="row well well-sm" id="b<?php echo $key;?>">
			<a class="btn btn-link" href="<?php echo base_url();?>index.php/home/map/<?php echo $key;?>"><h4 style="margin:0"><?php echo $var['name'];?></h4></a>
			<?php 
			$end = max(max(array_keys($var['initial'])), max(array_keys($var['after'])));
			$start = min(min(array_keys($var['initial'])), min(array_keys($var['after'])));

			for($i = $start; $i <= $end; $i++){
				?>

				<div class="row">
					<h4 style="padding:0px 15px">Floor <?php echo $i;?></h4>
					<div class="col-md-5">
						<canvas id="bi<?php echo $key . '_' . $i;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
					<div class="col-md-1 col-md-offset-1" style="height:300px;border-left:2px solid #E3E3E3;">
					</div>
					<div class="col-md-5">
						<canvas id="ba<?php echo $key . '_' . $i;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
				</div>
				<?php 
			}
			?>

			<div>
				<div class="pull-left">
					<span>Author: </span> <a href="#"><?php echo $var['user'];?></a>
					<div> Date:<?php echo $var['date']?></div>
				</div>
				<a href="javascript:void(0)" onclick="accept(<?php echo $key;?>)" class="btn btn-info pull-right">Accept</a>
				<a href="javascript:void(0)" onclick="decline(<?php echo $key;?>)" class="btn btn-warning pull-right">Dismiss</a>
			</div>
		</div>
		<?php } ?>

	</div>
</div>
<script src="<?php echo asset_url();?>javascripts/oblib.js"></script>
<script type="text/javascript">

	function accept(id){
		$.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/accept_edit",
            processData: true,
            context: "application/json",
            data: {building_id: id},
            success: function(data) {
            	data = JSON.parse(data);
            	if(data.status == 1){
            		$("#b" + id).fadeOut();
            	}
            },
            error: function() {
                console.log("failure");
            }
        });

	}

	function decline(id){

		$.ajax({
            type: "post",
            url: "<?php echo base_url();?>index.php/location/decline_edit",
            processData: true,
            context: "application/json",
            data: {building_id: id},
            success: function(data) {
            	data = JSON.parse(data);
            	if(data.status == 1){
            		$("#b" + id).fadeOut();
            	}
            },
            error: function() {
                console.log("failure");
            }
        });
		
	}

	function init() {
		var data1, data2;
		var b1, b2;

		<?php 
		foreach($arr as $key => $var){ 
			$end = max(max(array_keys($var['initial'])), max(array_keys($var['after'])));
			$start = min(min(array_keys($var['initial'])), min(array_keys($var['after'])));

			for($i = $start; $i <= $end; $i++){
				?>
				data1 = JSON.parse('<?php echo (isset($var["initial"][$i]))?($var["initial"][$i]):("[]"); ?>');
				b1 = new Building('<?php echo "bi" . $key . "_" . $i;?>');
				b1.setInitialObjects(data1);

				data2 = JSON.parse('<?php echo (isset($var["after"][$i]))?($var["after"][$i]):("[]"); ?>');
				b2 = new Building('<?php echo "ba" . $key . "_" . $i;?>');
				b2.setInitialObjects(data2);

				<?php 
			}
		}
		?>

	}

	$(document).ready(function(){
		init();
	});
</script>