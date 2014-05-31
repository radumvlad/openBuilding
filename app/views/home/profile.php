<div class="container" style="margin-top:10px;margin-bottom:20px">
	<div>
		<div class="pull-left">
			<h3 class="text-primary" style="margin-top:10px"><?php echo $info->name; ?></h3>
		</div>

		<ul class="nav nav-pills pull-right">
			<li class="active"><a href="#started" data-toggle="pill">Started</a></li>
			<li><a href="#contr" data-toggle="pill">Contributions</a></li>
		</ul>
	</div>

	<div class="clearfix"></div>
	<!-- Tab panes -->
	<div class="tab-content ">
		<div class="tab-pane fade in active" id="started">
			<h4 class="text-info">Buildings he started:</h4>
			<div class="well well-sm">

				<?php for($i = 0; $i < count($own); $i = $i + 2){ ?>
				<div class="row">
					<div class="col-md-5" style="text-align:center">
						<a href="<?php echo base_url();?>index.php/home/map/<?php echo $own[$i]->id;?>" class="btn btn-link"><?php echo $own[$i]->name;?></a>
						<canvas id="b<?php echo $own[$i]->id;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
					<div class="col-md-2"></div>
					<?php if(isset($own[$i+1])) { ?>
					<div class="col-md-5" style="text-align:center">
						<a href="<?php echo base_url();?>index.php/home/map/<?php echo $own[$i+1]->id;?>" class="btn btn-link"><?php echo $own[$i+1]->name;?></a>
						<canvas id="b<?php echo $own[$i+1]->id;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
					<?php }?>
				</div>

				<?php }
				?>
			</div>
		</div>

		<div class="tab-pane fade" id="contr">
			<h4 class="text-info">Buildings he contributed to:</h4>
			<div class="well well-sm">
				<?php for($i = 0; $i < count($contributed); $i = $i + 2){ ?>
				<div class="row">
					<div class="col-md-5" style="text-align:center">
						<a href="<?php echo base_url();?>index.php/home/map/<?php echo $contributed[$i]->id;?>" class="btn btn-link"><?php echo $contributed[$i]->name;?></a>
						<canvas id="b<?php echo $contributed[$i]->id;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
					<div class="col-md-2"></div>
					<?php if(isset($contributed[$i+1])) { ?>
					<div class="col-md-5" style="text-align:center">
						<a href="<?php echo base_url();?>index.php/home/map/<?php echo $contributed[$i+1]->id;?>" class="btn btn-link"><?php echo $contributed[$i+1]->name;?></a>
						<canvas id="b<?php echo $contributed[$i+1]->id;?>" style="background-color:white;border:1px solid #E3E3E3;width:100%;height:300px"></canvas>
					</div>
					<?php }?>
				</div>

				<?php }
				?>
			</div>
		</div>


	</div>
</div>

<script src="<?php echo asset_url();?>javascripts/objects.js"></script>
<script src="<?php echo asset_url();?>javascripts/obview.js"></script>

<script type="text/javascript">

	function init() {
		var data;
		var b;

		<?php 
		for($i = 0; $i < count($own); $i++){ ?>
			
			data = JSON.parse('<?php echo (isset($own[$i]->floor_json))?($own[$i]->floor_json):("[]"); ?>');
			b = new Building('<?php echo "b" . $own[$i]->id;?>');
			b.setInitialObjects(data);

			<?php 
		}

		for($i = 0; $i < count($contributed); $i++){ ?>
			?>
			
			data = JSON.parse('<?php echo (isset($contributed[$i]->floor_json))?($contributed[$i]->floor_json):("[]"); ?>');
			b = new Building('<?php echo "b" . $contributed[$i]->id;?>');
			b.setInitialObjects(data);

			<?php 
		}
		?>


	}

	$(document).ready(function(){
		init();
	});
</script>