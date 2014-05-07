<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>stylesheets/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url();?>stylesheets/bootstrap.min.css">

	<script src="http://code.jquery.com/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="<?php echo asset_url();?>javascripts/main.js"></script>
	<script>


		function fb_login(){
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


		function get_data(){
			FB.api('/me', function(response) {
				$('#user_actions').show();
				$('#user_dropdown').show();
				$('#user_connect').hide();
				$('#user_name').html(response.name + '<b class="caret"></b>');

				$.ajax({
					type: "post",
					url: "<?php echo base_url();?>index.php/users/set_user_id",
					processData: true,
					context: "application/json",
					data: {fb_id: response.id, email: response.email},
					success: function(data) {
						data = JSON.parse(data);

						if(data.status == 1)
							user = 1;
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