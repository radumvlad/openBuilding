<body>
 <div role="navigation" class="navbar navbar-inverse navbar-fixed-top" style="position:relative; margin-bottom:0px">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="javascript:void(0)">OpenBuilding</a>
    </div>
    <div class="collapse navbar-collapse">

      <ul class="nav navbar-nav">
        <div class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" id="searchInput" style="width:500px" class="form-control" placeholder="Search">
          </div>
        </div>

      </ul>
      <ul class="nav navbar-nav pull-right">

        <li id="user_actions" class="dropdown" style="display:none">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a onclick="add_marker();" href="javascript:void(0)">Add a building</a></li>
            <li><a onclick="done_marker();" href="javascript:void(0)">Done</a></li>
          </ul>
        </li>
        <li id="user_connect"><a href="javascript:fb_login();"><img src="<?php echo asset_url();?>img/fb_button.png"></a></li>
        <li id="user_dropdown" class="dropdown" style="display:none">
          <a id="user_name" data-toggle="dropdown" class="dropdown-toggle" role="button" href="#"></a>
          <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
            <li><a href="">Fuck it</a></li>
          </ul>

        </li>

      </ul>
    </div>
  </div>
</div>
