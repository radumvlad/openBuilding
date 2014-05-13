<body>
 <div id="myNav" role="navigation" class="navbar navbar-inverse navbar-fixed-top" style="position:relative; margin-bottom:0px">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="javascript:void(0)">OpenBuilding</a>
    </div>
    <div class="collapse navbar-collapse">

      <ul class="nav navbar-nav">
        <div class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" id="searchInput" style="width:500px" class="form-control input-sm" placeholder="Search">
          </div>
        </div>

      </ul>
      <ul class="nav navbar-nav pull-right">
        <li id="user_connect"><a href="javascript:fb_login();" style="padding-bottom:13px"><img src="<?php echo asset_url();?>img/fb_button.png"></a></li>
        <li id="user_dropdown" class="dropdown" style="display:none">
          <a id="user_name" data-toggle="dropdown" class="dropdown-toggle" role="button" href="#"></a>
          <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
            <li><a onclick="fb_logout();" href="javascript:void(0)">Logout</a></li>
          </ul>
        </li>
      </ul>
      <button style="display:none" id="add_b" type="button" onclick="add_marker();" class="btn btn-info btn-sm navbar-btn pull-right">Add a building</button>
    </div>
  </div>
</div>
