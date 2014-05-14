<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<?php 
	if(isset($title)) 
		echo '<title>'.$title.'</title>';
	else
		echo '<title>OpenBuilding</title>';
		?>
	
	<link href="<?php echo asset_url();?>img/cladire0.png" rel="shortcut icon">

	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>stylesheets/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>stylesheets/bootstrap.min.css">

	<script src="http://code.jquery.com/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="<?php echo asset_url();?>javascripts/main.js"></script>
	<script>


		var is_logged = -1;
		var user = 0;

		function fb_login(){
			if(is_logged == -1){
				FB.login(function(response) {
				if (response.authResponse) {
					FB.api('/me', function(response) {
						get_data();
					});
				} 
				else {
					console.log('User cancelled login or did not fully authorize.');
				}
			}, {
			});
			}
			else
				get_data();
			
		}

		function fb_logout(){
			$.ajax({
					type: "post",
					url: "<?php echo base_url();?>index.php/users/logout",
					processData: true,
					context: "application/json",
					success: function(data) {
						data = JSON.parse(data);
						if(data.status == 1){
							is_logged = 0;
							user = 0;

							$('#user_dropdown').hide();
							$('#add_b').hide();
							$('#user_connect').show();	
						}
					},
					error: function() {
						console.log("failure");
					}
				});
		}


		function get_data(){

			FB.api('/me', function(response) {
				

				$.ajax({
					type: "post",
					url: "<?php echo base_url();?>index.php/users/login",
					processData: true,
					context: "application/json",
					data: {fb_id: response.id, email: response.email, name:response.name},
					success: function(data) {
						data = JSON.parse(data);

						if(data.status == 1){
							user = data.id;
							is_logged = 1;

							$('#user_dropdown').show();
							$('#add_b').show();
							$('#user_connect').hide();
							$('#user_name').html(response.name + '<b class="caret"></b>');

							if (typeof markers !== 'undefined') {
								for(var i = 0; i < markers.length; i++){
									if(markers[i].owner_id == user){
										$("#actionDiv").html(markers[i].iwContent);
										$("#e_b").show();
										markers[i].iwContent = $("#actionDiv").html();
										$("#actionDiv").html("");
									}
								}
							}

						}
					},
					error: function() {
						console.log("failure");
					}
				});
			});	

		}
		
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '1426252164255457',
				cookie     : true,  
				xfbml      : true,  
				version    : 'v2.0' 
			});

			FB.getLoginStatus(function(response) {
				if (response.status = 'connected'){
					get_data();
				}

			});
		};

		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

	</script>
</head>